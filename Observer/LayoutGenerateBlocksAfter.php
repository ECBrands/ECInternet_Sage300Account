<?php
/**
 * Copyright (C) EC Brands Corporation - All Rights Reserved
 * Contact Licensing@ECInternet.com for use guidelines
 */
declare(strict_types=1);

namespace ECInternet\Sage300Account\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Model\StoreManagerInterface;
use ECInternet\Sage300Account\Logger\Logger;
use ECInternet\Sage300Account\Model\Config;

/**
 * Observer for 'layout_generate_blocks_after' event
 */
class LayoutGenerateBlocksAfter implements ObserverInterface
{
    const BLOCK_NAME_DELIMETER       = 'customer_account-sage300account-delimiter-1';

    const BLOCK_NAME_ORDER_HISTORY   = 'customer_account_index-sage300account-my_order_history';

    const BLOCK_NAME_INVOICE_HISTORY = 'customer_account_index-sage300account-my_invoice_history';

    const BLOCK_NAME_OPEN_INVOICES   = 'customer_account_index-sage300account-my_open_invoices';

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var \ECInternet\Sage300Account\Logger\Logger
     */
    private $logger;

    /**
     * @var \ECInternet\Sage300Account\Model\Config
     */
    private $config;

    /**
     * LayoutGenerateBlocksAfter constructor.
     *
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \ECInternet\Sage300Account\Logger\Logger   $logger
     * @param \ECInternet\Sage300Account\Model\Config    $config
     */
    public function __construct(
        StoreManagerInterface $storeManager,
        Logger $logger,
        Config $config
    ) {
        $this->storeManager = $storeManager;
        $this->logger       = $logger;
        $this->config       = $config;
    }

    /**
     * Remove 'customer_account_dashboard_info' layout blocks
     *
     * @param \Magento\Framework\Event\Observer $observer
     *
     * @return void
     */
    public function execute(
        Observer $observer
    ) {
        /** @var \Magento\Framework\View\LayoutInterface $layout */
        if ($layout = $observer->getData('layout')) {
            if ($this->config->hideCustomerDashboard()) {
                $layout->unsetElement('customer_account_dashboard_info');
            }

            $storeId = $this->getStoreId();
            if (is_numeric($storeId)) {
                if (!$this->config->showSidenavLinks((int)$storeId)) {
                    $layout->unsetElement(self::BLOCK_NAME_DELIMETER);
                    $layout->unsetElement(self::BLOCK_NAME_ORDER_HISTORY);
                    $layout->unsetElement(self::BLOCK_NAME_INVOICE_HISTORY);
                    $layout->unsetElement(self::BLOCK_NAME_OPEN_INVOICES);
                }
            }
        }
    }

    /**
     * Get current StoreId
     *
     * @return int|null
     */
    private function getStoreId()
    {
        try {
            return $this->storeManager->getStore()->getId();
        } catch (NoSuchEntityException $e) {
            $this->log('getStoreId()', ['exception' => $e->getMessage()]);
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
        $this->logger->info('Observer/LayoutGenerateBlocksAfter - ' . $message, $extra);
    }
}
