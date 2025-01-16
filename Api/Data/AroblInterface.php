<?php
/**
 * Copyright (C) EC Brands Corporation - All Rights Reserved
 * Contact Licensing@ECInternet.com for use guidelines
 */
declare(strict_types=1);

namespace ECInternet\Sage300Account\Api\Data;

interface AroblInterface
{
    const COLUMN_ID         = 'entity_id';

    const COLUMN_UPDATED_AT = 'updated_at';

    const COLUMN_IDCUST     = 'IDCUST';

    const COLUMN_IDINVC     = 'IDINVC';

    const COLUMN_SWPAID     = 'SWPAID';

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
     * Get Customer Number - IDCUST
     *
     * @return string
     */
    public function getCustomerNumber();

    /**
     * Set Customer Number - IDCUST
     *
     * @param string $customerNumber
     *
     * @return void
     */
    public function setCustomerNumber(string $customerNumber);

    /**
     * Get Document Number - IDINVC
     *
     * @return string
     */
    public function getDocumentNumber();

    /**
     * Set Document Number - IDINVC
     *
     * @param string $documentNumber
     *
     * @return void
     */
    public function setDocumentNumber(string $documentNumber);

    /**
     * Get Fully Paid - SWPAID
     *
     * @return int
     */
    public function getFullyPaid();

    /**
     * Set Fully Paid - SWPAID
     *
     * @param int $fullyPaid
     *
     * @return void
     */
    public function setFullyPaid(int $fullyPaid);
}
