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
use ECInternet\Sage300Account\Api\Data\OeordhInterface;
use ECInternet\Sage300Account\Model\ResourceModel\Oeordd\CollectionFactory as OeorddCollectionFactory;

/**
 * Oeordh data model
 *
 * @SuppressWarnings(PHPMD.ExcessivePublicCount)
 * @SuppressWarnings(PHPMD.LongVariable)
 */
class Oeordh extends AbstractModel implements IdentityInterface, OeordhInterface
{
    private const CACHE_TAG = 'ecinternet_sage300account_oeordh';

    protected $_cacheTag    = self::CACHE_TAG;

    protected $_eventPrefix = 'ecinternet_sage300account_oeordh';

    protected $_eventObject = 'oeordh';

    /**
     * @var \Magento\Framework\Stdlib\DateTime
     */
    private $dateTime;

    /**
     * @var \ECInternet\Sage300Account\Model\ResourceModel\Oeordd\CollectionFactory
     */
    private $oeorddCollectionFactory;

    /**
     * Oeordh constructor.
     *
     * @param \Magento\Framework\Model\Context                                        $context
     * @param \Magento\Framework\Registry                                             $registry
     * @param \Magento\Framework\Stdlib\DateTime                                      $dateTime
     * @param \ECInternet\Sage300Account\Model\ResourceModel\Oeordd\CollectionFactory $oeorddCollectionFactory
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource|null            $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb|null                      $resourceCollection
     * @param array                                                                   $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        DateTime $dateTime,
        OeorddCollectionFactory $oeorddCollectionFactory,
        AbstractResource $resource = null,
        AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        $this->oeorddCollectionFactory = $oeorddCollectionFactory;
        $this->dateTime                = $dateTime;

        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    protected function _construct()
    {
        $this->_init('ECInternet\Sage300Account\Model\ResourceModel\Oeordh');
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

    public function getOrderNumber()
    {
        return (string)$this->getData(self::COLUMN_ORDNUMBER);
    }

    public function setOrderNumber(string $orderNumber)
    {
        $this->setData(self::COLUMN_ORDNUMBER, $orderNumber);
    }

    public function getCustomerNumber()
    {
        return (string)$this->getData(self::COLUMN_CUSTOMER);
    }

    public function setCustomerNumber(string $customerNumber)
    {
        $this->setData(self::COLUMN_CUSTOMER, $customerNumber);
    }

    public function getBillToName()
    {
        return (string)$this->getData(self::COLUMN_BILNAME);
    }

    public function setBillToName(string $billToName)
    {
        $this->setData(self::COLUMN_BILNAME, $billToName);
    }

    public function getBillToAddressLine1()
    {
        return (string)$this->getData(self::COLUMN_BILADDR1);
    }

    public function setBillToAddressLine1(string $billToAddressLine1)
    {
        $this->setData(self::COLUMN_BILADDR1, $billToAddressLine1);
    }

    public function getBillToAddressLine2()
    {
        return (string)$this->getData(self::COLUMN_BILADDR2);
    }

    public function setBillToAddressLine2(string $billToAddressLine2)
    {
        $this->setData(self::COLUMN_BILADDR2, $billToAddressLine2);
    }

    public function getBillToAddressLine3()
    {
        return (string)$this->getData(self::COLUMN_BILADDR3);
    }

    public function setBillToAddressLine3(string $billToAddressLine3)
    {
        $this->setData(self::COLUMN_BILADDR3, $billToAddressLine3);
    }

    public function getBillToAddressLine4()
    {
        return (string)$this->getData(self::COLUMN_BILADDR4);
    }

    public function setBillToAddressLine4(string $billToAddressLine4)
    {
        $this->setData(self::COLUMN_BILADDR4, $billToAddressLine4);
    }

    public function getBillToAddressLine5()
    {
        return (string)$this->getData(self::COLUMN_BILADDR5);
    }

    public function setBillToAddressLine5(string $billToAddressLine5)
    {
        $this->setData(self::COLUMN_BILADDR5, $billToAddressLine5);
    }

    public function getBillToCity()
    {
        return (string)$this->getData(self::COLUMN_BILCITY);
    }

    public function setBillToCity(string $billToCity)
    {
        $this->setData(self::COLUMN_BILCITY, $billToCity);
    }

    public function getBillToState()
    {
        return (string)$this->getData(self::COLUMN_BILSTATE);
    }

    public function setBillToState(string $billToState)
    {
        $this->setData(self::COLUMN_BILSTATE, $billToState);
    }

    public function getBillToZipCode()
    {
        return (string)$this->getData(self::COLUMN_BILZIP);
    }

    public function setBillToZipCode(string $billToZipCode)
    {
        $this->setData(self::COLUMN_BILZIP, $billToZipCode);
    }

    public function getBillToCountry()
    {
        return (string)$this->getData(self::COLUMN_BILCOUNTRY);
    }

    public function setBillToCountry(string $billToCountry)
    {
        $this->setData(self::COLUMN_BILCOUNTRY, $billToCountry);
    }

    public function getShipToName()
    {
        return (string)$this->getData(self::COLUMN_SHPNAME);
    }

    public function setShipToName(string $shipToName)
    {
        $this->setData(self::COLUMN_SHPNAME, $shipToName);
    }

    public function getShipToAddressLine1()
    {
        return (string)$this->getData(self::COLUMN_SHPADDR1);
    }

    public function setShipToAddressLine1(string $shipToAddressLine1)
    {
        $this->setData(self::COLUMN_SHPADDR1, $shipToAddressLine1);
    }

    public function getShipToAddressLine2()
    {
        return (string)$this->getData(self::COLUMN_SHPADDR2);
    }

    public function setShipToAddressLine2(string $shipToAddressLine2)
    {
        $this->setData(self::COLUMN_SHPADDR2, $shipToAddressLine2);
    }

    public function getShipToAddressLine3()
    {
        return (string)$this->getData(self::COLUMN_SHPADDR3);
    }

    public function setShipToAddressLine3(string $shipToAddressLine3)
    {
        $this->setData(self::COLUMN_SHPADDR3, $shipToAddressLine3);
    }

    public function getShipToAddressLine4()
    {
        return (string)$this->getData(self::COLUMN_SHPADDR4);
    }

    public function setShipToAddressLine4(string $shipToAddressLine4)
    {
        $this->setData(self::COLUMN_SHPADDR4, $shipToAddressLine4);
    }

    public function getShipToAddressLine5()
    {
        return (string)$this->getData(self::COLUMN_SHPADDR5);
    }

    public function setShipToAddressLine5(string $shipToAddressLine5)
    {
        $this->setData(self::COLUMN_SHPADDR5, $shipToAddressLine5);
    }

    public function getShipToCity()
    {
        return (string)$this->getData(self::COLUMN_SHPCITY);
    }

    public function setShipToCity(string $shipToCity)
    {
        $this->setData(self::COLUMN_SHPCITY, $shipToCity);
    }

    public function getShipToState()
    {
        return (string)$this->getData(self::COLUMN_SHPSTATE);
    }

    public function setShipToState(string $shipToState)
    {
        $this->setData(self::COLUMN_SHPSTATE, $shipToState);
    }

    public function getShipToZipCode()
    {
        return (string)$this->getData(self::COLUMN_SHPZIP);
    }

    public function setShipToZipCode(string $shipToZipCode)
    {
        $this->setData(self::COLUMN_SHPZIP, $shipToZipCode);
    }

    public function getShipToCountry()
    {
        return (string)$this->getData(self::COLUMN_SHPCOUNTRY);
    }

    public function setShipToCountry(string $shipToCountry)
    {
        $this->setData(self::COLUMN_SHPCOUNTRY, $shipToCountry);
    }

    public function getPurchaseOrderNumber()
    {
        return (string)$this->getData(self::COLUMN_PONUMBER);
    }

    public function setPurchaseOrderNumber(string $purchaseOrderNumber)
    {
        $this->setData(self::COLUMN_PONUMBER, $purchaseOrderNumber);
    }

    public function getTermsCode()
    {
        return (string)$this->getData(self::COLUMN_TERMS);
    }

    public function setTermsCode(string $termsCode)
    {
        $this->setData(self::COLUMN_TERMS, $termsCode);
    }

    public function getOrderReference()
    {
        return (string)$this->getData(self::COLUMN_REFERENCE);
    }

    public function setOrderReference(string $orderReference)
    {
        $this->setData(self::COLUMN_REFERENCE, $orderReference);
    }

    public function getOrderDate()
    {
        return (string)$this->getData(self::COLUMN_ORDDATE);
    }

    public function setOrderDate(string $orderDate)
    {
        $this->setData(self::COLUMN_ORDDATE, $orderDate);
    }

    public function getShipViaCode()
    {
        return (string)$this->getData(self::COLUMN_SHIPVIA);
    }

    public function setShipViaCode(string $shipViaCode)
    {
        $this->setData(self::COLUMN_SHIPVIA, $shipViaCode);
    }

    public function getOrderComment()
    {
        return (string)$this->getData(self::COLUMN_COMMENT);
    }

    public function setOrderComment(string $orderComment)
    {
        $this->setData(self::COLUMN_COMMENT, $orderComment);
    }

    public function getSalesperson1()
    {
        return (string)$this->getData(self::COLUMN_SALESPER1);
    }

    public function setSalesperson1(string $salesperson1)
    {
        $this->setData(self::COLUMN_SALESPER1, $salesperson1);
    }

    public function getTaxAuthority1()
    {
        return (string)$this->getData(self::COLUMN_TAUTH1);
    }

    public function setTaxAuthority1(string $taxAuthority1)
    {
        $this->setData(self::COLUMN_TAUTH1, $taxAuthority1);
    }

    public function getTaxAuthority2()
    {
        return (string)$this->getData(self::COLUMN_TAUTH2);
    }

    public function setTaxAuthority2(string $taxAuthority2)
    {
        $this->setData(self::COLUMN_TAUTH2, $taxAuthority2);
    }

    public function getTaxAuthority3()
    {
        return (string)$this->getData(self::COLUMN_TAUTH3);
    }

    public function setTaxAuthority3(string $taxAuthority3)
    {
        $this->setData(self::COLUMN_TAUTH3, $taxAuthority3);
    }

    public function getTaxAuthority4()
    {
        return (string)$this->getData(self::COLUMN_TAUTH4);
    }

    public function setTaxAuthority4(string $taxAuthority4)
    {
        $this->setData(self::COLUMN_TAUTH4, $taxAuthority4);
    }

    public function getTaxAuthority5()
    {
        return (string)$this->getData(self::COLUMN_TAUTH5);
    }

    public function setTaxAuthority5(string $taxAuthority5)
    {
        $this->setData(self::COLUMN_TAUTH5, $taxAuthority5);
    }

    public function getExcludedTaxAmount1()
    {
        return (float)$this->getData(self::COLUMN_TEAMOUNT1);
    }

    public function setExcludedTaxAmount1(float $excludedTaxAmount1)
    {
        $this->setData(self::COLUMN_TEAMOUNT1, $excludedTaxAmount1);
    }

    public function getExcludedTaxAmount2()
    {
        return (float)$this->getData(self::COLUMN_TEAMOUNT2);
    }

    public function setExcludedTaxAmount2(float $excludedTaxAmount2)
    {
        $this->setData(self::COLUMN_TEAMOUNT2, $excludedTaxAmount2);
    }

    public function getExcludedTaxAmount3()
    {
        return (float)$this->getData(self::COLUMN_TEAMOUNT3);
    }

    public function setExcludedTaxAmount3(float $excludedTaxAmount3)
    {
        $this->setData(self::COLUMN_TEAMOUNT3, $excludedTaxAmount3);
    }

    public function getExcludedTaxAmount4()
    {
        return (float)$this->getData(self::COLUMN_TEAMOUNT4);
    }

    public function setExcludedTaxAmount4(float $excludedTaxAmount4)
    {
        $this->setData(self::COLUMN_TEAMOUNT4, $excludedTaxAmount4);
    }

    public function getExcludedTaxAmount5()
    {
        return (float)$this->getData(self::COLUMN_TEAMOUNT5);
    }

    public function setExcludedTaxAmount5(float $excludedTaxAmount5)
    {
        $this->setData(self::COLUMN_TEAMOUNT5, $excludedTaxAmount5);
    }

    public function getIncludedTaxAmount1()
    {
        return (float)$this->getData(self::COLUMN_TIAMOUNT1);
    }

    public function setIncludedTaxAmount1(float $includedTaxAmount1)
    {
        $this->setData(self::COLUMN_TIAMOUNT1, $includedTaxAmount1);
    }

    public function getIncludedTaxAmount2()
    {
        return (float)$this->getData(self::COLUMN_TIAMOUNT2);
    }

    public function setIncludedTaxAmount2(float $includedTaxAmount2)
    {
        $this->setData(self::COLUMN_TIAMOUNT2, $includedTaxAmount2);
    }

    public function getIncludedTaxAmount3()
    {
        return (float)$this->getData(self::COLUMN_TIAMOUNT3);
    }

    public function setIncludedTaxAmount3(float $includedTaxAmount3)
    {
        $this->setData(self::COLUMN_TIAMOUNT3, $includedTaxAmount3);
    }

    public function getIncludedTaxAmount4()
    {
        return (float)$this->getData(self::COLUMN_TIAMOUNT4);
    }

    public function setIncludedTaxAmount4(float $includedTaxAmount4)
    {
        $this->setData(self::COLUMN_TIAMOUNT4, $includedTaxAmount4);
    }

    public function getIncludedTaxAmount5()
    {
        return (float)$this->getData(self::COLUMN_TIAMOUNT5);
    }

    public function setIncludedTaxAmount5(float $includedTaxAmount5)
    {
        $this->setData(self::COLUMN_TIAMOUNT5, $includedTaxAmount5);
    }

    public function getOrderSubtotalAmount()
    {
        return (float)$this->getData(self::COLUMN_INVSUBTOT);
    }

    public function setOrderSubtotalAmount(float $orderSubtotalAmount)
    {
        $this->setData(self::COLUMN_INVSUBTOT, $orderSubtotalAmount);
    }

    public function getOrderTotalIncludingTax()
    {
        return (float)$this->getData(self::COLUMN_INVITAXTOT);
    }

    public function setOrderTotalIncludingTax(float $orderTotalIncludingTax)
    {
        $this->setData(self::COLUMN_INVITAXTOT, $orderTotalIncludingTax);
    }

    public function getOrderDiscountAmount()
    {
        return (float)$this->getData(self::COLUMN_INVDISCAMT);
    }

    public function setOrderDiscountAmount(float $orderDiscountAmount)
    {
        $this->setData(self::COLUMN_INVDISCAMT, $orderDiscountAmount);
    }

    public function getOrderTotal()
    {
        return (float)$this->getData(self::COLUMN_INVNETWTX);
    }

    public function setOrderTotal(float $orderTotal)
    {
        $this->setData(self::COLUMN_INVNETWTX, $orderTotal);
    }

    /**
     * @return array
     */
    public function getOrderDetails()
    {
        $orderDetails = [];

        $collection = $this->oeorddCollectionFactory->create()
            ->addFieldToFilter(Oeordd::COLUMN_ORDUNIQ, ['eq' => $this->getOrderUniquifier()])
            ->setOrder(Oeordd::COLUMN_LINENUM, 'ASC');

        /** @var \ECInternet\Sage300Account\Model\Data\Oeordd $orderDetail */
        foreach ($collection->getItems() as $orderDetail) {
            $orderDetails[] = $orderDetail;
        }

        return $orderDetails;
    }

