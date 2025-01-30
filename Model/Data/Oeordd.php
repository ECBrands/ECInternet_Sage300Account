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
use ECInternet\Sage300Account\Api\Data\OeorddInterface;

/**
 * Oeordd Model
 */
class Oeordd extends AbstractModel implements IdentityInterface, OeorddInterface
{
    private const CACHE_TAG = 'ecinternet_sage300account_oeordd';

    protected $_cacheTag    = 'ecinternet_sage300account_oeordd';

    protected $_eventPrefix = 'ecinternet_sage300account_oeordd';

    protected $_eventObject = 'oeordd';

    /**
     * @var \Magento\Framework\Stdlib\DateTime
     */
    private $dateTime;

    /**
     * Oeordd constructor.
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
        $this->dateTime = $dateTime;

        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    protected function _construct()
    {
        $this->_init('ECInternet\Sage300Account\Model\ResourceModel\Oeordd');
    }

    public function beforeSave()
    {
        // Always update (we can use this to verify syncs are running)
        $this->setUpdatedAt($this->dateTime->formatDate(true));

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

    public function getOrderUniquifier()
    {
        return (int)$this->getData(self::COLUMN_ORDUNIQ);
    }

    public function setOrderUniquifier(int $orderUniquifier)
    {
        $this->setData(self::COLUMN_ORDUNIQ, $orderUniquifier);
    }

    public function getLineNumber()
    {
        return (int)$this->getData(self::COLUMN_LINENUM);
    }

    public function setLineNumber(int $lineNumber)
    {
        $this->setData(self::COLUMN_LINENUM, $lineNumber);
    }

    public function getLineType()
    {
        return (int)$this->getData(self::COLUMN_LINETYPE);
    }

    public function setLineType(int $lineType)
    {
        $this->setData(self::COLUMN_LINETYPE, $lineType);
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

    public function getQuantityOrdered()
    {
        return (float)$this->getData(self::COLUMN_QTYORDERED);
    }

    public function setQuantityOrdered(float $quantityOrdered)
    {
        $this->setData(self::COLUMN_QTYORDERED, $quantityOrdered);
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
        return $this->getData(self::COLUMN_QTYBACKORD);
    }

    public function setQuantityBackordered(float $quantityBackordered)
    {
        $this->setData(self::COLUMN_QTYBACKORD, $quantityBackordered);
    }

    public function getOrderUnitOfMeasure()
    {
        return $this->getData(self::COLUMN_ORDUNIT);
    }

    public function setOrderUnitOfMeasure(string $orderUnitOfMeasure)
    {
        $this->setData(self::COLUMN_ORDUNIT, $orderUnitOfMeasure);
    }

    public function getOrderUnitPrice()
    {
        return (float)$this->getData(self::COLUMN_UNITPRICE);
    }

    public function setOrderUnitPrice(float $orderUnitPrice)
    {
        $this->setData(self::COLUMN_UNITPRICE, $orderUnitPrice);
    }

    public function getExtendedAmount()
    {
        return (float)$this->getData(self::COLUMN_EXTINVMISC);
    }

    public function setExtendedAmount(float $extendedAmount)
    {
        $this->setData(self::COLUMN_EXTINVMISC, $extendedAmount);
    }

    /**
     * Calculate extended price
     *
     * @return float
     */
    public function getExtendedPrice()
    {
        return (float)($this->getQuantityShipped() * $this->getOrderUnitPrice());
    }
}
