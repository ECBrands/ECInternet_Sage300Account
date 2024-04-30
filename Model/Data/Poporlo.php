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
use ECInternet\Sage300Account\Api\Data\PoporloInterface;

/**
 * Poporlo Model
 *
 * @SuppressWarnings(PHPMD.LongVariable)
 */
class Poporlo extends AbstractModel implements IdentityInterface, PoporloInterface
{
    const CACHE_TAG = 'ecinternet_sage300account_poporlo';

    protected $_cacheTag    = 'ecinternet_sage300account_poporlo';

    protected $_eventPrefix = 'ecinternet_sage300account_poporlo';

    protected $_eventObject = 'poporlo';

    /**
     * @var \Magento\Framework\Stdlib\DateTime
     */
    private $dateTime;

    /**
     * Poporlo constructor.
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
        $this->_init('ECInternet\Sage300Account\Model\ResourceModel\Poporlo');
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

    public function getLineNumber()
    {
        return (float)$this->getData(self::COLUMN_PORLREV);
    }

    public function setLineNumber(float $lineNumber)
    {
        $this->setData(self::COLUMN_PORLREV, $lineNumber);
    }

    public function getOptionalField()
    {
        return (string)$this->getData(self::COLUMN_OPTFIELD);
    }

    public function setOptionalField(string $optionalField)
    {
        $this->setData(self::COLUMN_OPTFIELD, $optionalField);
    }

    public function getValue()
    {
        return (string)$this->getData(self::COLUMN_VALUE);
    }

    public function setValue(string $value)
    {
        $this->setData(self::COLUMN_VALUE, $value);
    }
}
