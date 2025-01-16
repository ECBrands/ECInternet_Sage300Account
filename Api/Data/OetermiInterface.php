<?php
/**
 * Copyright (C) EC Brands Corporation - All Rights Reserved
 * Contact Licensing@ECInternet.com for use guidelines
 */
declare(strict_types=1);

namespace ECInternet\Sage300Account\Api\Data;

interface OetermiInterface
{
    const COLUMN_ID         = 'entity_id';

    const COLUMN_UPDATED_AT = 'updated_at';

    const COLUMN_INVUNIQ    = 'INVUNIQ';

    const COLUMN_PAYMENT    = 'PAYMENT';

    const COLUMN_DISCDATE   = 'DISCDATE';

    const COLUMN_DISCAMT    = 'DISCAMT';

    const COLUMN_DUEDATE    = 'DUEDATE';

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
     * Get Invoice Uniquifier - INVUNIQ
     *
     * @return int
     */
    public function getInvoiceUniquifier();

    /**
     * Set Invoice Uniquifier - INVUNIQ
     *
     * @param int $invoiceUniquifier
     *
     * @return void
     */
    public function setInvoiceUniquifier(int $invoiceUniquifier);

    /**
     * Get Payment Number - PAYMENT
     *
     * @return int
     */
    public function getPaymentNumber();

    /**
     * Set Payment Number - PAYMENT
     *
     * @param int $paymentNumber
     *
     * @return void
     */
    public function setPaymentNumber(int $paymentNumber);

    /**
     * Get Discount Date - DISCDATE
     *
     * @return int
     */
    public function getDiscountDate();

    /**
     * Set Discount Date - DISCDATE
     *
     * @param int $discountDate
     *
     * @return void
     */
    public function setDiscountDate(int $discountDate);

    /**
     * Get Discount Amount - DISCAMT
     *
     * @return float
     */
    public function getDiscountAmount();

    /**
     * Set Discount Amount - DISCAMT
     *
     * @param float $discountAmount
     *
     * @return void
     */
    public function setDiscountAmount(float $discountAmount);

    /**
     * Get Due Date (YYYYMMDD) - DUEDATE
     *
     * @return int
     */
    public function getDueDate();

    /**
     * Set Due Date (YYYYMMDD) - DUEDATE
     *
     * @param int $dueDate
     *
     * @return void
     */
    public function setDueDate(int $dueDate);
}
