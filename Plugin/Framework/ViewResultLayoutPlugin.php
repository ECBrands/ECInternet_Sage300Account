<?php
/**
 * Copyright (C) EC Brands Corporation - All Rights Reserved
 * Contact Licensing@ECInternet.com for use guidelines
 */
declare(strict_types=1);

namespace ECInternet\Sage300Account\Plugin\Framework;

use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Framework\App\Request\Http;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\View\Result\Layout;
use Magento\Quote\Api\CartRepositoryInterface;
use ECInternet\Sage300Account\Helper\Data;
use ECInternet\Sage300Account\Logger\Logger;
use Exception;

/**
 * Plugin for Magento\Framework\View\Result\Layout
 */
class ViewResultLayoutPlugin
{
    /**
     * @var \Magento\Checkout\Model\Session
     */
    private $checkoutSession;

    /**
     * @var \Magento\Framework\App\Request\Http
     */
    private $request;

    /**
     * @var \Magento\Quote\Api\CartRepositoryInterface
     */
    private $cartRepository;

    /**
     * @var \ECInternet\Sage300Account\Helper\Data
     */
    private $helper;

    /**
     * @var \ECInternet\Sage300Account\Logger\Logger
     */
    private $logger;

    /**
     * @var array
     */
    private $validRoutes = [
        'accounting',
        'checkout'
    ];

    /**
     * ViewResultLayoutPlugin constructor.
     *
     * @param \Magento\Checkout\Model\Session            $checkoutSession
     * @param \Magento\Framework\App\Request\Http        $request
     * @param \Magento\Quote\Api\CartRepositoryInterface $cartRepository
     * @param \ECInternet\Sage300Account\Helper\Data     $helper
     * @param \ECInternet\Sage300Account\Logger\Logger   $logger
     */
    public function __construct(
        CheckoutSession $checkoutSession,
        Http $request,
        CartRepositoryInterface $cartRepository,
        Data $helper,
        Logger $logger
    ) {
        $this->checkoutSession = $checkoutSession;
        $this->request         = $request;
        $this->cartRepository  = $cartRepository;
        $this->helper          = $helper;
        $this->logger          = $logger;
    }

    /**
     * Empty cart if invoice payment is in Cart and the user is not in the Cart page.
     *
     * @param \Magento\Framework\View\Result\Layout    $subject
     * @param \Magento\Framework\App\ResponseInterface $response
     *
     * @return mixed
     */
    public function beforeRenderResult(
        /** @noinspection PhpUnusedParameterInspection */ Layout $subject,
        /** @noinspection PhpUnusedParameterInspection */ ResponseInterface $response
    ) {
        if ($this->isInvoicePaymentInCart()) {
            $this->log('beforeRenderResult() - Invoice Payment is in cart');
            if (!$this->isUserInCart()) {
                $this->log('beforeRenderResult() - Emptying cart...');
                $this->emptyCart();
            } else {
                $this->log('beforeRenderResult() - User is in cart');
            }
        }

        return null;
    }

    /**
     * Does the Customer have the InvoicePayment product in their Cart?
     *
     * @return bool
     */
    private function isInvoicePaymentInCart()
    {
        if ($invoicePaymentSku = $this->helper->getInvoicePaymentSku()) {
            if ($quote = $this->getCurrentQuote()) {
                foreach ($quote->getAllItems() as $quoteItem) {
                    if ($quoteItem->getSku() == $invoicePaymentSku) {
                        return true;
                    }
                }
            }
        }

        return false;
    }

    /**
     * Is the Customer on the Cart page?
     *
     * @return bool
     */
    private function isUserInCart()
    {
        $route = $this->request->getRouteName();

        return in_array($route, $this->validRoutes);
    }

    /**
     * Empty the Customer's Cart by deleting the current quote.
     *
     * @return void
     */
    private function emptyCart()
    {
        if ($quote = $this->getCurrentQuote()) {
            $this->cartRepository->delete($quote);
        }
    }

    /**
     * Pull the current Quote from CheckoutSession.
     *
     * @return \Magento\Quote\Model\Quote|null
     */
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
     * Write to extension log
     *
     * @param string $message
     * @param array  $extra
     *
     * @return void
     */
    private function log(string $message, array $extra = [])
    {
        $this->logger->info('Plugin/Framework/ViewResultLayoutPlugin - ' . $message, $extra);
    }
}
