<?php
/**
 * Copyright (C) EC Brands Corporation - All Rights Reserved
 * Contact Licensing@ECInternet.com for use guidelines
 */
declare(strict_types=1);

namespace ECInternet\Sage300Account\Model\Data;

use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\Context;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Registry;
use Magento\Framework\Stdlib\DateTime;
use ECInternet\Sage300Account\Api\Data\OeinvdInterface;

/**
 * Oeinvd model
 */
class Oeinvd extends AbstractModel implements IdentityInterface, OeinvdInterface
{
    const CACHE_TAG = 'ecinternet_sage300account_oeinvd';

    protected $_cacheTag    = 'ecinternet_sage300account_oeinvd';

    protected $_eventPrefix = 'ecinternet_sage300account_oeinvd';

    protected $_eventObject = 'oeinvd';

    /**
     * @var \Magento\Framework\Stdlib\DateTime
     */
    private $_dateTime;

    /**
     * Oeinvd constructor.
     *
     * @param \Magento\Framework\Model\Context                             $context
     * @param \Magento\Framework\Registry                                  $registry
     * @param \Magento\Framework\Stdlib\DateTime                           $dateTime
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource|null $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb|null           $resourceCollection
     * @param array                                                        $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        DateTime $dateTime,
        AbstractResource $resource = null,
        AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        $this->_dateTime = $dateTime;

        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    protected function _construct()
    {
        $this->_init('ECInternet\Sage300Account\Model\ResourceModel\Oeinvd');
    }

    public function beforeSave()
    {
        // Always update (we can use this to verify syncs are running)
        $this->setUpdatedAt($this->_dateTime->formatDate(true));

        return parent::beforeSave();
    }

    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    public function getId()
    {
        return $this->getData(self::COLUMN_ID);
    }

    public function setUpdatedAt(string $updatedAt)
    {
        $this->setData(self::COLUMN_UPDATED_AT, $updatedAt);
    }

    public function getInvoiceUniquifier()
    {
        return $this->getData(self::COLUMN_INVUNIQ);
    }

    public function setInvoiceUniquifier(int $invoiceUniquifier)
    {
        $this->setData(self::COLUMN_INVUNIQ, $invoiceUniquifier);
    }

    public function getLineUniquifier()
    {
        return $this->getData(self::COLUMN_LINENUM);
    }

    public function setLineUniquifier(int $lineUniquifier)
    {
        $this->setData(self::COLUMN_LINENUM, $lineUniquifier);
    }

    public function getItem()
    {
        return (string)$this->getData(self::COLUMN_ITEM);
    }

    public function setItem(string $item)
    {
        $this->setData(self::COLUMN_ITEM, $item);
    }

    public function getDescription()
    {
        return (string)$this->getData(self::COLUMN_DESC);
    }

    public function setDescription(string $description)
    {
        $this->setData(self::COLUMN_DESC, $description);
    }

    public function getCurrentQuantityOutstanding()
    {
        return (float)$this->getData(self::COLUMN_QTYORDERED);
    }

    public function setCurrentQuantityOutstanding(float $currentQuantityOutstanding)
    {
        $this->setData(self::COLUMN_QTYORDERED, $currentQuantityOutstanding);
    }

    public function getQuantityShipped()
    {
        return (float)$this->getData(self::COLUMN_QTYSHIPPED);
    }

    public function setQuantityShipped(float $quantityShipped)
    {
        $this->setData(self::COLUMN_QTYSHIPPED, $quantityShipped);
    }

    public function getQuantityBackordered()
    {
        return (float)$this->getData(self::COLUMN_QTYBACKORD);
    }

    public function setQuantityBackordered(float $quantityBackordered)
    {
        $this->setData(self::COLUMN_QTYBACKORD, $quantityBackordered);
    }

    public function getInvoiceUnitOfMeasure()
    {
        return (string)$this->getData(self::COLUMN_INVUNIT);
    }

    public function setInvoiceUnitOfMeasure(string $invoiceUnitOfMeasure)
    {
        $this->setData(self::COLUMN_INVUNIT, $invoiceUnitOfMeasure);
    }

    public function getUnitPrice()
    {
        return (float)$this->getData(self::COLUMN_UNITPRICE);
    }

    public function setUnitPrice(float $unitPrice)
    {
        $this->setData(self::COLUMN_UNITPRICE, $unitPrice);
    }

    public function getExtendedShippedPriceAmount()
    {
        return (float)$this->getData(self::COLUMN_EXTINVMISC);
    }

    public function setExtendedShippedPriceAmount(float $extendedShippedPriceAmount)
    {
        $this->setData(self::COLUMN_EXTINVMISC, $extendedShippedPriceAmount);
    }

    public function getInvoiceDiscountAmount()
    {
        return (float)$this->getData(self::COLUMN_INVDISC);
    }

    public function setInvoiceDiscountAmount(float $invoiceDiscountAmount)
    {
        $this->setData(self::COLUMN_INVDISC, $invoiceDiscountAmount);
    }

    public function getExtendedPrice()
    {
        return $this->getExtendedShippedPriceAmount() - $this->getInvoiceDiscountAmount();
    }

    public function setExtendedPrice(float $extendedPrice)
    {
        $this->setData(self::COLUMN_EXTINVMISC, $extendedPrice);
    }
}
