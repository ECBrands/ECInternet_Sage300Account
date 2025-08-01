<?php
/**
 * Copyright (C) EC Brands Corporation - All Rights Reserved
 * Contact Licensing@ECInternet.com for use guidelines
 */
declare(strict_types=1);

namespace ECInternet\Sage300Account\Helper;

use Magento\Customer\Api\GroupRepositoryInterface;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use ECInternet\Sage300Account\Helper\Uom as UomHelper;
use ECInternet\Sage300Account\Model\Config;
use Exception;
use Psr\Log\LoggerInterface;

/**
 * @SuppressWarnings(PHPMD.CookieAndSessionMisuse)
 * @SuppressWarnings(PHPMD.ExcessiveParameterList)
 * @SuppressWarnings(PHPMD.LongVariable)
 */
class Data
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
     * @var \Magento\Framework\Pricing\PriceCurrencyInterface
     */
    private $priceCurrency;

    /**
     * @var \ECInternet\Sage300Account\Helper\Uom
     */
    private $uomHelper;

    /**
     * @var \ECInternet\Sage300Account\Model\Config
     */
    private $config;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    /**
     * Data constructor.
     *
     * @param \Magento\Customer\Api\GroupRepositoryInterface    $groupRepository
     * @param \Magento\Customer\Model\Session                   $customerSession
     * @param \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency
     * @param \ECInternet\Sage300Account\Helper\Uom             $uomHelper
     * @param \ECInternet\Sage300Account\Model\Config           $config
     * @param \Psr\Log\LoggerInterface                          $logger
     */
    public function __construct(
        GroupRepositoryInterface $groupRepository,
        CustomerSession $customerSession,
        PriceCurrencyInterface $priceCurrency,
        Uomhelper $uomHelper,
        Config $config,
        LoggerInterface $logger
    ) {
        $this->groupRepository = $groupRepository;
        $this->customerSession = $customerSession;
        $this->priceCurrency   = $priceCurrency;
        $this->uomHelper       = $uomHelper;
        $this->config          = $config;
        $this->logger          = $logger;
    }

    public function convertAndFormat($value, $includeInContainer = true)
    {
        return $this->priceCurrency->convertAndFormat($value, $includeInContainer);
    }

    /**
     * Get UOM display value
     *
     * @param string      $sku
     * @param string|null $pricelist
     *
     * @return string
     */
    public function getUomDisplayValue(string $sku, ?string $pricelist = null)
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
    public function getCustomerGroupCode(?int $customerGroupId = null)
    {
        $this->log('getCustomerGroupCode()', ['customerGroupId' => $customerGroupId]);

        if ($customerGroupId === null && $this->customerSession->isLoggedIn()) {
            $customerGroupId = $this->customerSession->getCustomerGroupId();
        }

        if ($customerGroupId !== null) {
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
