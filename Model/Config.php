<?php
/**
 * Copyright (C) EC Brands Corporation - All Rights Reserved
 * Contact Licensing@ECInternet.com for use guidelines
 */
declare(strict_types=1);

namespace ECInternet\Sage300Account\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class Config
{
    const CONFIG_PATH_ENABLED                    = 'sage300account/general/enable';

    const CONFIG_PATH_ENABLE_PRODUCT_FILTERING   = 'sage300account/general/product_filtering';

    const CONFIG_PATH_SHOW_UOM                   = 'sage300account/uom/show_uom';

    const CONFIG_PATH_DEFAULT_UOM_CUSTOMER_GROUP = 'sage300account/uom/default_customer_group';

    const CONFIG_PATH_HIDE_CUSTOMER_DASHBOARD    = 'sage300account/customer_account_display/hide_customer_dashboard';

    const CONFIG_PATH_SHOW_SIDENAV_LINKS         = 'sage300account/customer_account_display/show_sidenav_links';

    const CONFIG_PATH_REORDER_ENABLED            = 'sage300account/reorder/enable';

    const CONFIG_PATH_REORDER_SKIP_SKUS          = 'sage300account/reorder/skip_skus';

    const CONFIG_PATH_INVOICE_PAYMENT_ALLOW      = 'sage300account/invoice_payments/allow_invoice_payments';

    const CONFIG_PATH_INVOICE_PAYMENT_SKU        = 'sage300account/invoice_payments/virtual_product_sku';

    const INVOICE_PAYMENT_ORDER_STATUS           = 'invoice_payment';

    const ORDER_ITEM_INVOICE_ADDITIONAL_OPTION   = 'invoice_payment';

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * Config constructor.
     *
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig
    ) {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * Is module enabled?
     *
     * @return bool
     */
    public function isModuleEnabled()
    {
        return $this->scopeConfig->isSetFlag(self::CONFIG_PATH_ENABLED);
    }

    /**
     * Is product filtering enabled?
     *
     * @return bool
     */
    public function isProductFilteringEnabled()
    {
        return $this->scopeConfig->isSetFlag(self::CONFIG_PATH_ENABLE_PRODUCT_FILTERING);
    }

    /**
     * Should we display UOM on the frontend?
     *
     * @return bool
     */
    public function shouldDisplayUom()
    {
        return $this->scopeConfig->isSetFlag(self::CONFIG_PATH_SHOW_UOM);
    }

    /**
     * Get the default CustomerGroup for UOM
     *
     * @return string
     */
    public function getDefaultCustomerGroup()
    {
        return $this->scopeConfig->getValue(self::CONFIG_PATH_DEFAULT_UOM_CUSTOMER_GROUP);
    }

    /**
     * Should we hide the Customer Dashboard?
     *
     * @return bool
     */
    public function hideCustomerDashboard()
    {
        return $this->scopeConfig->isSetFlag(
            self::CONFIG_PATH_HIDE_CUSTOMER_DASHBOARD,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Should we show sidenav links?
     *
     * @param int|null $storeId
     *
     * @return bool
     */
    public function showSidenavLinks(int $storeId = null)
    {
        return $this->scopeConfig->isSetFlag(
            self::CONFIG_PATH_SHOW_SIDENAV_LINKS,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * Is Reorder page enabled?
     *
     * @return bool
     */
    public function isReorderEnabled()
    {
        return $this->scopeConfig->isSetFlag(self::CONFIG_PATH_REORDER_ENABLED);
    }

    /**
     * List of SKUs to skip
     *
     * @return string
     */
    public function getSkippableSkus()
    {
        return (string)$this->scopeConfig->getValue(self::CONFIG_PATH_REORDER_SKIP_SKUS);
    }

    /**
     * Are InvoicePayments enabled in admin settings?
     *
     * @return bool
     */
    public function areInvoicePaymentsAllowed()
    {
        return $this->scopeConfig->isSetFlag(
            self::CONFIG_PATH_INVOICE_PAYMENT_ALLOW,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get the InvoicePayment SKU from admin settings.
     *
     * @return string
     */
    public function getInvoicePaymentSku()
    {
        return (string)$this->scopeConfig->getValue(
            self::CONFIG_PATH_INVOICE_PAYMENT_SKU,
            ScopeInterface::SCOPE_WEBSITE
        );
    }
}
