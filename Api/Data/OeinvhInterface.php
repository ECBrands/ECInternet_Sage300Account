<?php
/**
 * Copyright (C) EC Brands Corporation - All Rights Reserved
 * Contact Licensing@ECInternet.com for use guidelines
 */
declare(strict_types=1);

namespace ECInternet\Sage300Account\Api\Data;

interface OeinvhInterface
{
    const COLUMN_ID         = 'entity_id';

    const COLUMN_UPDATED_AT = 'updated_at';

    const COLUMN_INVUNIQ    = 'INVUNIQ';

    const COLUMN_ORDNUMBER  = 'ORDNUMBER';

    const COLUMN_CUSTOMER   = 'CUSTOMER';

    const COLUMN_BILNAME    = 'BILNAME';

    const COLUMN_BILADDR1   = 'BILADDR1';

    const COLUMN_BILADDR2   = 'BILADDR2';

    const COLUMN_BILADDR3   = 'BILADDR3';

    const COLUMN_BILADDR4   = 'BILADDR4';

    const COLUMN_BILCITY    = 'BILCITY';

    const COLUMN_BILSTATE   = 'BILSTATE';

    const COLUMN_BILZIP     = 'BILZIP';

    const COLUMN_BILCOUNTRY = 'BILCOUNTRY';

    const COLUMN_SHPNAME    = 'SHPNAME';

    const COLUMN_SHPADDR1   = 'SHPADDR1';

    const COLUMN_SHPADDR2   = 'SHPADDR2';

    const COLUMN_SHPADDR3   = 'SHPADDR3';

    const COLUMN_SHPADDR4   = 'SHPADDR4';

    const COLUMN_SHPCITY    = 'SHPCITY';

    const COLUMN_SHPSTATE   = 'SHPSTATE';

    const COLUMN_SHPZIP     = 'SHPZIP';

    const COLUMN_SHPCOUNTRY = 'SHPCOUNTRY';

    const COLUMN_PONUMBER   = 'PONUMBER';

    const COLUMN_TERMS      = 'TERMS';

    const COLUMN_ORDDATE    = 'ORDDATE';

    const COLUMN_SHIPVIA    = 'SHIPVIA';

    const COLUMN_INVDATE    = 'INVDATE';

    const COLUMN_INVNETNOTX = 'INVNETNOTX';

    const COLUMN_INVITAXTOT = 'INVITAXTOT';

    const COLUMN_INVSUBTOT  = 'INVSUBTOT';

    const COLUMN_INVNETWTX  = 'INVNETWTX';

    const COLUMN_INHOMECURR = 'INHOMECURR';

    const COLUMN_INSOURCURR = 'INSOURCURR';

    const COLUMN_SALESPER1  = 'SALESPER1';

    const COLUMN_INVNUMBER  = 'INVNUMBER';

    const COLUMN_RTGAMOUNT  = 'RTGAMOUNT';

    /**
     * Get ID
     *
     * @return mixed
     */
    public function getId();

    /**
     * Set Updated At
     *
     * @param string $updatedAt
     *
     * @return void
     */
    public function setUpdatedAt(string $updatedAt);

    /**
     * Get Invoice Uniquifier - INVUNIQ
     *
     * @return int
     */
    public function getInvoiceUniquifier();

    /**
     * Set Invoice Uniquifier - INVUNIQ
     *
     * @param int $invoiceUniquifier
     *
     * @return void
     */
    public function setInvoiceUniquifier(int $invoiceUniquifier);

    /**
     * Get Order Number - ORDNUMBER
     *
     * @return string
     */
    public function getOrderNumber();

    /**
     * Set Order Number - ORDNUMBER
     *
     * @param string $orderNumber
     *
     * @return void
     */
    public function setOrderNumber(string $orderNumber);

    /**
     * Get Customer Number - CUSTOMER
     *
     * @return string
     */
    public function getCustomerNumber();

    /**
     * Set Customer Number - CUSTOMER
     *
     * @param string $customerNumber
     *
     * @return void
     */
    public function setCustomerNumber(string $customerNumber);

    /**
     * Get Bill-To Name - BILNAME
     *
     * @return string
     */
    public function getBillToName();

    /**
     * Set Bill-To Name - BILNAME
     *
     * @param string $billToName
     *
     * @return void
     */
    public function setBillToName(string $billToName);

    /**
     * Get Bill-To Address Line 1 - BILADDR1
     *
     * @return string
     */
    public function getBillToAddressLine1();

    /**
     * Set Bill-To Address Line 1 - BILADDR1
     *
     * @param string $billToAddressLine1
     *
     * @return void
     */
    public function setBillToAddressLine1(string $billToAddressLine1);

    /**
     * Get Bill-To Address Line 2 - BILADDR2
     *
     * @return string
     */
    public function getBillToAddressLine2();

    /**
     * Set Bill-To Address Line 2 - BILADDR2
     *
     * @param string $billToAddressLine2
     *
     * @return void
     */
    public function setBillToAddressLine2(string $billToAddressLine2);

