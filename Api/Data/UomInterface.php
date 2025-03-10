<?php
/**
 * Copyright (C) EC Brands Corporation - All Rights Reserved
 * Contact Licensing@ECInternet.com for use guidelines
 */
declare(strict_types=1);

namespace ECInternet\Sage300Account\Api\Data;

interface UomInterface
{
    public const COLUMN_ID         = 'entity_id';

    public const COLUMN_UPDATED_AT = 'updated_at';

    public const COLUMN_ITEMNO     = 'ITEMNO';

    public const COLUMN_PRICELIST  = 'PRICELIST';

    public const COLUMN_UNIT       = 'UNIT';

    public const COLUMN_DEFCONV    = 'DEFCONV';

    /**
     * Get ID
     *
     * @return mixed
     */
    public function getId();

    /**
     * Get Unit of Measure
     *
     * @return string
     */
    public function getUnit();

    /**
     * Get Conversion Factor
     *
     * @return float
     */
    public function getConversionFactor();
}
