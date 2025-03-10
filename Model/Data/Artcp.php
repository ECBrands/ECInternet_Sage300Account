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
use ECInternet\Sage300Account\Api\Data\ArtcpInterface;

/**
 * Artcp model
 */
class Artcp extends AbstractModel implements IdentityInterface, ArtcpInterface
{
    private const CACHE_TAG = 'ecinternet_sage300account_artcp';

    protected $_cacheTag    = 'ecinternet_sage300account_artcp';

    protected $_eventPrefix = 'ecinternet_sage300account_artcp';

    protected $_eventObject = 'artcp';

    /**
     * @var \Magento\Framework\Stdlib\DateTime
     */
    private $_dateTime;

    /**
     * Artcp constructor.
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
        $this->_init('ECInternet\Sage300Account\Model\ResourceModel\Artcp');
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

    public function getBatchType()
    {
        return (string)$this->getData(self::COLUMN_CODEPAYM);
    }

    public function setBatchType(string $batchType)
    {
        $this->setData(self::COLUMN_CODEPAYM, $batchType);
    }

    public function getBatchNumber()
    {
        return (int)$this->getData(self::COLUMN_CNTBTCH);
    }

    public function setBatchNumber(int $batchNumber)
    {
        $this->setData(self::COLUMN_CNTBTCH, $batchNumber);
    }

    public function getEntryNumber()
    {
        return (int)$this->getData(self::COLUMN_CNTITEM);
    }

    public function setEntryNumber(int $entryNumber)
    {
        $this->setData(self::COLUMN_CNTITEM, $entryNumber);
    }

    public function getLineNumber()
    {
        return (int)$this->getData(self::COLUMN_CNTLINE);
    }

    public function setLineNumber(int $lineNumber)
    {
        $this->setData(self::COLUMN_CNTLINE, $lineNumber);
    }

    public function getDocumentNumber()
    {
        return (string)$this->getData(self::COLUMN_IDINVC);
    }

    public function setDocumentNumber(string $documentNumber)
    {
        $this->setData(self::COLUMN_IDINVC, $documentNumber);
    }

    public function getCustomerReceiptAmount()
    {
        return (float)$this->getData(self::COLUMN_AMTPAYM);
    }

    public function setCustomerReceiptAmount(float $customerReceiptAmount)
    {
        $this->setData(self::COLUMN_AMTPAYM, $customerReceiptAmount);
    }
}
