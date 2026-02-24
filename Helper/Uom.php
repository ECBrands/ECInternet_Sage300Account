<?php
/**
 * Copyright (C) EC Brands Corporation - All Rights Reserved
 * Contact Licensing@ECInternet.com for use guidelines
 */
declare(strict_types=1);

namespace ECInternet\Sage300Account\Helper;

use ECInternet\Sage300Account\Api\UomRepositoryInterface;
use Psr\Log\LoggerInterface as Logger;

class Uom
{
    private const SINGLE_ITEM_DEFAULT_TEXT = 'Each';

    /**
     * @var \ECInternet\Sage300Account\Api\UomRepositoryInterface
     */
    private $uomRepository;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    /**
     * Uom constructor.
     *
     * @param \ECInternet\Sage300Account\Api\UomRepositoryInterface $uomRepository
     * @param \Psr\Log\LoggerInterface                              $logger
     */
    public function __construct(
        UomRepositoryInterface $uomRepository,
        Logger $logger,
    ) {
        $this->uomRepository = $uomRepository;
        $this->logger        = $logger;
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
     * @param string      $sku
     * @param string|null $pricelist
     *
     * @return string
     */
    public function getUomDisplayValue(string $sku, ?string $pricelist)
    {
        $this->log('getUomDisplayValue()', ['sku' => $sku, 'pricelist' => $pricelist]);

        $uomConversionFactor = $this->getUomConversionFactor($sku, $pricelist);
        $this->log('getUomDisplayValue()', ['uomConversionFactory' => $uomConversionFactor]);

        if ($uomConversionFactor === null) {
            return self::SINGLE_ITEM_DEFAULT_TEXT;
        }

        if ($uomConversionFactor === 1.0) {
            return self::SINGLE_ITEM_DEFAULT_TEXT;
        }

        return "Set of $uomConversionFactor";
    }

    /**
     * @param string $sku
     * @param string $pricelist
     *
     * @return \ECInternet\Sage300Account\Api\Data\UomInterface|null
     */
    private function getUomRecord(string $sku, string $pricelist)
    {
        return $this->uomRepository->get($sku, $pricelist);
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
