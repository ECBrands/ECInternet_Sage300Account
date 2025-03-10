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
use ECInternet\Sage300Account\Helper\Uom as UomHelper;
use ECInternet\Sage300Account\Logger\Logger;
use ECInternet\Sage300Account\Model\Config;
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
     * @var \ECInternet\Sage300Account\Model\Config
     */
    private $config;

    /**
     * Data constructor.
     *
     * @param \Magento\Framework\App\Helper\Context          $context
     * @param \Magento\Customer\Api\GroupRepositoryInterface $groupRepository
     * @param \Magento\Customer\Model\Session                $customerSession
     * @param \ECInternet\Sage300Account\Helper\Uom          $uomHelper
     * @param \ECInternet\Sage300Account\Logger\Logger       $logger
     * @param \ECInternet\Sage300Account\Model\Config        $config
     */
    public function __construct(
        Context $context,
        GroupRepositoryInterface $groupRepository,
        CustomerSession $customerSession,
        Uomhelper $uomHelper,
        Logger $logger,
        Config $config
    ) {
        parent::__construct($context);

        $this->groupRepository = $groupRepository;
        $this->customerSession = $customerSession;
        $this->uomHelper       = $uomHelper;
        $this->logger          = $logger;
        $this->config          = $config;
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
            if ($customerGroup = $this->getCustomerGroup($customerGroupId)) {
                return $customerGroup->getCode();
            }
        } else {
            return $this->config->getDefaultCustomerGroup();
        }

        return null;
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
