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
use ECInternet\Sage300Account\Api\Data\OeinvhInterface;
use ECInternet\Sage300Account\Model\ResourceModel\Arobl\CollectionFactory as AroblCollectionFactory;
use ECInternet\Sage300Account\Model\ResourceModel\Artcp\CollectionFactory as ArtcpCollectionFactory;
use ECInternet\Sage300Account\Model\ResourceModel\Oeinvd\CollectionFactory as OeinvdCollectionFactory;
use ECInternet\Sage300Account\Model\ResourceModel\Oeppre\CollectionFactory as OeppreCollectionFactory;
use ECInternet\Sage300Account\Model\ResourceModel\Oetermi\CollectionFactory as OetermiCollectionFactory;

/**
 * Oeinvh model
 *
 * @SuppressWarnings(PHPMD.ExcessiveParameterList)
 * @SuppressWarnings(PHPMD.ExcessivePublicCount)
 * @SuppressWarnings(PHPMD.LongVariable)
 */
class Oeinvh extends AbstractModel implements IdentityInterface, OeinvhInterface
{
    const CACHE_TAG = 'ecinternet_sage300account_oeinvh';

    protected $_cacheTag    = 'ecinternet_sage300account_oeinvh';

    protected $_eventPrefix = 'ecinternet_sage300account_oeinvh';

    protected $_eventObject = 'oeinvh';

    /**
     * @var \Magento\Framework\Stdlib\DateTime
     */
    private $dateTime;

    /**
     * @var \ECInternet\Sage300Account\Model\ResourceModel\Arobl\CollectionFactory
     */
    private $aroblCollectionFactory;

    /**
     * @var \ECInternet\Sage300Account\Model\ResourceModel\Artcp\CollectionFactory
     */
    private $artcpCollectionFactory;

    /**
     * @var \ECInternet\Sage300Account\Model\ResourceModel\Oeinvd\CollectionFactory
     */
    private $oeinvdCollectionFactory;

    /**
     * @var \ECInternet\Sage300Account\Model\ResourceModel\Oeppre\CollectionFactory
     */
    private $oeppreCollectionFactory;

    /**
     * @var \ECInternet\Sage300Account\Model\ResourceModel\Oetermi\CollectionFactory
     */
    private $oetermiCollectionFactory;

