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
use ECInternet\Sage300Account\Api\Data\OetermiInterface;

/**
 * Oetermi Model
 */
class Oetermi extends AbstractModel implements IdentityInterface, OetermiInterface
{
    const CACHE_TAG = 'ecinternet_sage300account_oetermi';

    protected $_cacheTag    = 'ecinternet_sage300account_oetermi';

    protected $_eventPrefix = 'ecinternet_sage300account_oetermi';

    protected $_eventObject = 'oetermi';

    /**
     * @var \Magento\Framework\Stdlib\DateTime
     */
    private $dateTime;

    /**
     * Oetermi constructor.
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
        $this->_init('ECInternet\Sage300Account\Model\ResourceModel\Oetermi');
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

    public function getInvoiceUniquifier()
    {
        return (int)$this->getData(self::COLUMN_INVUNIQ);
    }

    public function setInvoiceUniquifier(int $invoiceUniquifier)
    {
        $this->setData(self::COLUMN_INVUNIQ, $invoiceUniquifier);
    }

    public function getPaymentNumber()
    {
        return (int)$this->getData(self::COLUMN_PAYMENT);
    }

    public function setPaymentNumber(int $paymentNumber)
    {
        $this->setData(self::COLUMN_PAYMENT, $paymentNumber);
    }

    public function getDiscountDate()
    {
        return (int)$this->getData(self::COLUMN_DISCDATE);
    }

    public function setDiscountDate(int $discountDate)
    {
        $this->setData(self::COLUMN_DISCDATE, $discountDate);
    }

    public function getDiscountAmount()
    {
        return (float)$this->getData(self::COLUMN_DISCAMT);
    }

    public function setDiscountAmount(float $discountAmount)
    {
        $this->setData(self::COLUMN_DISCAMT, $discountAmount);
    }

    public function getDueDate()
    {
        return (int)$this->getData(self::COLUMN_DUEDATE);
    }

    public function setDueDate(int $dueDate)
    {
        $this->setData(self::COLUMN_DUEDATE, $dueDate);
    }
}