    /**
     * Get Bill-To Address Line 3 - BILADDR3
     *
     * @return string
     */
    public function getBillToAddressLine3();

    /**
     * Set Bill-To Address Line 3 - BILADDR3
     *
     * @param string $billToAddressLine3
     *
     * @return void
     */
    public function setBillToAddressLine3(string $billToAddressLine3);

    /**
     * Get Bill-To Address Line 4 - BILADDR4
     *
     * @return string
     */
    public function getBillToAddressLine4();

    /**
     * Set Bill-To Address Line 4 - BILADDR4
     *
     * @param string $billToAddressLine4
     *
     * @return void
     */
    public function setBillToAddressLine4(string $billToAddressLine4);

    /**
     * Get Bill-To City - BILCITY
     *
     * @return string
     */
    public function getBillToCity();

    /**
     * Set Bill-To City - BILCITY
     *
     * @param string $billToCity
     *
     * @return void
     */
    public function setBillToCity(string $billToCity);

    /**
     * Get Bill-To State - BILSTATE
     *
     * @return string
     */
    public function getBillToState();

    /**
     * Set Bill-To State - BILSTATE
     *
     * @param string $billToState
     *
     * @return void
     */
    public function setBillToState(string $billToState);

    /**
     * Get Bill-To Zip Code - BILZIP
     *
     * @return string
     */
    public function getBillToZipCode();

    /**
     * Set Bill-To Zip Code - BILZIP
     *
     * @param string $billToZipCode
     *
     * @return void
     */
    public function setBillToZipCode(string $billToZipCode);

    /**
     * Get Bill-To Country - BILCOUNTRY
     *
     * @return string
     */
    public function getBillToCountry();

    /**
     * Set Bill-To Country - BILCOUNTRY
     *
     * @param string $billToCountry
     *
     * @return void
     */
    public function setBillToCountry(string $billToCountry);

    /**
     * Get Ship-To Name - SHPNAME
     *
     * @return string
     */
    public function getShipToName();

    /**
     * Set Ship-To Name - SHPNAME
     *
     * @param string $shipToName
     *
     * @return void
     */
    public function setShipToName(string $shipToName);

    /**
     * Get Ship-To Address Line 1 - SHPADDR1
     *
     * @return string
     */
    public function getShipToAddressLine1();

    /**
     * Set Ship-To Address Line 1 - SHPADDR1
     *
     * @param string $shipToAddressLine1
     *
     * @return void
     */
    public function setShipToAddressLine1(string $shipToAddressLine1);

    /**
     * Get Ship-To Address Line 2 - SHPADDR2
     *
     * @return string
     */
    public function getShipToAddressLine2();

    /**
     * Set Ship-To Address Line 2 - SHPADDR2
     *
     * @param string $shipToAddressLine2
     *
     * @return void
     */
    public function setShipToAddressLine2(string $shipToAddressLine2);

    /**
     * Get Ship-To Address Line 3 - SHPADDR3
     *
     * @return string
     */
    public function getShipToAddressLine3();

    /**
     * Set Ship-To Address Line 3 - SHPADDR3
     *
     * @param string $shipToAddressLine3
     *
     * @return void
     */
    public function setShipToAddressLine3(string $shipToAddressLine3);

    /**
     * Get Ship-To Address Line 4 - SHPADDR4
     *
     * @return string
     */
    public function getShipToAddressLine4();

    /**
     * Set Ship-To Address Line 4 - SHPADDR4
     *
     * @param string $shipToAddressLine4
     *
     * @return void
     */
    public function setShipToAddressLine4(string $shipToAddressLine4);

    /**
     * Get Ship-To City - SHPCITY
     *
     * @return string
     */
    public function getShipToCity();

    /**
     * Set Ship-To City - SHPCITY
     *
     * @param string $shipToCity
     *
     * @return void
     */
    public function setShipToCity(string $shipToCity);

    /**
     * Get Ship-To State - SHPSTATE
     *
     * @return string
     */
    public function getShipToState();

    /**
     * Set Ship-To State - SHPSTATE
     *
     * @param string $shipToState
     *
     * @return void
     */
    public function setShipToState(string $shipToState);

    /**
     * Get Ship-To Zip Code - SHPZIP
     *
     * @return string
     */
    public function getShipToZipCode();

    /**
     * Set Ship-To Zip Code - SHPZIP
     *
     * @param string $shipToZipCode
     *
     * @return void
     */
    public function setShipToZipCode(string $shipToZipCode);

    /**
     * Get Ship-To Country
     *
     * @return string
     */
    public function getShipToCountry();

    /**
     * Set Ship-To Country
     *
     * @param string $shipToCountry
     *
     * @return void
     */
    public function setShipToCountry(string $shipToCountry);

    /**
     * Get Purchase Order Number - PONUMBER
     *
     * @return string
     */
    public function getPurchaseOrderNumber();

    /**
     * Set Purchase Order Number - PONUMBER
     *
     * @param string $purchaseOrderNumber
     *
     * @return void
     */
    public function setPurchaseOrderNumber(string $purchaseOrderNumber);

