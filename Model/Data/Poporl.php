<?php
/**
 * Copyright (C) EC Brands Corporation - All Rights Reserved
 * Contact Licensing@ECInternet.com for use guidelines
 */
declare(strict_types=1);

namespace ECInternet\Sage300Account\Model\Data;

use Magento\Framework\Api\AttributeValueFactory;
use Magento\Framework\Api\ExtensionAttributesFactory;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Model\AbstractExtensibleModel;
use Magento\Framework\Model\Context;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Registry;
use Magento\Framework\Stdlib\DateTime;
use ECInternet\Sage300Account\Api\Data\PoporlInterface;
use ECInternet\Sage300Account\Api\Data\PoporlExtensionInterface;
use ECInternet\Sage300Account\Model\ResourceModel\Poporlo\CollectionFactory as PoporloCollectionFactory;

/**
 * Poporl Model
 *
 * @SuppressWarnings(PHPMD.LongVariable)
 */
class Poporl extends AbstractExtensibleModel implements IdentityInterface, PoporlInterface
{
    const CACHE_TAG = 'ecinternet_sage300account_poporl';

    protected $_cacheTag    = 'ecinternet_sage300account_poporl';

    protected $_eventPrefix = 'ecinternet_sage300account_poporl';

    protected $_eventObject = 'poporl';

    /**
     * @var \Magento\Framework\Stdlib\DateTime
     */
    private $dateTime;

    /**
     * @var \ECInternet\Sage300Account\Model\ResourceModel\Poporlo\CollectionFactory
     */
    private $poporloCollectionFactory;

    /**
     * Poporl constructor.
     *
     * @param \Magento\Framework\Model\Context                                         $context
     * @param \Magento\Framework\Registry                                              $registry
     * @param \Magento\Framework\Api\ExtensionAttributesFactory                        $extensionFactory
     * @param \Magento\Framework\Api\AttributeValueFactory                             $customAttributeFactory
     * @param \Magento\Framework\Stdlib\DateTime                                       $dateTime
     * @param \ECInternet\Sage300Account\Model\ResourceModel\Poporlo\CollectionFactory $poporloCollectionFactory
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource|null             $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb|null                       $resourceCollection
     * @param array                                                                    $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        ExtensionAttributesFactory $extensionFactory,
        AttributeValueFactory $customAttributeFactory,
        DateTime $dateTime,
        PoporloCollectionFactory $poporloCollectionFactory,
        AbstractResource $resource = null,
        AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        $this->dateTime                 = $dateTime;
        $this->poporloCollectionFactory = $poporloCollectionFactory;

        parent::__construct($context, $registry, $extensionFactory, $customAttributeFactory, $resource, $resourceCollection, $data);
    }

    protected function _construct()
    {
        $this->_init('ECInternet\Sage300Account\Model\ResourceModel\Poporl');
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

    public function setIsActive(bool $isActive)
    {
        $this->setData(self::COLUMN_IS_ACTIVE, $isActive);
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

    public function getPurchaseOrderLineSequenceKey()
    {
        return (float)$this->getData(self::COLUMN_PORLSEQ);
    }

    public function setPurchaseOrderLineSequenceKey(float $purchaseOrderLineSequenceKey)
    {
        $this->setData(self::COLUMN_PORLSEQ, $purchaseOrderLineSequenceKey);
    }

    public function getItemNumber()
    {
        return (string)$this->getData(self::COLUMN_ITEMNO);
    }

    public function setItemNumber(string $itemNumber)
    {
        $this->setData(self::COLUMN_ITEMNO, $itemNumber);
    }

    public function getQuantityOrdered()
    {
        return (float)$this->getData(self::COLUMN_OQORDERED);
    }

    public function setQuantityOrdered(float $quantityOrdered)
    {
        $this->setData(self::COLUMN_OQORDERED, $quantityOrdered);
    }

    public function getQuantityReceived()
    {
        return (float)$this->getData(self::COLUMN_OQRECEIVED);
    }

    public function setQuantityReceived(float $quantityReceived)
    {
        $this->setData(self::COLUMN_OQRECEIVED, $quantityReceived);
    }

    public function getQuantityCanceled()
    {
        return (float)$this->getData(self::COLUMN_OQCANCELED);
    }

    public function setQuantityCanceled(float $quantityCanceled)
    {
        $this->setData(self::COLUMN_OQCANCELED, $quantityCanceled);
    }

    public function getQuantityOutstanding()
    {
        return (float)$this->getData(self::COLUMN_OQOUTSTAND);
    }

    public function setQuantityOutstanding(float $quantityOustanding)
    {
        $this->setData(self::COLUMN_OQOUTSTAND, $quantityOustanding);
    }

    public function getDetailNumber()
    {
        return (int)$this->getData(self::COLUMN_DETAILNUM);
    }

    public function setDetailNumber(int $detailNumber)
    {
        $this->setData(self::COLUMN_DETAILNUM, $detailNumber);
    }

    public function getExtensionAttributes()
    {
        return $this->_getExtensionAttributes();
    }

    public function setExtensionAttributes(PoporlExtensionInterface $extensionAttributes)
    {
        $this->_setExtensionAttributes($extensionAttributes);
    }

    /**
     * @return \ECInternet\Sage300Account\Model\ResourceModel\Poporlo\Collection
     */
    public function getOptionalFieldValues()
    {
        return $this->poporloCollectionFactory->create()
            ->addFieldToFilter(Poporlo::COLUMN_PORHSEQ, $this->getPurchaseOrderSequenceKey())
            ->addFieldToFilter(Poporlo::COLUMN_PORLREV, $this->getLineNumber());
    }
}
