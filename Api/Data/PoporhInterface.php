<?php
/**
 * Copyright (C) EC Brands Corporation - All Rights Reserved
 * Contact Licensing@ECInternet.com for use guidelines
 */
declare(strict_types=1);

namespace ECInternet\Sage300Account\Api\Data;

/**
 * @SuppressWarnings(PHPMD.LongVariable)
 */
interface PoporhInterface
{
    const COLUMN_ID         = 'entity_id';

    const COLUMN_UPDATED_AT = 'updated_at';

    const COLUMN_PORHSEQ    = 'PORHSEQ';

    const COLUMN_PONUMBER   = 'PONUMBER';

    const COLUMN_VDCODE     = 'VDCODE';

    const COLUMN_VDNAME     = 'VDNAME';

    const COLUMN_EXPARRIVAL = 'EXPARRIVAL';

    const COLUMN_VIACODE    = 'VIACODE';

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
     * Get Purchase Order Sequence Key (PORHSEQ)
     *
     * @return float
     */
    public function getPurchaseOrderSequenceKey();

    /**
     * Set Purchase Order Sequence Key (PORHSEQ)
     *
     * @param float $purchaseOrderSequenceKey
     *
     * @return void
     */
    public function setPurchaseOrderSequenceKey(float $purchaseOrderSequenceKey);

    /**
     * Get Purchase Order Number (PONUMBER)
     *
     * @return string
     */
    public function getPurchaseOrderNumber();

    /**
     * Set Purchase Order Number (PONUMBER)
     *
     * @param string $purchaseOrderNumber
     *
     * @return void
     */
    public function setPurchaseOrderNumber(string $purchaseOrderNumber);

    /**
     * Get Vendor Code (VDCODE)
     *
     * @return string
     */
    public function getVendorCode();

    /**
     * Set Vendor Code (VDCODE)
     *
     * @param string $vendorCode
     *
     * @return void
     */
    public function setVendorCode(string $vendorCode);

    /**
     * Get Vendor Name
     *
     * @return string
     */
    public function getVendorName();

    /**
     * Set Vendor Name
     *
     * @param string $vendorName
     *
     * @return void
     */
    public function setVendorName(string $vendorName);

    /**
     * Get Expected Arrival Date
     *
     * @return float
     */
    public function getExpectedArrivalDate();

    /**
     * Set Expected Arrival Date
     *
     * @param float $expectedArrivalDate
     *
     * @return void
     */
    public function setExpectedArrivalDate(float $expectedArrivalDate);

    /**
     * Get Ship Via Code (VIACODE)
     *
     * @return string
     */
    public function getShipViaCode();

    /**
     * Set Ship Via Code (VIACODE)
     *
     * @param string $shipViaCode
     *
     * @return void
     */
    public function setShipViaCode(string $shipViaCode);
}
