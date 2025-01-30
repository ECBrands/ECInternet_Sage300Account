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
use ECInternet\Sage300Account\Api\Data\OeshdtInterface;

/**
 * Oeshdt Model
 */
class Oeshdt extends AbstractModel implements IdentityInterface, OeshdtInterface
{
    private const CACHE_TAG = 'ecinternet_sage300account_oeshdt';

    protected $_cacheTag    = 'ecinternet_sage300account_oeshdt';

    protected $_eventPrefix = 'ecinternet_sage300account_oeshdt';

    protected $_eventObject = 'oeshdt';

    /**
     * @var \Magento\Framework\Stdlib\DateTime
     */
    private $dateTime;

    /**
     * Oeshdt constructor.
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
        $this->_init('ECInternet\Sage300Account\Model\ResourceModel\Oeshdt');
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

    public function getIsActive()
    {
        return (bool)$this->getData(self::COLUMN_IS_ACTIVE);
    }

    public function setIsActive($isActive)
    {
        $this->setData(self::COLUMN_IS_ACTIVE, $isActive);
    }

    public function getCustomerNumber()
    {
        return (string)$this->getData(self::COLUMN_CUSTOMER);
    }

    public function setCustomerNumber($customerNumber)
    {
        $this->setData(self::COLUMN_CUSTOMER, $customerNumber);
    }

    public function getItemNumber()
    {
        return (string)$this->getData(self::COLUMN_ITEM);
    }

    public function setItemNumber($itemNumber)
    {
        $this->setData(self::COLUMN_ITEM, $itemNumber);
    }

    public function getShipDate()
    {
        return (string)$this->getData(self::COLUMN_SHIPDATE);
    }

    public function setShipDate($shipDate)
    {
        $this->setData(self::COLUMN_SHIPDATE, $shipDate);
    }
}
