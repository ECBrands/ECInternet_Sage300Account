<?php
/**
 * Copyright (C) EC Brands Corporation - All Rights Reserved
 * Contact Licensing@ECInternet.com for use guidelines
 */
declare(strict_types=1);

namespace ECInternet\Sage300Account\Api\Data;

interface OeorddInterface
{
    const COLUMN_ID         = 'entity_id';

    const COLUMN_UPDATED_AT = 'updated_at';

    const COLUMN_ORDUNIQ    = 'ORDUNIQ';

    const COLUMN_LINENUM    = 'LINENUM';

    const COLUMN_LINETYPE   = 'LINETYPE';

    const COLUMN_ITEM       = 'ITEM';

    const COLUMN_DESC       = 'DESC';

    const COLUMN_QTYORDERED = 'QTYORDERED';

    const COLUMN_QTYSHIPPED = 'QTYSHIPPED';

    const COLUMN_QTYBACKORD = 'QTYBACKORD';

    const COLUMN_ORDUNIT    = 'ORDUNIT';

    const COLUMN_UNITPRICE  = 'UNITPRICE';

    const COLUMN_EXTINVMISC = 'EXTINVMISC';

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
     * Get Order Uniquifier - ORDUNIQ
     *
     * @return int
     */
    public function getOrderUniquifier();

    /**
     * Set Order Uniquifier - ORDUNIQ
     *
     * @param int $orderUniquifier
     *
     * @return void
     */
    public function setOrderUniquifier(int $orderUniquifier);

    /**
     * Get Line Number - LINENUM
     *
     * @return int
     */
    public function getLineNumber();

    /**
     * Set Line Number - LINENUM
     *
     * @param int $lineNumber
     *
     * @return void
     */
    public function setLineNumber(int $lineNumber);

    /**
     * Get Line Type - LINETYPE
     *
     * @return int
     */
    public function getLineType();

    /**
     * Set Line Type - LINETYPE
     *
     * @param int $lineType
     *
     * @return void
     */
    public function setLineType(int $lineType);

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
     * Get Quantity Ordered - QTYORDERED
     *
     * @return float
     */
    public function getQuantityOrdered();

    /**
     * Set Quantity Ordered - QTYORDERED
     *
     * @param float $quantityOrdered
     *
     * @return void
     */
    public function setQuantityOrdered(float $quantityOrdered);

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
     * Get Order Unit of Measure - ORDUNIT
     *
     * @return string
     */
    public function getOrderUnitOfMeasure();

    /**
     * Set Order Unit of Measure - ORDUNIT
     *
     * @param string $orderUnitOfMeasure
     *
     * @return void
     */
    public function setOrderUnitOfMeasure(string $orderUnitOfMeasure);

    /**
     * Get Order Unit Price - UNITPRICE
     *
     * @return float
     */
    public function getOrderUnitPrice();

    /**
     * Set Order Unit Price - UNITPRICE
     *
     * @param float $orderUnitPrice
     *
     * @return void
     */
    public function setOrderUnitPrice(float $orderUnitPrice);

    /**
     * Get Extended Amount - EXTINVMISC
     *
     * @return float
     */
    public function getExtendedAmount();

    /**
     * Set Extended Amount - EXTINVMISC
     *
     * @param float $extendedAmount
     *
     * @return void
     */
    public function setExtendedAmount(float $extendedAmount);
}
