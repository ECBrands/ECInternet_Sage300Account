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
use ECInternet\Sage300Account\Api\Data\OeppreInterface;

/**
 * Oeppre Model
 */
class Oeppre extends AbstractModel implements IdentityInterface, OeppreInterface
{
    const CACHE_TAG = 'ecinternet_sage300account_oeppre';

    protected $_cacheTag    = 'ecinternet_sage300account_oeppre';

    protected $_eventPrefix = 'ecinternet_sage300account_oeppre';

    protected $_eventObject = 'oeppre';

    /**
     * @var \Magento\Framework\Stdlib\DateTime
     */
    private $dateTime;

    /**
     * Oeppre constructor.
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
        $this->_init('ECInternet\Sage300Account\Model\ResourceModel\Oeppre');
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

    public function getApplyTo()
    {
        return (int)$this->getData(self::COLUMN_APPLYTO);
    }

    public function setApplyTo(int $applyTo)
    {
        $this->setData(self::COLUMN_APPLYTO, $applyTo);
    }

    public function getDocumentNumber()
    {
        return (string)$this->getData(self::COLUMN_DOCNUMBER);
    }

    public function setDocumentNumber(string $documentNumber)
    {
        $this->setData(self::COLUMN_DOCNUMBER, $documentNumber);
    }

    public function getPrepaymentNumber()
    {
        return (int)$this->getData(self::COLUMN_PPNUMBER);
    }

    public function setPrepaymentNumber(int $prepaymentNumber)
    {
        $this->setData(self::COLUMN_PPNUMBER, $prepaymentNumber);
    }

    public function getPaymentInCustomerCurrency()
    {
        return (float)$this->getData(self::COLUMN_PAYMENT);
    }

    public function setPaymentInCustomerCurrency(float $paymentInCustomerCurrency)
    {
        $this->setData(self::COLUMN_PAYMENT, $paymentInCustomerCurrency);
    }

    public function getPaymentDiscount()
    {
        return (float)$this->getData(self::COLUMN_INVPAYDISC);
    }

    public function setPaymentDiscount(float $paymentDiscount)
    {
        $this->setData(self::COLUMN_INVPAYDISC, $paymentDiscount);
    }

    /**
     * Was prepayment applied to invoice?
     *
     * @return bool
     */
    public function appliedToInvoice()
    {
        return $this->getApplyTo() === self::APPLY_TO_INVOICE;
    }
}