    /**
     * Get calculated subtotal
     *
     * @return float
     */
    public function getSubtotal()
    {
        return $this->getOrderSubtotalAmount() - $this->getOrderTotalIncludingTax() - $this->getOrderDiscountAmount();
    }

    /**
     * Get total ExcludedTaxAmount and IncludedTaxAmounts sum
     *
     * @return float
     */
    public function getTotalTaxAmount()
    {
        $taxTotal = 0.0;

        $taxTotal += $this->getExcludedTaxAmount1();
        $taxTotal += $this->getExcludedTaxAmount2();
        $taxTotal += $this->getExcludedTaxAmount3();
        $taxTotal += $this->getExcludedTaxAmount4();
        $taxTotal += $this->getExcludedTaxAmount5();
        $taxTotal += $this->getIncludedTaxAmount1();
        $taxTotal += $this->getIncludedTaxAmount2();
        $taxTotal += $this->getIncludedTaxAmount3();
        $taxTotal += $this->getIncludedTaxAmount4();
        $taxTotal += $this->getIncludedTaxAmount5();

        return $taxTotal;
    }

    /**
     * Get total IncludeTaxAmount1 and ExcludedTaxAmount1 sum
     *
     * @return float
     */
    public function getTaxAmount1()
    {
        return $this->getIncludedTaxAmount1() + $this->getExcludedTaxAmount1();
    }