    /**
     * Oeinvh constructor.
     *
     * @param \Magento\Framework\Model\Context                                         $context
     * @param \Magento\Framework\Registry                                              $registry
     * @param \Magento\Framework\Stdlib\DateTime                                       $dateTime
     * @param \ECInternet\Sage300Account\Model\ResourceModel\Arobl\CollectionFactory   $aroblCollectionFactory
     * @param \ECInternet\Sage300Account\Model\ResourceModel\Artcp\CollectionFactory   $artcpCollectionFactory
     * @param \ECInternet\Sage300Account\Model\ResourceModel\Oeinvd\CollectionFactory  $oeinvdCollectionFactory
     * @param \ECInternet\Sage300Account\Model\ResourceModel\Oeppre\CollectionFactory  $oeppreCollectionFactory
     * @param \ECInternet\Sage300Account\Model\ResourceModel\Oetermi\CollectionFactory $oetermiCollectionFactory
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource|null             $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb|null                       $resourceCollection
     * @param array                                                                    $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        DateTime $dateTime,
        AroblCollectionFactory $aroblCollectionFactory,
        ArtcpCollectionFactory $artcpCollectionFactory,
        OeinvdCollectionFactory $oeinvdCollectionFactory,
        OeppreCollectionFactory $oeppreCollectionFactory,
        OetermiCollectionFactory $oetermiCollectionFactory,
        AbstractResource $resource = null,
        AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        $this->dateTime                = $dateTime;
        $this->aroblCollectionFactory  = $aroblCollectionFactory;
        $this->artcpCollectionFactory = $artcpCollectionFactory;
        $this->oeinvdCollectionFactory  = $oeinvdCollectionFactory;
        $this->oeppreCollectionFactory  = $oeppreCollectionFactory;
        $this->oetermiCollectionFactory = $oetermiCollectionFactory;

        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    protected function _construct()
    {
        $this->_init('ECInternet\Sage300Account\Model\ResourceModel\Oeinvh');
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

    public function getOrderDate()
    {
        return (int)$this->getData(self::COLUMN_ORDDATE);
    }

    public function setOrderDate(int $orderDate)
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

    public function getInvoiceDate()
    {
        return (int)$this->getData(self::COLUMN_INVDATE);
    }

    public function setInvoiceDate(int $invoiceDate)
    {
        $this->setData(self::COLUMN_INVDATE, $invoiceDate);
    }

    public function getInvoiceTotalBeforeTax()
    {
        return (float)$this->getData(self::COLUMN_INVNETNOTX);
    }

    public function setInvoiceTotalBeforeTax(float $invoiceTotalBeforeTax)
    {
        $this->setData(self::COLUMN_INVNETNOTX, $invoiceTotalBeforeTax);
    }

    public function getInvoiceTotalTaxAmount()
    {
        return (float)$this->getData(self::COLUMN_INVITAXTOT);
    }

    public function setInvoiceTotalTaxAmount(float $invoiceTotalTaxAmount)
    {
        return $this->setData(self::COLUMN_INVITAXTOT, $invoiceTotalTaxAmount);
    }

    public function getInvoiceSubtotalAmount()
    {
        return (float)$this->getData(self::COLUMN_INVSUBTOT);
    }

    public function setInvoiceSubtotalAmount(float $invoiceSubtotalAmount)
    {
        $this->setData(self::COLUMN_INVSUBTOT, $invoiceSubtotalAmount);
    }

    public function getInvoiceTotalWithTax()
    {
        return (float)$this->getData(self::COLUMN_INVNETWTX);
    }

    public function setInvoiceTotalWithTax(float $invoiceTotalWithTax)
    {
        $this->setData(self::COLUMN_INVNETWTX, $invoiceTotalWithTax);
    }

    public function getInvoiceHomeCurrency()
    {
        return (string)$this->getData(self::COLUMN_INHOMECURR);
    }

    public function setInvoiceHomeCurrency(string $invoiceHomeCurrency)
    {
        $this->setData(self::COLUMN_INHOMECURR, $invoiceHomeCurrency);
    }

    public function getInvoiceSourceCurrency()
    {
        return (string)$this->getData(self::COLUMN_INSOURCURR);
    }

    public function setInvoiceSourceCurrency(string $invoiceSourceCurrency)
    {
        $this->setData(self::COLUMN_INSOURCURR, $invoiceSourceCurrency);
    }

    public function getSalesperson1()
    {
        return (string)$this->getData(self::COLUMN_SALESPER1);
    }

    public function setSalesperson1(string $salesperson1)
    {
        $this->setData(self::COLUMN_SALESPER1, $salesperson1);
    }

    public function getInvoiceNumber()
    {
        return (string)$this->getData(self::COLUMN_INVNUMBER);
    }

    public function setInvoiceNumber(string $invoiceNumber)
    {
        $this->setData(self::COLUMN_INVNUMBER, $invoiceNumber);
    }

    public function getRetainageAmount()
    {
        return (float)$this->getData(self::COLUMN_RTGAMOUNT);
    }

    public function setRetainageAmount(float $retainageAmount)
    {
        $this->setData(self::COLUMN_RTGAMOUNT, $retainageAmount);
    }

    public function getInvoiceDetails()
    {
        $invoiceDetails = [];

        $collection = $this->oeinvdCollectionFactory->create()
            ->addFieldToFilter(Oeinvd::COLUMN_INVUNIQ, ['eq' => $this->getInvoiceUniquifier()])
            ->setOrder(Oeinvd::COLUMN_LINENUM, 'ASC');

        foreach ($collection->getItems() as $invoiceDetail) {
            $invoiceDetails[] = $invoiceDetail;
        }

        return $invoiceDetails;
    }

    public function getTotalDue()
    {
        $invoiceTotal    = $this->getInvoiceTotalWithTax();
        $maxPayment      = $this->getMaxPayment();
        $discountTotal   = $this->getOEDiscountTotal();
        $retainageAmount = $this->getRetainageAmount();

        return $invoiceTotal - $maxPayment - $discountTotal - $retainageAmount;
    }

    /**
     * Look for matching AROBL ("Documents") record, return the SWPAID field.
     *
     * @return bool
     */
    public function isFullyPaid()
    {
        /** @var \ECInternet\Sage300Account\Model\ResourceModel\Arobl\Collection $documents */
        $documents = $this->aroblCollectionFactory->create()
            ->addFieldToFilter(Arobl::COLUMN_IDINVC, ['eq' => $this->getInvoiceNumber()]);

        if ($documents->getSize() === 1) {
            /** @var \ECInternet\Sage300Account\Model\Data\Arobl $document */
            $document = $documents->getFirstItem();

            return $document->isFullyPaid();
        }

        return false;
    }

    public function getRemainingBalance()
    {
        // TODO: Implement
        return 0.0;
    }

    public function getMaxPayment()
    {
        $arPaymentTotal = $this->getARPaymentTotal();
        $oePaymentTotal = $this->getOEPaymentTotal();

        return (float)max($arPaymentTotal, $oePaymentTotal, 0);
    }

