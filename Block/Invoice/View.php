<?php
/**
 * Copyright (C) EC Brands Corporation - All Rights Reserved
 * Contact Licensing@ECInternet.com for use guidelines
 */
declare(strict_types=1);

namespace ECInternet\Sage300Account\Block\Invoice;

use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\View\Element\Template;
use ECInternet\Sage300Account\Model\Data\Oeinvd;
use ECInternet\Sage300Account\Model\Data\Oeinvh;
use ECInternet\Sage300Account\Model\ResourceModel\Oeinvh\CollectionFactory as OeinvhCollectionFactory;

/**
 * Invoice View Block
 *
 * @SuppressWarnings(PHPMD.LongVariable)
 */
class View extends Template
{
    /**
     * @var \Magento\Framework\Pricing\PriceCurrencyInterface
     */
    private $priceCurrency;

    /**
     * @var \ECInternet\Sage300Account\Model\ResourceModel\Oeinvh\CollectionFactory
     */
    private $oeinvhCollectionFactory;

    /**
     * View constructor.
     *
     * @param \Magento\Framework\View\Element\Template\Context                        $context
     * @param \Magento\Framework\Pricing\PriceCurrencyInterface                       $priceCurrency
     * @param \ECInternet\Sage300Account\Model\ResourceModel\Oeinvh\CollectionFactory $oeinvhCollectionFactory
     * @param array                                                                   $data
     */
    public function __construct(
        Context $context,
        PriceCurrencyInterface $priceCurrency,
        OeinvhCollectionFactory $oeinvhCollectionFactory,
        array $data = []
    ) {
        $this->priceCurrency           = $priceCurrency;
        $this->oeinvhCollectionFactory = $oeinvhCollectionFactory;

        parent::__construct($context, $data);
    }

    /**
     * Get first Oeinvh matching parameter 'id'
     *
     * @return \ECInternet\Sage300Account\Model\Data\Oeinvh|null
     */
    public function getInvoice()
    {
        if ($invoiceId = $this->getRequest()->getParam('id')) {
            /** @var \ECInternet\Sage300Account\Model\ResourceModel\Oeinvh\Collection $oeinvhCollection */
            $oeinvhCollection = $this->oeinvhCollectionFactory->create()
                ->addFieldToFilter(Oeinvh::COLUMN_ID, ['eq' => $invoiceId]);

            if ($oeinvhCollection->getSize() === 1) {
                $oeinvh = $oeinvhCollection->getFirstItem();
                if ($oeinvh instanceof Oeinvh) {
                    return $oeinvh;
                }
            }
        }

        return null;
    }

    /**
     * Get Invoice details: Oeinvd Collection
     *
     * @return \ECInternet\Sage300Account\Api\Data\OeinvdInterface[]|null
     */
    public function getInvoiceDetails()
    {
        /** @var \ECInternet\Sage300Account\Model\Data\Oeinvh $invoice */
        if ($invoice = $this->getInvoice()) {
            return $invoice->getInvoiceDetails();
        }

        return null;
    }

    /**
     * Get the formatted InvoiceDate
     *
     * @param \ECInternet\Sage300Account\Model\Data\Oeinvh $invoice
     *
     * @return string
     */
    public function getInvoiceDateHtml(
        Oeinvh $invoice
    ) {
        if ($invoiceDate = $invoice->getInvoiceDate()) {
            return date('m/d/Y', strtotime((string)$invoiceDate));
        }

        return '';
    }

    /**
     * Get the formatted InvoicePaymentScheduleDueDate
     *
     * @param \ECInternet\Sage300Account\Model\Data\Oeinvh $invoice
     *
     * @return string
     */
    public function getInvoicePaymentScheduleDueDateHtml(
        Oeinvh $invoice
    ) {
        if ($dueDate = $invoice->getInvoicePaymentScheduleDueDate()) {
            return date('m/d/Y', strtotime((string)$dueDate));
        }

        return '';
    }

    /**
     * Get the formatted InvoicePaymenteScheduleDiscountDate
     *
     * @param \ECInternet\Sage300Account\Model\Data\Oeinvh $invoice
     *
     * @return string
     */
    public function getInvoicePaymentScheduleDiscountDateHtml(
        Oeinvh $invoice
    ) {
        if ($discountDate = $invoice->getInvoicePaymentScheduleDiscountDate()) {
            return date('m/d/Y', strtotime((string)$discountDate));
        }

        return '';
    }

