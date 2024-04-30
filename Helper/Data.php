<?php
/**
 * Copyright (C) EC Brands Corporation - All Rights Reserved
 * Contact Licensing@ECInternet.com for use guidelines
 */
declare(strict_types=1);

namespace ECInternet\Sage300Account\Helper;

use Magento\Customer\Api\GroupRepositoryInterface;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\ScopeInterface;
use ECInternet\Sage300Account\Helper\Uom as UomHelper;
use ECInternet\Sage300Account\Logger\Logger;
use Exception;

/**
 * Helper
 *
 * @SuppressWarnings(PHPMD.CookieAndSessionMisuse)
 * @SuppressWarnings(PHPMD.ExcessiveParameterList)
 * @SuppressWarnings(PHPMD.LongVariable)
 */
class Data extends AbstractHelper
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

    const INVOICE_PAYMENT_COMPLETE_ORDER_STATUS  = 'invoice_payment_complete';

    const ORDER_ITEM_INVOICE_ADDITIONAL_OPTION   = 'invoice_payment';

    /**
     * @var \Magento\Customer\Api\GroupRepositoryInterface
     */
    private $groupRepository;

    /**
     * @var \Magento\Customer\Model\Session
     */
    private $customerSession;

    /**
     * @var \ECInternet\Sage300Account\Helper\Uom
     */
    private $uomHelper;

    /**
     * @var \ECInternet\Sage300Account\Logger\Logger
     */
    private $logger;

    /**
     * Data constructor.
     *
     * @param \Magento\Framework\App\Helper\Context          $context
     * @param \Magento\Customer\Api\GroupRepositoryInterface $groupRepository
     * @param \Magento\Customer\Model\Session                $customerSession
     * @param \ECInternet\Sage300Account\Helper\Uom          $uomHelper
     * @param \ECInternet\Sage300Account\Logger\Logger       $logger
     */
    public function __construct(
        Context $context,
        GroupRepositoryInterface $groupRepository,
        CustomerSession $customerSession,
        Uomhelper $uomHelper,
        Logger $logger
    ) {
        parent::__construct($context);

        $this->groupRepository = $groupRepository;
        $this->customerSession = $customerSession;
        $this->uomHelper       = $uomHelper;
        $this->logger          = $logger;
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
        return $this->scopeConfig->isSetFlag(self::CONFIG_PATH_HIDE_CUSTOMER_DASHBOARD, ScopeInterface::SCOPE_STORE);
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

    /**
     * Get UOM display text
     *
     * @param string $sku
     * @param string $pricelist
     *
     * @return string|null
     */
    public function getUomText(string $sku, string $pricelist)
    {
        //$this->log('getUomText()', ['sku' => $sku, 'pricelist' => $pricelist]);

        return $this->uomHelper->getUomText($sku, $pricelist);
    }

    /**
     * Get UOM display value
     *
     * @param string      $sku
     * @param string|null $pricelist
     *
     * @return string
     */
    public function getUomDisplayValue(string $sku, string $pricelist = null)
    {
        $this->log('getUomDisplayValue()', ['sku' => $sku, 'pricelist' => $pricelist]);

        if (empty($pricelist)) {
            try {
                $pricelist = $this->getCustomerGroupCode();
            } catch (Exception $e) {
                $this->log('getUomDisplayValue()', ['exception' => $e->getMessage()]);
            }
        }

        if ($pricelist !== null) {
            return $this->uomHelper->getUomDisplayValue($sku, $pricelist);
        }

        return 'Each';
    }

    public function translateUom($uom)
    {
        // See if we can pull this from Sage table.
        return $uom;
    }

    /**
     * Get CustomerGroup code for current Customer
     *
     * @param int|null $customerGroupId
     *
     * @return string|null
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getCustomerGroupCode(int $customerGroupId = null)
    {
        $this->log('getCustomerGroupCode()', ['customerGroupId' => $customerGroupId]);

        if ($customerGroupId === null && $this->customerSession->isLoggedIn()) {
            $customerGroupId = $this->customerSession->getCustomerGroupId();
        }

        if (!empty($customerGroupId)) {
            $customerGroup = $this->getCustomerGroup($customerGroupId);
            if ($customerGroup) {
                return $customerGroup->getCode();
            }
        } else {
            return $this->getDefaultCustomerGroup();
        }

        return null;
    }

    /**
     * Get the UOM value based on SKU and Pricelist
     *
     * @param string $sku
     * @param string $pricelist
     *
     * @return float|null
     */
    public function getUomConversionFactor(string $sku, string $pricelist)
    {
        $this->log('getUomConversionFactor()', ['sku' => $sku, 'pricelist' => $pricelist]);

        return $this->uomHelper->getUomConversionFactor($sku, $pricelist);
    }

    private function getCustomerGroup(int $customerGroupId)
    {
        try {
            return $this->groupRepository->getById($customerGroupId);
        } catch (Exception $e) {
            $this->log('getCustomerGroup()', [
                'customerGroupId' => $customerGroupId,
                'exception'       => $e->getMessage()
            ]);
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
        $this->logger->info('Helper/Data - ' . $message, $extra);
    }
}
