<?php
/**
 * Copyright (C) EC Brands Corporation - All Rights Reserved
 * Contact Licensing@ECInternet.com for use guidelines
 */
declare(strict_types=1);

namespace ECInternet\Sage300Account\Controller\Invoice;

use Magento\Catalog\Model\Product;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Exception\InputException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\StateException;
use Magento\Quote\Api\Data\CartInterface;
use Magento\Quote\Model\Quote;
use ECInternet\Sage300Account\Controller\Invoice;
use ECInternet\Sage300Account\Model\Config;
use ECInternet\Sage300Account\Model\Data\Oeinvh;
use Exception;

/**
 * Open Invoice Post controller
 *
 * @SuppressWarnings(PHPMD.LongVariable)
 * @SuppressWarnings(PHPMD.ShortVariable)
 */
class OpenPost extends Invoice implements HttpPostActionInterface
{
    /**
     * Execute 'OpenPost' action based on request and return result
     *
     * @return \Magento\Framework\Controller\ResultInterface
     * @throws Exception
     */
    public function execute()
    {
        $this->log('execute()');

        /** @var \Magento\Framework\Controller\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();

        $postData = $this->getRequest()->getParams();
        if ($this->isPaymentAttempt($postData)) {
            /** @var \Magento\Quote\Api\Data\CartInterface $quote */
            $quote = $this->getCurrentQuote();

            // Clear out current quote
            $this->checkoutSession->clearQuote();

            foreach ($postData['pay'] as $id => $checkbox) {
                if (isset($postData['amount'][$id])) {
                    $amount = $this->getAmount($postData['amount'][$id]);
                    if ($amount !== null) {
                        $this->log("execute() - User is attempting to pay [$amount] on invoice [$id].");

                        $invoice = $this->getInvoiceById($id);
                        if ($invoice !== null) {
                            $this->addInvoicePaymentToQuote($quote, $invoice->getInvoiceNumber(), $amount);
                        }
                    }
                }
            }

            // Save cart
            $quote->collectTotals();

            try {
                $this->saveAndReplaceQuote($quote);
            } catch (NoSuchEntityException $e) {
                $this->log('execute()', ['exception' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);

                $this->messageManager->addErrorMessage(
                    __('Unable to pay invoice payment: ' . $e->getMessage())
                );

                return $resultRedirect->setPath('accounting/invoice/open');
            }

            return $resultRedirect->setPath('checkout/cart');
        }

        return $resultRedirect->setPath('accounting/invoice/history');
    }

    /**
     * Is the Customer attempting to pay an Invoice?
     *
     * @param array $postData
     *
     * @return bool
     */
    private function isPaymentAttempt(array $postData)
    {
        return (!empty($postData) && isset($postData['pay']));
    }

    /**
     * @param mixed $value
     *
     * @return float|null
     */
    private function getAmount($value)
    {
        // Cache and then check to see if it's valid by cleaning first
        $cleanedAmount = $this->cleanAmount($value);
        if (is_numeric($cleanedAmount)) {
            return (float)$cleanedAmount;
        } else {
            $this->log('getAmount() - cleanedAmount is not numeric');
        }

        // If cleaning fails, check value to see if numeric
        if (is_numeric($value)) {
            return (float)$value;
        }

        // Else return null
        return null;
    }

    /**
     * @param mixed $amountData
     *
     * @return array|string|string[]|null
     */
    private function cleanAmount($amountData)
    {
        try {
            return preg_replace('([^0-9 + .])', '', (string)$amountData);
        } catch (Exception $e) {
            $this->log('cleanAmount()', ['exception' => $e->getMessage()]);
        }

        return null;
    }

    private function getCurrentQuote()
    {
        try {
            return $this->checkoutSession->getQuote();
        } catch (Exception $e) {
            $this->log('getCurrentQuote()', ['exception' => $e->getMessage()]);
        }

        return null;
    }

    /**
     * @param int $invoiceId
     *
     * @return \ECInternet\Sage300Account\Model\Data\Oeinvh|null
     */
    private function getInvoiceById(int $invoiceId)
    {
        $this->log('getInvoiceById()', ['invoiceId' => $invoiceId]);

        /** @var \ECInternet\Sage300Account\Model\Data\Oeinvh $invoice */
        $invoice = $this->oeinvhCollectionFactory->create()
            ->addFieldToFilter(Oeinvh::COLUMN_ID, ['eq' => $invoiceId])
            ->getFirstItem();

        if ($invoice instanceof Oeinvh) {
            return $invoice;
        }

        return null;
    }

