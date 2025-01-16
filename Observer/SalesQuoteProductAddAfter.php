<?php
/**
 * Copyright (C) EC Brands Corporation - All Rights Reserved
 * Contact Licensing@ECInternet.com for use guidelines
 */
declare(strict_types=1);

namespace ECInternet\Sage300Account\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Quote\Model\Quote\Item;
use ECInternet\Sage300Account\Helper\Data;
use ECInternet\Sage300Account\Helper\Uom as UomHelper;
use ECInternet\Sage300Account\Logger\Logger;
use Exception;

/**
 * Observer for 'sales_quote_product_add_after' event
 */
class SalesQuoteProductAddAfter implements ObserverInterface
{
    /**
     * @var \ECInternet\Sage300Account\Helper\Data
     */
    private $helper;

    /**
     * @var \ECInternet\Sage300Account\Helper\Uom
     */
    private $uom;

    /**
     * @var \ECInternet\Sage300Account\Logger\Logger
     */
    private $logger;

    /**
     * SalesQuoteProductAddAfter constructor.
     *
     * @param \ECInternet\Sage300Account\Helper\Data   $helper
     * @param \ECInternet\Sage300Account\Helper\Uom    $uomHelper
     * @param \ECInternet\Sage300Account\Logger\Logger $logger
     */
    public function __construct(
        Data $helper,
        UomHelper $uomHelper,
        Logger $logger
    ) {
        $this->helper = $helper;
        $this->uom    = $uomHelper;
        $this->logger = $logger;
    }

    /**
     * Main Observer method for 'sales_quote_product_add_after' event, which has one item in array ('items').
     *
     * Notes:
     * - Because we're watching the '...add_after' event, this probably will not fire when a product is updated.
     * - This appears to only grab the item currently being added to the quote.  We can iterate through all of the
     *   quote items by using '$quoteItem->getQuote()->getItems() (or getAllItems())... figure it out and update this.
     *
     * @param \Magento\Framework\Event\Observer $observer
     *
     * @return void
     */
    public function execute(
        Observer $observer
    ) {
        $this->log('execute()');

        /** @var \Magento\Quote\Model\Quote\Item[] $quoteItems */
        $quoteItems = $observer->getEvent()->getData('items');
        if ($quoteItems && is_array($quoteItems) && count($quoteItems) > 0) {
            /** @var \Magento\Quote\Model\Quote\Item $quoteItem */
            foreach ($quoteItems as $quoteItem) {
                $uom = $this->getUom($quoteItem);

                // Set on QuoteItem
                $quoteItem->setData('uom', $uom);
            }
        }
    }

    /**
     * Calculate UOM for QuoteItem
     *
     * @param \Magento\Quote\Model\Quote\Item $item
     *
     * @return string|null
     */
    private function getUom(
        Item $item
    ) {
        $this->log('getUom()');

        if ($product = $item->getProduct()) {
            if ($quote = $item->getQuote()) {
                if ($customer = $quote->getCustomer()) {
                    $customerGroupId = $customer->getGroupId();
                    if (is_numeric($customerGroupId)) {
                        $customerGroupCode = $this->getCustomerGroupCode((int)$customerGroupId);
                        if ($customerGroupCode !== null) {
                            return $this->uom->getUomText($product->getSku(), $customerGroupCode);
                        } else {
                            $this->log('getUom() - Unable to get customer group code');
                        }
                    } else {
                        $this->log('getUom() - Customer group id is not numeric');
                    }
                } else {
                    $this->log('getUom() - Quote does not have a customer');
                }
            } else {
                $this->log('getUom() - Item does not have a quote');
            }
        } else {
            $this->log('getUom() - Item does not have a product');
        }

        return null;
    }

    private function getCustomerGroupCode(int $customerGroupId)
    {
        try {
            return $this->helper->getCustomerGroupCode($customerGroupId);
        } catch (Exception $e) {
            $this->log('getCustomerGroupCode()', ['exception' => $e->getMessage(), 'trace' => $e->getTrace()]);
        }

        return null;
    }

    /**
     * Write to extension log
     *
     * @param string $message
     * @param array  $extra
     */
    private function log(string $message, array $extra = [])
    {
        $this->logger->info('Observer/SalesQuoteProductAddAfter - ' . $message, $extra);
    }
}