    /**
     * Get total IncludeTaxAmount2 and ExcludedTaxAmount2 sum
     *
     * @return float
     */
    public function getTaxAmount2()
    {
        return $this->getIncludedTaxAmount2() + $this->getExcludedTaxAmount2();
    }

    /**
     * Get total IncludeTaxAmount3 and ExcludedTaxAmount3 sum
     *
     * @return float
     */
    public function getTaxAmount3()
    {
        return $this->getIncludedTaxAmount3() + $this->getExcludedTaxAmount3();
    }

    /**
     * Get total IncludeTaxAmount4 and ExcludedTaxAmount4 sum
     *
     * @return float
     */
    public function getTaxAmount4()
    {
        return $this->getIncludedTaxAmount4() + $this->getExcludedTaxAmount4();
    }

    /**
     * Get total IncludeTaxAmount5 and ExcludedTaxAmount5 sum
     *
     * @return float
     */
    public function getTaxAmount5()
    {
        return $this->getIncludedTaxAmount5() + $this->getExcludedTaxAmount5();
    }

    /**
     * Get the formatted OrderDate
     *
     * @return string
     */
    public function getOrderDateFormatted()
    {
        return date('m/d/Y', strtotime($this->getOrderDate()));
    }

    /**
     * Get Billing Address display
     *
     * @return string
     */
    public function getBillingAddressDisplayLineHtml()
    {
        return $this->getBillToCity() . ', ' . $this->getBillToState() . ' ' . $this->getBillToZipCode();
    }

    /**
     * Get Shipping Address display
     *
     * @return string
     */
    public function getShippingAddressDisplayLineHtml()
    {
        return $this->getShipToCity() . ', ' . $this->getShipToState() . ' ' . $this->getShipToZipCode();
    }
}
