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
interface PoporloInterface
{
    public const COLUMN_ID         = 'entity_id';

    public const COLUMN_STORE_ID   = 'store_id';

    public const COLUMN_CREATED_AT = 'created_at';

    public const COLUMN_UPDATED_AT = 'updated_at';

    public const COLUMN_PORHSEQ    = 'PORHSEQ';

    public const COLUMN_PORLREV    = 'PORLREV';

    public const COLUMN_OPTFIELD   = 'OPTFIELD';

    public const COLUMN_VALUE      = 'VALUE';

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
     * Get Line Number (PORLREV)
     *
     * @return int
     */
    public function getLineNumber();

    /**
     * Set Line Number (PORLREV)
     *
     * @param float $lineNumber
     *
     * @return void
     */
    public function setLineNumber(float $lineNumber);

    /**
     * Get Optional Field (OPTFIELD)
     *
     * @return string
     */
    public function getOptionalField();

    /**
     * Set Optional Field (OPTFIELD)
     *
     * @param string $optionalField
     *
     * @return void
     */
    public function setOptionalField(string $optionalField);

    /**
     * Get value
     *
     * @return string
     */
    public function getValue();

    /**
     * Set value
     *
     * @param string $value
     *
     * @return void
     */
    public function setValue(string $value);
}
