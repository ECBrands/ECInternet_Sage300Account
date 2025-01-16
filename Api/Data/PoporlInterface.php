<?php
/**
 * Copyright (C) EC Brands Corporation - All Rights Reserved
 * Contact Licensing@ECInternet.com for use guidelines
 */
declare(strict_types=1);

namespace ECInternet\Sage300Account\Api\Data;

use Magento\Framework\Api\ExtensibleDataInterface;

/**
 * @SuppressWarnings(PHPMD.LongVariable)
 */
interface PoporlInterface extends ExtensibleDataInterface
{
    const COLUMN_ID         = 'entity_id';

    const COLUMN_UPDATED_AT = 'updated_at';

    const COLUMN_IS_ACTIVE  = 'is_active';

    const COLUMN_PORHSEQ    = 'PORHSEQ';

    const COLUMN_PORLREV    = 'PORLREV';

    const COLUMN_PORLSEQ    = 'PORLSEQ';

    const COLUMN_ITEMNO     = 'ITEMNO';

    const COLUMN_OQORDERED  = 'OQORDERED';

    const COLUMN_OQRECEIVED = 'OQRECEIVED';

    const COLUMN_OQCANCELED = 'OQCANCELED';

    const COLUMN_OQOUTSTAND = 'OQOUTSTAND';

    const COLUMN_DETAILNUM  = 'DETAILNUM';

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
     * Get Purchase Order Sequence Key (PORHSEQ)
     *
     * @return float
     */
    public function getPurchaseOrderSequenceKey();

    /**
     * Set Purchase Order SequenceKey (PORHSEQ)
     *
     * @param float $purchaseOrderSequenceKey
     *
     * @return void
     */
    public function setPurchaseOrderSequenceKey(float $purchaseOrderSequenceKey);

    /**
     * Get Line Number (PORLREV)
     *
     * @return float
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
     * Get Purchase Order Line Sequence Key (PORLSEQ)
     *
     * @return float
     */
    public function getPurchaseOrderLineSequenceKey();

    /**
     * Set Purchase Order Line Sequence Key (PORLSEQ)
     *
     * @param float $purchaseOrderLineSequenceKey
     *
     * @return void
     */
    public function setPurchaseOrderLineSequenceKey(float $purchaseOrderLineSequenceKey);

    /**
     * Get Item Number (ITEMNO)
     *
     * @return string
     */
    public function getItemNumber();

    /**
     * Set Item Number (ITEMNO)
     *
     * @param string $itemNumber
     *
     * @return void
     */
    public function setItemNumber(string $itemNumber);

    /**
     * Get Quantity Ordered (OQORDERED)
     *
     * @return float
     */
    public function getQuantityOrdered();

    /**
     * Set Quantity Ordered (OQORDERED)
     *
     * @param float $quantityOrdered
     *
     * @return void
     */
    public function setQuantityOrdered(float $quantityOrdered);

    /**
     * Get Quantity Received (OQRECEIVED)
     *
     * @return float
     */
    public function getQuantityReceived();

    /**
     * Set Quantity Received (OQRECEIVED)
     *
     * @param float $quantityReceived
     *
     * @return void
     */
    public function setQuantityReceived(float $quantityReceived);

    /**
     * Get Quantity Cancelled (OQCANCELED)
     *
     * @return float
     */
    public function getQuantityCanceled();

    /**
     * Set Quantity Cancelled (OQCANCELED)
     *
     * @param float $quantityCanceled
     *
     * @return void
     */
    public function setQuantityCanceled(float $quantityCanceled);

    /**
     * Get Quantity Outstanding (OQOUTSTAND)
     *
     * @return float
     */
    public function getQuantityOutstanding();

    /**
     * Set Quantity Outstanding (OQOUTSTAND)
     *
     * @param float $quantityOustanding
     *
     * @return void
     */
    public function setQuantityOutstanding(float $quantityOustanding);

    /**
     * Get Detail Number (DETAILNUM)
     *
     * @return int
     */
    public function getDetailNumber();

    /**
     * Set Detail Number (DETAILNUM)
     *
     * @param int $detailNumber
     *
     * @return void
     */
    public function setDetailNumber(int $detailNumber);

    /**
     * Get extension attributes
     *
     * @return \ECInternet\Sage300Account\Api\Data\PoporlExtensionInterface|null
     */
    public function getExtensionAttributes();

    /**
     * Set extension attributes
     *
     * @param \ECInternet\Sage300Account\Api\Data\PoporlExtensionInterface $extensionAttributes
     *
     * @return void
     */
    public function setExtensionAttributes(PoporlExtensionInterface $extensionAttributes);
}
