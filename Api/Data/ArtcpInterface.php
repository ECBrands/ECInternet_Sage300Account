<?php
/**
 * Copyright (C) EC Brands Corporation - All Rights Reserved
 * Contact Licensing@ECInternet.com for use guidelines
 */
declare(strict_types=1);

namespace ECInternet\Sage300Account\Api\Data;

interface ArtcpInterface
{
    const COLUMN_ID         = 'entity_id';

    const COLUMN_UPDATED_AT = 'updated_at';

    const COLUMN_CODEPAYM   = 'CODEPAYM';

    const COLUMN_CNTBTCH    = 'CNTBTCH';

    const COLUMN_CNTITEM    = 'CNTITEM';

    const COLUMN_CNTLINE    = 'CNTLINE';

    const COLUMN_IDINVC     = 'IDINVC';

    const COLUMN_AMTPAYM    = 'AMTPAYM';

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
     * Get Batch Type - CODEPAYM
     *
     * @return string
     */
    public function getBatchType();

    /**
     * Set Batch Type - CODEPAYM
     *
     * @param string $batchType
     *
     * @return void
     */
    public function setBatchType(string $batchType);

    /**
     * Get Batch Number - CNTBTCH
     *
     * @return int
     */
    public function getBatchNumber();

    /**
     * Set Batch Number - CNTBTCH
     *
     * @param int $batchNumber
     *
     * @return void
     */
    public function setBatchNumber(int $batchNumber);

    /**
     * Get Entry Number - CNTITEM
     *
     * @return int
     */
    public function getEntryNumber();

    /**
     * Set Entry Number - CNTITEM
     *
     * @param int $entryNumber
     *
     * @return void
     */
    public function setEntryNumber(int $entryNumber);

    /**
     * Get Line Number - CNTLINE
     *
     * @return int
     */
    public function getLineNumber();

    /**
     * Set Line Number - CNTLINE
     *
     * @param int $lineNumber
     *
     * @return void
     */
    public function setLineNumber(int $lineNumber);

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
     * Get Customer Receipt Amount - AMTPAYM
     *
     * @return float
     */
    public function getCustomerReceiptAmount();

    /**
     * Set Customer Receipt Amount - AMTPAYM
     *
     * @param float $customerReceiptAmount
     *
     * @return void
     */
    public function setCustomerReceiptAmount(float $customerReceiptAmount);
}
