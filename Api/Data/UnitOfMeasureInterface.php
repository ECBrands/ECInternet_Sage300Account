<?php
/**
 * Copyright (C) EC Brands Corporation - All Rights Reserved
 * Contact Licensing@ECInternet.com for use guidelines
 */
declare(strict_types=1);

namespace ECInternet\Sage300Account\Api\Data;

interface UnitOfMeasureInterface
{
    /**
     * @return float
     */
    public function getConversionFactor();

    /**
     * @return string
     */
    public function getKey();

    /**
     * @return string
     */
    public function getDisplayText();
}