    /**
     * Get the formatted InvoiceTotalBeforeTax
     *
     * @param \ECInternet\Sage300Account\Model\Data\Oeinvh $invoice
     *
     * @return string
     */
    public function getInvoiceTotalBeforeTaxHtml(
        Oeinvh $invoice
    ) {
        return $this->format($invoice->getInvoiceTotalBeforeTax());
    }

    /**
     * Get the formatted InvoiceTotalTaxAmount
     *
     * @param \ECInternet\Sage300Account\Model\Data\Oeinvh $invoice
     *
     * @return string
     */
    public function getInvoiceTotalTaxAmountHtml(
        Oeinvh $invoice
    ) {
        return $this->format($invoice->getInvoiceTotalTaxAmount());
    }

    /**
     * Get the formatted TotalDue
     *
     * @param \ECInternet\Sage300Account\Model\Data\Oeinvh $invoice
     *
     * @return string
     */
    public function getTotalDueHtml(
        Oeinvh $invoice
    ) {
        return $this->format($invoice->getTotalDue());
    }

    /**
     * Get the InvoiceSourceCurrency
     *
     * @param \ECInternet\Sage300Account\Model\Data\Oeinvh $invoice
     *
     * @return string
     */
    public function getInvoiceSourceCurrencyHtml(
        Oeinvh $invoice
    ) {
        return $invoice->getInvoiceSourceCurrency()
            ? ' ' . $invoice->getInvoiceSourceCurrency()
            : '';
    }

    /**
     * Get the formatted InvoicePaymentScheduleDiscountAmount
     *
     * @param \ECInternet\Sage300Account\Model\Data\Oeinvh $invoice
     *
     * @return string
     */
    public function getInvoicePaymentScheduleDiscountAmountHtml(
        Oeinvh $invoice
    ) {
        $invoicePaymentScheduleDiscountAmount = $invoice->getInvoicePaymentScheduleDiscountAmount();
        if ($invoicePaymentScheduleDiscountAmount !== null) {
            return $this->format($invoicePaymentScheduleDiscountAmount);
        }

        return '';
    }

    /**
     * Get formatted InvoiceTotalWithTax
     *
     * @param \ECInternet\Sage300Account\Model\Data\Oeinvh $invoice
     *
     * @return string
     */
    public function getInvoiceTotalWithTaxHtml(
        Oeinvh $invoice
    ) {
        return $this->format($invoice->getInvoiceTotalWithTax());
    }

    /**
     * Get the formatted MaxPayment
     *
     * @param \ECInternet\Sage300Account\Model\Data\Oeinvh $invoice
     *
     * @return string
     */
    public function getMaxPaymentHtml(
        Oeinvh $invoice
    ) {
        return $this->format($invoice->getMaxPayment());
    }

    /**
     * Get the formatted RegtainageAmount
     *
     * @param \ECInternet\Sage300Account\Model\Data\Oeinvh $invoice
     *
     * @return string
     */
    public function getRetainageAmountHtml(
        Oeinvh $invoice
    ) {
        return $this->format($invoice->getRetainageAmount());
    }

    /**
     * Get the formatted UnitPrice
     *
     * @param \ECInternet\Sage300Account\Model\Data\Oeinvd $oeinvd
     *
     * @return string
     */
    public function getUnitPriceHtml(
        Oeinvd $oeinvd
    ) {
        return $this->format($oeinvd->getUnitPrice());
    }

    /**
     * Get the formatted ExtendedPrice
     *
     * @param \ECInternet\Sage300Account\Model\Data\Oeinvd $oeinvd
     *
     * @return string
     */
    public function getExtendedPriceHtml(
        Oeinvd $oeinvd
    ) {
        return $this->format($oeinvd->getExtendedPrice());
    }

    /**
     * Get the formatted LineDiscount
     *
     * @param \ECInternet\Sage300Account\Model\Data\Oeinvd $oeinvd
     *
     * @return string
     */
    public function getLineDiscountHtml(
        Oeinvd $oeinvd
    ) {
        return $this->format($oeinvd->getInvoiceDiscountAmount());
    }

    /**
     * Convert and format price value
     *
     * @param float $value
     *
     * @return string
     */
    private function format(float $value)
    {
        return $this->priceCurrency->convertAndFormat($value);
    }
}