    public function getARPaymentTotal()
    {
        $payment = 0.0;

        /** @var \ECInternet\Sage300Account\Model\ResourceModel\Artcp\Collection $artcpCollection */
        $artcpCollection = $this->getAppliedReceiptsAndAdjustments();

        /** @var \ECInternet\Sage300Account\Model\Data\Artcp $artcpItem */
        foreach ($artcpCollection as $artcpItem) {
            $payment += $artcpItem->getCustomerReceiptAmount();
        }

        return $payment;
    }

    /**
     * Get DiscountDate for first InvoicePaymentSchedule
     *
     * @return int|null
     */
    public function getInvoicePaymentScheduleDiscountDate()
    {
        if ($invoicePaymentSchedule = $this->getInvoicePaymentSchedule()) {
            return $invoicePaymentSchedule->getDiscountDate();
        }

        return null;
    }

    /**
     * Get DiscountAmount for first InvoicePaymentSchedule
     *
     * @return float|null
     */
    public function getInvoicePaymentScheduleDiscountAmount()
    {
        if ($invoicePaymentSchedule = $this->getInvoicePaymentSchedule()) {
            return $invoicePaymentSchedule->getDiscountAmount();
        }

        return null;
    }

    /**
     * Get DueDate for first InvoicePaymentSchedule
     *
     * @return int|null
     */
    public function getInvoicePaymentScheduleDueDate()
    {
        if ($invoicePaymentSchedule = $this->getInvoicePaymentSchedule()) {
            return $invoicePaymentSchedule->getDueDate();
        }

        return null;
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

    /**
     * Get first InvoicePaymentSchedule
     *
     * @return \ECInternet\Sage300Account\Model\Data\Oetermi|null
     */
    private function getInvoicePaymentSchedule()
    {
        /** @var \ECInternet\Sage300Account\Model\ResourceModel\Oetermi\Collection $invoicePaymentSchedules */
        $invoicePaymentSchedules = $this->getInvoicePaymentSchedules();

        if ($invoicePaymentSchedules->getSize() == 1) {
            $oetermi = $invoicePaymentSchedules->getFirstItem();
            if ($oetermi instanceof Oetermi) {
                return $oetermi;
            }
        }

        return null;
    }

    /**
     * Get the total PostedPrepayment payment sum
     *
     * @return float
     */
    private function getOEPaymentTotal()
    {
        $payment = 0.0;

        /** @var \ECInternet\Sage300Account\Model\ResourceModel\Oeppre\Collection $oeppreCollection */
        $oeppreCollection = $this->getPostedPrepayments();

        /** @var \ECInternet\Sage300Account\Model\Data\Oeppre $oeppreItem */
        foreach ($oeppreCollection as $oeppreItem) {
            // Only add to total if this prepayment was applied to the invoice.
            // If it was applied to the order, then we assume it's a fully unpaid invoice.
            if ($oeppreItem->appliedToInvoice()) {
                $payment += $oeppreItem->getPaymentInCustomerCurrency();
            }
        }

        return $payment;
    }

    /**
     * Get the total PostedPrepayment discount sum
     *
     * @return float
     */
    private function getOEDiscountTotal()
    {
        $discount = 0.0;

        /** @var \ECInternet\Sage300Account\Model\ResourceModel\Oeppre\Collection $oeppreCollection */
        $oeppreCollection = $this->getPostedPrepayments();

        /** @var \ECInternet\Sage300Account\Model\Data\Oeppre $oeppreItem */
        foreach ($oeppreCollection as $oeppreItem) {
            $discount += $oeppreItem->getPaymentDiscount();
        }

        return $discount;
    }

    /**
     * Get Collection of AppliedReceiptsAndAdjustments
     *
     * @return \ECInternet\Sage300Account\Model\ResourceModel\Artcp\Collection
     */
    private function getAppliedReceiptsAndAdjustments()
    {
        return $this->artcpCollectionFactory->create()
            ->addFieldToFilter(Artcp::COLUMN_IDINVC, ['eq' => $this->getInvoiceNumber()]);
    }

    /**
     * Get Collection of PaymentSchedules
     *
     * @return \ECInternet\Sage300Account\Model\ResourceModel\Oetermi\Collection
     */
    private function getInvoicePaymentSchedules()
    {
        return $this->oetermiCollectionFactory->create()
            ->addFieldToFilter(Oetermi::COLUMN_INVUNIQ, ['eq' => $this->getInvoiceUniquifier()]);
    }

    /**
     * Get Collection of PostedPrepayments
     *
     * @return \ECInternet\Sage300Account\Model\ResourceModel\Oeppre\Collection
     */
    private function getPostedPrepayments()
    {
        return $this->oeppreCollectionFactory->create()
            ->addFieldToFilter(Oeppre::COLUMN_DOCNUMBER, [
                ['eq' => $this->getOrderNumber()],
                ['eq' => $this->getInvoiceNumber()]
            ]);
    }
}
