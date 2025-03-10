<?php
/**
 * Copyright (C) EC Brands Corporation - All Rights Reserved
 * Contact Licensing@ECInternet.com for use guidelines
 */
declare(strict_types=1);

namespace ECInternet\Sage300Account\Api\Data;

interface OeshdtInterface
{
    public const COLUMN_ID         = 'entity_id';

    public const COLUMN_UPDATED_AT = 'updated_at';

    public const COLUMN_IS_ACTIVE  = 'is_active';

    public const COLUMN_CUSTOMER   = 'CUSTOMER';

    public const COLUMN_ITEM       = 'ITEM';

    public const COLUMN_SHIPDATE   = 'SHIPDATE';

    /**
     * Get ID
     *
     * @return mixed
     */
    public function getId();

    /**
     * Set Updated At
     *
     * @param string $updatedAt
     *
     * @return void
     */
    public function setUpdatedAt(string $updatedAt);

    /**
     * Get IsActive
     *
     * @return bool
     */
    public function getIsActive();

    /**
     * Set IsActive
     *
     * @param bool $isActive
     *
     * @return void
     */
    public function setIsActive(bool $isActive);

    /**
     * Get Customer Number
     *
     * @return string
     */
    public function getCustomerNumber();

    /**
     * Set Customer Number
     *
     * @param string $customerNumber
     *
     * @return void
     */
    public function setCustomerNumber(string $customerNumber);

    /**
     * Get Item Number
     *
     * @return string
     */
    public function getItemNumber();

    /**
     * Set Item Number
     *
     * @param string $itemNumber
     *
     * @return void
     */
    public function setItemNumber(string $itemNumber);

    /**
     * Get Ship Date
     *
     * @return string
     */
    public function getShipDate();

    /**
     * Set Ship Date
     *
     * @param string $shipDate
     *
     * @return void
     */
    public function setShipDate(string $shipDate);
}
