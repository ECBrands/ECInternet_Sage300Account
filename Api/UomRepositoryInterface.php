<?php
/**
 * Copyright (C) EC Brands Corporation - All Rights Reserved
 * Contact Licensing@ECInternet.com for use guidelines
 */
declare(strict_types=1);

namespace ECInternet\Sage300Account\Api;

/**
 * @SuppressWarnings(PHPMD.ShortVariable)
 */
interface UomRepositoryInterface
{
    /**
     * Get UOM record by sku and pricelist
     *
     * @param string $sku
     * @param string $pricelist
     *
     * @return \ECInternet\Sage300Account\Api\Data\UomInterface|null
     */
    public function get(string $sku, string $pricelist);
}
