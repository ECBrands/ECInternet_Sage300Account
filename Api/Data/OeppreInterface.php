<?php
/**
 * Copyright (C) EC Brands Corporation - All Rights Reserved
 * Contact Licensing@ECInternet.com for use guidelines
 */
declare(strict_types=1);

namespace ECInternet\Sage300Account\Api\Data;

interface OeppreInterface
{
    const COLUMN_ID         = 'entity_id';

    const COLUMN_UPDATED_AT = 'updated_at';

    const COLUMN_APPLYTO    = 'APPLYTO';

    const COLUMN_DOCNUMBER  = 'DOCNUMBER';

    const COLUMN_PPNUMBER   = 'PPNUMBER';

    const COLUMN_PAYMENT    = 'PAYMENT';

    const COLUMN_INVPAYDISC = 'INVPAYDISC';

    const APPLY_TO_INVOICE  = 2;

    const APPLY_TO_ORDER    = 4;

    const APPLY_TO_SHIPMENT = 9;

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
     * Get Apply To - APPLYTO
     *
     * @return int
     */
    public function getApplyTo();

    /**
     * Set Apply To - APPLYTO
     *
     * @param int $applyTo
     *
     * @return void
     */
    public function setApplyTo(int $applyTo);

    /**
     * Get Document Number - DOCNUMBER
     *
     * @return string
     */
    public function getDocumentNumber();

    /**
     * Set Document Number - DOCNUMBER
     *
     * @param string $documentNumber
     *
     * @return void
     */
    public function setDocumentNumber(string $documentNumber);

    /**
     * Get Prepayment Number - PPNUMBER
     *
     * @return int
     */
    public function getPrepaymentNumber();

    /**
     * Set Prepayment Number - PPNUMBER
     *
     * @param int $prepaymentNumber
     *
     * @return void
     */
    public function setPrepaymentNumber(int $prepaymentNumber);

    /**
     * Get Payment In Customer Currency - PAYMENT
     *
     * @return float
     */
    public function getPaymentInCustomerCurrency();

    /**
     * Set Payment In Customer Currency - PAYMENT
     *
     * @param float $paymentInCustomerCurrency
     *
     * @return void
     */
    public function setPaymentInCustomerCurrency(float $paymentInCustomerCurrency);

    /**
     * Get Payment Discount - INVPAYDISC
     *
     * @return float
     */
    public function getPaymentDiscount();

    /**
     * Set Payment Discount - INVPAYDISC
     *
     * @param float $paymentDiscount
     *
     * @return void
     */
    public function setPaymentDiscount(float $paymentDiscount);
}
