<?php
/**
 * Copyright (C) EC Brands Corporation - All Rights Reserved
 * Contact Licensing@ECInternet.com for use guidelines
 */
declare(strict_types=1);

namespace ECInternet\Sage300Account\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use ECInternet\Sage300Account\Logger\Logger;
use ECInternet\Sage300Account\Model\ResourceModel\Uom\CollectionFactory as UomCollectionFactory;

class Uom extends AbstractHelper
{
    /**
     * @var \ECInternet\Sage300Account\Logger\Logger
     */
    private $logger;

    /**
     * @var \ECInternet\Sage300Account\Model\ResourceModel\Uom\CollectionFactory
     */
    private $uomCollectionFactory;

    /**
     * @param \Magento\Framework\App\Helper\Context                                $context
     * @param \ECInternet\Sage300Account\Logger\Logger                             $logger
     * @param \ECInternet\Sage300Account\Model\ResourceModel\Uom\CollectionFactory $uomCollectionFactory
     */
    public function __construct(
        Context $context,
        Logger $logger,
        UomCollectionFactory $uomCollectionFactory
    ) {
        parent::__construct($context);

        $this->logger               = $logger;
        $this->uomCollectionFactory = $uomCollectionFactory;
    }

    /**
     * Translate uom to friendly value (if possible)
     *
     * @param string $uom
     *
     * @return string
     */
    public function translateUom(string $uom)
    {
        // See if we can pull this from Sage table.
        return $uom;
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

        if ($uom = $this->getUomRecord($sku, $pricelist)) {
            return $uom->getConversionFactor();
        }

        return null;
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
        $this->log('getUomText()', ['sku' => $sku, 'pricelist' => $pricelist]);

        if ($uom = $this->getUomRecord($sku, $pricelist)) {
            return $uom->getUnit();
        }

        return null;
    }

    /**
     * Get UOM display value
     *
     * @param string $sku
     * @param string $pricelist
     *
     * @return string
     */
    public function getUomDisplayValue(string $sku, string $pricelist)
    {
        $this->log('getUomDisplayValue()', ['sku' => $sku, 'pricelist' => $pricelist]);

        $uomConversionFactor = $this->getUomConversionFactor($sku, $pricelist);
        $this->log('getUomDisplayValue()', ['uomConversionFactory' => $uomConversionFactor]);

        if ($uomConversionFactor !== null) {
            if ($uomConversionFactor === 1.0) {
                return 'Each';
            } else {
                return "Set of $uomConversionFactor";
            }
        } else {
            $this->log('getUomDisplayValue() - Failed to lookup UOM value.', [
                'sku'       => $sku,
                'pricelist' => $pricelist
            ]);

            return 'Each';
        }
    }

    /**
     * @param string $sku
     * @param string $pricelist
     *
     * @return \ECInternet\Sage300Account\Model\Data\Uom|null
     */
    private function getUomRecord(string $sku, string $pricelist)
    {
        /** @var \ECInternet\Sage300Account\Model\ResourceModel\Uom\Collection $uomCollection */
        $uomCollection = $this->uomCollectionFactory->create()
            ->addFieldToFilter(\ECInternet\Sage300Account\Model\Data\Uom::COLUMN_ITEMNO, ['eq' => $sku])
            ->addFieldToFilter(\ECInternet\Sage300Account\Model\Data\Uom::COLUMN_PRICELIST, ['eq' => $pricelist])
            ->addFieldToSelect(\ECInternet\Sage300Account\Model\Data\Uom::COLUMN_UNIT);

        if ($uom = $uomCollection->getFirstItem()) {
            /** @var \ECInternet\Sage300Account\Model\Data\Uom $uom */
            return $uom;
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
        $this->logger->info('Helper/Uom - ' . $message, $extra);
    }
}