    /**
     * Look up InvoicePayment Product and add it to the Quote
     *
     * @param \Magento\Quote\Api\Data\CartInterface $quote
     * @param string                                $docNumber
     * @param float                                 $invoicePaymentAmount
     *
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Exception
     */
    private function addInvoicePaymentToQuote(
        CartInterface $quote,
        string $docNumber,
        float $invoicePaymentAmount
    ) {
        $this->log('addInvoicePaymentToQuote()', [
            'docNumber'            => $docNumber,
            'invoicePaymentAmount' => $invoicePaymentAmount
        ]);

        $invoicePaymentSku = $this->config->getInvoicePaymentSku();
        if (!$invoicePaymentSku) {
            $this->log('addInvoicePaymentToQuote() - Invoice Payment Sku not defined.');
            throw new InputException(__('Invoice Payment Sku not defined.'));
        }

        $invoicePaymentProduct = $this->getProduct($invoicePaymentSku);
        if ($invoicePaymentProduct === null) {
            $this->log('addInvoicePaymentToQuote() - Invoice Payment Product not found.');
            throw new NoSuchEntityException(__('Invoice Payment Product not found.'));
        }

        if (!$invoicePaymentProduct->isSalable()) {
            throw new StateException(__("Invoice Payment Product [$invoicePaymentSku] is not salable."));
        }

        $additionalOptions[Config::ORDER_ITEM_INVOICE_ADDITIONAL_OPTION] = [
            'label' => 'Invoice',
            'value' => $docNumber
        ];

        $this->log('addInvoicePaymentToQuote()', ['invoicePaymentProduct BEFORE' => $invoicePaymentProduct->getData()]);
        $invoicePaymentProduct->addCustomOption('additional_options', json_encode($additionalOptions));
        $invoicePaymentProduct->setPrice($invoicePaymentAmount);
        $invoicePaymentProduct->setCustomPrice($invoicePaymentAmount);
        $invoicePaymentProduct->setFinalPrice($invoicePaymentAmount);
        $invoicePaymentProduct->setData('invoice_docnumber', $docNumber);
        $this->log('addInvoicePaymentToQuote()', ['invoicePaymentProduct AFTER' => $invoicePaymentProduct->getData()]);

        $quote->addProduct($invoicePaymentProduct, 1);
        $this->log('addInvoicePaymentToQuote() - Product added, saving cart...');

        $this->cartRepository->save($quote);
        $this->log('addInvoicePaymentToQuote() - Cart saved.');

        if ($invoicePaymentProduct instanceof Product) {
            /** @var \Magento\Quote\Model\Quote\Item $quoteItem */
            if ($quoteItem = $quote->getItemByProduct($invoicePaymentProduct)) {
                $this->log('addInvoicePaymentToQuote()', ['quoteItem BEFORE' => $quoteItem->getData()]);

                $quoteItem->setPrice($invoicePaymentAmount);
                $quoteItem->setCustomPrice($invoicePaymentAmount);
                $quoteItem->setOriginalCustomPrice($invoicePaymentAmount);
                $quoteItem->getProduct()->setIsSuperMode(true);
                $this->log('addInvoicePaymentToQuote()', ['quoteItem AFTER' => $quoteItem->getData()]);
            }
        }

        $quote->collectTotals()->save();
    }

    /**
     * @param string $sku
     *
     * @return \Magento\Catalog\Api\Data\ProductInterface|null
     */
    private function getProduct(string $sku)
    {
        //$this->log('getProduct()', ['sku' => $sku]);

        try {
            return $this->productRepository->get($sku, false, null, true);
        } catch (NoSuchEntityException $e) {
            $this->log('getProduct()', ['exception' => $e->getMessage()]);
        }

        return null;
    }

    /**
     * Saves the Quote and uses it to set the Cart
     *
     * @param \Magento\Quote\Api\Data\CartInterface $quote
     */
    private function saveAndReplaceQuote(
        CartInterface $quote
    ) {
        $this->cartRepository->save($quote);
        if ($quote instanceof Quote) {
            $this->checkoutSession->replaceQuote($quote);
        }
    }

    /**
     * Write to extension log
     *
     * @param string $message
     * @param array  $extra
     *
     * @return void
     */
    private function log(string $message, array $extra = [])
    {
        $this->logger->info('Controller/Invoice/OpenPost - ' . $message, $extra);
    }
}
