<?php
/**
 * Copyright (C) EC Brands Corporation - All Rights Reserved
 * Contact Licensing@ECInternet.com for use guidelines
 */
declare(strict_types=1);

namespace ECInternet\Sage300Account\Api\Data;

interface OeinvdInterface
{
    public const COLUMN_ID         = 'entity_id';

    public const COLUMN_UPDATED_AT = 'updated_at';

    public const COLUMN_INVUNIQ    = 'INVUNIQ';

    public const COLUMN_LINENUM    = 'LINENUM';

    public const COLUMN_ITEM       = 'ITEM';

    public const COLUMN_DESC       = 'DESC';

    public const COLUMN_QTYORDERED = 'QTYORDERED';

    public const COLUMN_QTYSHIPPED = 'QTYSHIPPED';

    public const COLUMN_QTYBACKORD = 'QTYBACKORD';

    public const COLUMN_INVUNIT    = 'INVUNIT';

    public const COLUMN_UNITPRICE  = 'UNITPRICE';

    public const COLUMN_EXTINVMISC = 'EXTINVMISC';

    public const COLUMN_INVDISC    = 'INVDISC';

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
     * @return int
     */
    public function getInvoiceUniquifier();

    /**
     * @param int $invoiceUniquifier
     *
     * @return void
     */
    public function setInvoiceUniquifier(int $invoiceUniquifier);

    /**
     * @return int
     */
    public function getLineUniquifier();

    /**
     * @param int $lineUniquifier
     *
     * @return void
     */
    public function setLineUniquifier(int $lineUniquifier);

    /**
     * Get Item - ITEM
     *
     * @return string
     */
    public function getItem();

    /**
     * Set Item - ITEM
     *
     * @param string $item
     *
     * @return void
     */
    public function setItem(string $item);

    /**
     * Get Description - DESC
     *
     * @return string
     */
    public function getDescription();

    /**
     * Set Description - DESC
     *
     * @param string $description
     *
     * @return void
     */
    public function setDescription(string $description);

    /**
     * Get Current Quantity Outstanding - QTYORDERED
     *
     * @return float
     */
    public function getCurrentQuantityOutstanding();

    /**
     * Set Current Quantity Outstanding- QTYORDERED
     *
     * @param float $currentQuantityOutstanding
     *
     * @return void
     */
    public function setCurrentQuantityOutstanding(float $currentQuantityOutstanding);

    /**
     * Get Quantity Shipped - QTYSHIPPED
     *
     * @return float
     */
    public function getQuantityShipped();

    /**
     * Set Quantity Shipped - QTYSHIPPED
     *
     * @param float $quantityShipped
     *
     * @return void
     */
    public function setQuantityShipped(float $quantityShipped);

    /**
     * Get Quantity Backordered - QTYBACKORD
     *
     * @return float
     */
    public function getQuantityBackordered();

    /**
     * Set Quantity Backordered - QTYBACKORD
     *
     * @param float $quantityBackordered
     *
     * @return void
     */
    public function setQuantityBackordered(float $quantityBackordered);

    /**
     * Get Invoice Unit of Measure - INVUNIT
     *
     * @return string
     */
    public function getInvoiceUnitOfMeasure();

    /**
     * Set Invoice Unit of Measure - INVUNIT
     *
     * @param string $invoiceUnitOfMeasure
     *
     * @return void
     */
    public function setInvoiceUnitOfMeasure(string $invoiceUnitOfMeasure);

    /**
     * Get Unit Price - UNITPRICE
     *
     * @return float
     */
    public function getUnitPrice();

    /**
     * Set Unit Price - UNITPRICE
     *
     * @param float $unitPrice
     *
     * @return void
     */
    public function setUnitPrice(float $unitPrice);

    /**
     * Get Extended Shipped Price Amount
     *
     * @return float
     */
    public function getExtendedShippedPriceAmount();

    /**
     * Set Extended Shipped Price Amount
     *
     * @param float $extendedShippedPriceAmount
     *
     * @return void
     */
    public function setExtendedShippedPriceAmount(float $extendedShippedPriceAmount);

    /**
     * Get Invoice Discount Amount - INVDISC
     *
     * @return float
     */
    public function getInvoiceDiscountAmount();

    /**
     * Set Invoice Discount Amount - INVDISC
     *
     * @param float $invoiceDiscountAmount
     *
     * @return void
     */
    public function setInvoiceDiscountAmount(float $invoiceDiscountAmount);

    /**
     * Get Extended Price
     *
     * @return float
     */
    public function getExtendedPrice();

    /**
     * Set Extended Price
     *
     * @param float $extendedPrice
     *
     * @return void
     */
    public function setExtendedPrice(float $extendedPrice);
}
