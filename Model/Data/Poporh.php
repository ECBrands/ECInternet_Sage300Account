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
use ECInternet\Sage300Account\Api\Data\PoporhInterface;

/**
 * Poporh Model
 *
 * @SuppressWarnings(PHPMD.LongVariable)
 */
class Poporh extends AbstractModel implements IdentityInterface, PoporhInterface
{
    const CACHE_TAG = 'ecinternet_sage300account_poporh';

    protected $_cacheTag    = 'ecinternet_sage300account_poporh';

    protected $_eventPrefix = 'ecinternet_sage300account_poporh';

    protected $_eventObject = 'poporh';

    /**
     * @var \Magento\Framework\Stdlib\DateTime
     */
    private $dateTime;

    /**
     * Poporh constructor.
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
        $this->_init('ECInternet\Sage300Account\Model\ResourceModel\Poporh');
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

    public function getPurchaseOrderSequenceKey()
    {
        return (float)$this->getData(self::COLUMN_PORHSEQ);
    }

    public function setPurchaseOrderSequenceKey(float $purchaseOrderSequenceKey)
    {
        $this->setData(self::COLUMN_PORHSEQ, $purchaseOrderSequenceKey);
    }

    public function getPurchaseOrderNumber()
    {
        return (string)$this->getData(self::COLUMN_PONUMBER);
    }

    public function setPurchaseOrderNumber(string $purchaseOrderNumber)
    {
        $this->setData(self::COLUMN_PONUMBER, $purchaseOrderNumber);
    }

    public function getVendorCode()
    {
        return (string)$this->getData(self::COLUMN_VDCODE);
    }

    public function setVendorCode(string $vendorCode)
    {
        $this->setData(self::COLUMN_VDCODE, $vendorCode);
    }

    public function getVendorName()
    {
        return (string)$this->getData(self::COLUMN_VDNAME);
    }

    public function setVendorName(string $vendorName)
    {
        $this->setData(self::COLUMN_VDNAME, $vendorName);
    }

    public function getExpectedArrivalDate()
    {
        return (float)$this->getData(self::COLUMN_EXPARRIVAL);
    }

    public function setExpectedArrivalDate(float $expectedArrivalDate)
    {
        $this->setData(self::COLUMN_EXPARRIVAL, $expectedArrivalDate);
    }

    public function getShipViaCode()
    {
        return (string)$this->getData(self::COLUMN_VIACODE);
    }

    public function setShipViaCode(string $shipViaCode)
    {
        $this->setData(self::COLUMN_VIACODE, $shipViaCode);
    }
}