    /**
     * Get Terms Code - TERMS
     *
     * @return string
     */
    public function getTermsCode();

    /**
     * Set Terms Code - TERMS
     *
     * @param string $termsCode
     *
     * @return void
     */
    public function setTermsCode(string $termsCode);

    /**
     * Get Order Date - ORDDATE
     *
     * @return int
     */
    public function getOrderDate();

    /**
     * Set Order Date - ORDDATE
     *
     * @param int $orderDate
     *
     * @return void
     */
    public function setOrderDate(int $orderDate);

    /**
     * Get Ship-Via Code - SHIPVIA
     *
     * @return string
     */
    public function getShipViaCode();

    /**
     * Set Ship-Via Code - SHIPVIA
     *
     * @param string $shipViaCode
     *
     * @return void
     */
    public function setShipViaCode(string $shipViaCode);

    /**
     * Get Invoice Date - INVDATE
     *
     * @return int
     */
    public function getInvoiceDate();

    /**
     * Set Invoice Date - INVDATE
     *
     * @param int $invoiceDate
     *
     * @return void
     */
    public function setInvoiceDate(int $invoiceDate);

    /**
     * Get Invoice Total Before Tax - INVNETNOTX
     *
     * @return float
     */
    public function getInvoiceTotalBeforeTax();

    /**
     * Set Invoice Total Before Tax - INVNETNOTX
     *
     * @param float $invoiceTotalBeforeTax
     *
     * @return void
     */
    public function setInvoiceTotalBeforeTax(float $invoiceTotalBeforeTax);

    /**
     * Set Invoice Total Tax Amount - INVITAXTOT
     *
     * @return float
     */
    public function getInvoiceTotalTaxAmount();

    /**
     * Set Invoice Total Tax Amount - INVITAXTOT
     *
     * @param float $invoiceTotalTaxAmount
     *
     * @return void
     */
    public function setInvoiceTotalTaxAmount(float $invoiceTotalTaxAmount);

    /**
     * Get Invoice Subtotal Amount - INVSUBTOT
     *
     * @return float
     */
    public function getInvoiceSubtotalAmount();

    /**
     * Set Invoice Subtotal Amount - INVSUBTOT
     *
     * @param float $invoiceSubtotalAmount
     *
     * @return void
     */
    public function setInvoiceSubtotalAmount(float $invoiceSubtotalAmount);

    /**
     * Get Invoice Total With Tax - INVNETWTX
     *
     * @return float
     */
    public function getInvoiceTotalWithTax();

    /**
     * Set Invoice Total With Tax - INVNETWTX
     *
     * @param float $invoiceTotalWithTax
     *
     * @return void
     */
    public function setInvoiceTotalWithTax(float $invoiceTotalWithTax);

    /**
     * Get Invoice Home Currency - INHOMECURR
     *
     * @return string
     */
    public function getInvoiceHomeCurrency();

    /**
     * Set Invoice Home Currency - INHOMECURR
     *
     * @param string $invoiceHomeCurrency
     *
     * @return void
     */
    public function setInvoiceHomeCurrency(string $invoiceHomeCurrency);

    /**
     * Get Invoice Source Currency - INSOURCURR
     *
     * @return string
     */
    public function getInvoiceSourceCurrency();

    /**
     * Set Invoice Source Currency - INSOURCURR
     *
     * @param string $invoiceSourceCurrency
     *
     * @return void
     */
    public function setInvoiceSourceCurrency(string $invoiceSourceCurrency);

    /**
     * Get Salesperson 1 - SALESPER1
     *
     * @return string
     */
    public function getSalesperson1();

    /**
     * Set Salesperson 1 - SALESPER1
     *
     * @param string $salesperson1
     *
     * @return void
     */
    public function setSalesperson1(string $salesperson1);

    /**
     * Get Invoice Number - INVNUMBER
     *
     * @return string
     */
    public function getInvoiceNumber();

    /**
     * Set Invoice Number - INVNUMBER
     *
     * @param string $invoiceNumber
     *
     * @return void
     */
    public function setInvoiceNumber(string $invoiceNumber);

    /**
     * Get Retainage Amount - RTGAMOUNT
     *
     * @return float
     */
    public function getRetainageAmount();

    /**
     * Set Retainage Amount - RTGAMOUNT
     *
     * @param float $retainageAmount
     *
     * @return void
     */
    public function setRetainageAmount(float $retainageAmount);

    /**
     * Get Invoice Details
     *
     * @return \ECInternet\Sage300Account\Api\Data\OeinvdInterface[]
     */
    public function getInvoiceDetails();

    /**
     * Get Total Due
     *
     * @return float
     */
    public function getTotalDue();

    /**
     * Is fully paid?
     *
     * @return bool
     */
    public function isFullyPaid();

    /**
     * Get remaining balance
     *
     * @return float
     */
    public function getRemainingBalance();

    /**
     * Get max payment
     *
     * @return float
     */
    public function getMaxPayment();

    /**
     * Get AR Payment Total
     *
     * @return float
     */
    public function getARPaymentTotal();
}
