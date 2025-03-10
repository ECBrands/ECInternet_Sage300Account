<?php
/**
 * Copyright (C) EC Brands Corporation - All Rights Reserved
 * Contact Licensing@ECInternet.com for use guidelines
 */
declare(strict_types=1);

namespace ECInternet\Sage300Account\Api\Data;

interface OeordhInterface
{
    public const COLUMN_ID         = 'entity_id';

    public const COLUMN_UPDATED_AT = 'updated_at';

    public const COLUMN_ORDUNIQ    = 'ORDUNIQ';

    public const COLUMN_ORDNUMBER  = 'ORDNUMBER';

    public const COLUMN_CUSTOMER   = 'CUSTOMER';

    public const COLUMN_BILNAME    = 'BILNAME';

    public const COLUMN_BILADDR1   = 'BILADDR1';

    public const COLUMN_BILADDR2   = 'BILADDR2';

    public const COLUMN_BILADDR3   = 'BILADDR3';

    public const COLUMN_BILADDR4   = 'BILADDR4';

    public const COLUMN_BILADDR5   = 'BILADDR5';

    public const COLUMN_BILCITY    = 'BILCITY';

    public const COLUMN_BILSTATE   = 'BILSTATE';

    public const COLUMN_BILZIP     = 'BILZIP';

    public const COLUMN_BILCOUNTRY = 'BILCOUNTRY';

    public const COLUMN_SHPNAME    = 'SHPNAME';

    public const COLUMN_SHPADDR1   = 'SHPADDR1';

    public const COLUMN_SHPADDR2   = 'SHPADDR2';

    public const COLUMN_SHPADDR3   = 'SHPADDR3';

    public const COLUMN_SHPADDR4   = 'SHPADDR4';

    public const COLUMN_SHPADDR5   = 'SHPADDR5';

    public const COLUMN_SHPCITY    = 'SHPCITY';

    public const COLUMN_SHPSTATE   = 'SHPSTATE';

    public const COLUMN_SHPZIP     = 'SHPZIP';

    public const COLUMN_SHPCOUNTRY = 'SHPCOUNTRY';

    public const COLUMN_PONUMBER   = 'PONUMBER';

    public const COLUMN_TERMS      = 'TERMS';

    public const COLUMN_REFERENCE  = 'REFERENCE';

    public const COLUMN_ORDDATE    = 'ORDDATE';

    public const COLUMN_SHIPVIA    = 'SHIPVIA';

    public const COLUMN_COMMENT    = 'COMMENT';

    public const COLUMN_SALESPER1  = 'SALESPER1';

    public const COLUMN_TAUTH1     = 'TAUTH1';

    public const COLUMN_TAUTH2     = 'TAUTH2';

    public const COLUMN_TAUTH3     = 'TAUTH3';

    public const COLUMN_TAUTH4     = 'TAUTH4';

    public const COLUMN_TAUTH5     = 'TAUTH5';

    public const COLUMN_TEAMOUNT1  = 'TEAMOUNT1';

    public const COLUMN_TEAMOUNT2  = 'TEAMOUNT2';

    public const COLUMN_TEAMOUNT3  = 'TEAMOUNT3';

    public const COLUMN_TEAMOUNT4  = 'TEAMOUNT4';

    public const COLUMN_TEAMOUNT5  = 'TEAMOUNT5';

    public const COLUMN_TIAMOUNT1  = 'TEAMOUNT1';

    public const COLUMN_TIAMOUNT2  = 'TIAMOUNT2';

    public const COLUMN_TIAMOUNT3  = 'TIAMOUNT3';

    public const COLUMN_TIAMOUNT4  = 'TIAMOUNT4';

    public const COLUMN_TIAMOUNT5  = 'TIAMOUNT5';

    public const COLUMN_INVITAXTOT = 'INVITAXTOT';

    public const COLUMN_INVDISCAMT = 'INVDISCAMT';

    public const COLUMN_INVSUBTOT  = 'INVSUBTOT';

    public const COLUMN_INVNETWTX  = 'INVNETWTX';

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
     * Get Order Uniquifier - ORDUNIQ
     *
     * @return int
     */
    public function getOrderUniquifier();

    /**
     * Set Order Uniquifier - ORDUNIQ
     *
     * @param int $orderUniquifier
     *
     * @return void
     */
    public function setOrderUniquifier(int $orderUniquifier);

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
     * @return void
     */
    public function setBillToCountry(string $billToCountry);

    /**
     * Get Ship-To Name
     *
     * @return string
     */
    public function getShipToName();

    /**
     * Set Ship-To Name
     *
     * @param string $shipToName
     * @return void
     */
    public function setShipToName(string $shipToName);

    /**
     * Get Ship-To Address Line 1
     *
     * @return string
     */
    public function getShipToAddressLine1();

    /**
     * Set Ship-To Address Line 1
     *
     * @param string $shipToAddressLine1
     * @return void
     */
    public function setShipToAddressLine1(string $shipToAddressLine1);

    /**
     * Get Ship-To Address Line 2
     *
     * @return string
     */
    public function getShipToAddressLine2();

    /**
     * Set Ship-To Address Line 2
     *
     * @param string $shipToAddressLine2
     * @return void
     */
    public function setShipToAddressLine2(string $shipToAddressLine2);

    /**
     * Get Ship-To Address Line 3
     *
     * @return string
     */
    public function getShipToAddressLine3();

    /**
     * Set Ship-To Address Line 3
     *
     * @param string $shipToAddressLine3
     * @return void
     */
    public function setShipToAddressLine3(string $shipToAddressLine3);

    /**
     * Get Ship-To Address Line 4
     *
     * @return string
     */
    public function getShipToAddressLine4();

    /**
     * Set Ship-To Address Line 4
     *
     * @param string $shipToAddressLine4
     * @return void
     */
    public function setShipToAddressLine4(string $shipToAddressLine4);

    /**
     * Get Ship-To Address Line 5
     *
     * @return string
     */
    public function getShipToAddressLine5();

    /**
     * Set Ship-To Address Line 5
     *
     * @param string $shipToAddressLine5
     * @return void
     */
    public function setShipToAddressLine5(string $shipToAddressLine5);

    /**
     * Get Ship-To City
     *
     * @return string
     */
    public function getShipToCity();

    /**
     * Set Ship-To City
     *
     * @param string $shipToCity
     * @return void
     */
    public function setShipToCity(string $shipToCity);

    /**
     * Get Ship-To State
     *
     * @return string
     */
    public function getShipToState();

    /**
     * Set Ship-To State
     *
     * @param string $shipToState
     * @return void
     */
    public function setShipToState(string $shipToState);

    /**
     * Get Ship-To Zip Code
     *
     * @return string
     */
    public function getShipToZipCode();

    /**
     * Set Ship-To Zip Code
     *
     * @param string $shipToZipCode
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
     * @return void
     */
    public function setShipToCountry(string $shipToCountry);

    /**
     * Get Purchase Order Number
     *
     * @return string
     */
    public function getPurchaseOrderNumber();

    /**
     * Set Purchase Order Number
     *
     * @param string $purchaseOrderNumber
     * @return void
     */
    public function setPurchaseOrderNumber(string $purchaseOrderNumber);

    /**
     * Get Terms Code
     *
     * @return string
     */
    public function getTermsCode();

    /**
     * Set Terms Code
     *
     * @param string $termsCode
     * @return void
     */
    public function setTermsCode(string $termsCode);

    /**
     * Get Order Reference
     *
     * @return string
     */
    public function getOrderReference();

    /**
     * Set Order Reference
     *
     * @param string $orderReference
     * @return void
     */
    public function setOrderReference(string $orderReference);

    /**
     * Get Order Date
     *
     * @return string
     */
    public function getOrderDate();

    /**
     * Set Order Date
     *
     * @param string $orderDate
     * @return void
     */
    public function setOrderDate(string $orderDate);

    /**
     * Get Ship-Via Code
     *
     * @return string
     */
    public function getShipViaCode();

    /**
     * Set Ship-Via Code
     *
     * @param string $shipViaCode
     * @return void
     */
    public function setShipViaCode(string $shipViaCode);

    /**
     * Get Order Comment
     *
     * @return string
     */
    public function getOrderComment();

    /**
     * Set Order Comment
     *
     * @param string $orderComment
     * @return void
     */
    public function setOrderComment(string $orderComment);

    /**
     * Get Salesperson 1
     *
     * @return string
     */
    public function getSalesperson1();

    /**
     * Set Salesperson 1
     *
     * @param string $salesperson1
     * @return void
     */
    public function setSalesperson1(string $salesperson1);

    /**
     * Get Tax Authority 1
     *
     * @return string
     */
    public function getTaxAuthority1();

    /**
     * Set Tax Authority 1
     *
     * @param string $taxAuthority1
     * @return void
     */
    public function setTaxAuthority1(string $taxAuthority1);

    /**
     * Get Tax Authority 2
     *
     * @return string
     */
    public function getTaxAuthority2();

    /**
     * Set Tax Authority 2
     *
     * @param string $taxAuthority2
     * @return void
     */
    public function setTaxAuthority2(string $taxAuthority2);

    /**
     * Get Tax Authority 3
     *
     * @return string
     */
    public function getTaxAuthority3();

    /**
     * Set Tax Authority 3
     *
     * @param string $taxAuthority3
     * @return void
     */
    public function setTaxAuthority3(string $taxAuthority3);

    /**
     * Get Tax Authority 4
     *
     * @return string
     */
    public function getTaxAuthority4();

    /**
     * Set Tax Authority 4
     *
     * @param string $taxAuthority4
     * @return void
     */
    public function setTaxAuthority4(string $taxAuthority4);

    /**
     * Get Tax Authority 5
     *
     * @return string
     */
    public function getTaxAuthority5();

    /**
     * Set Tax Authority 5
     *
     * @param string $taxAuthority5
     * @return void
     */
    public function setTaxAuthority5(string $taxAuthority5);

    /**
     * Get Excluded Tax Amount 1
     *
     * @return float
     */
    public function getExcludedTaxAmount1();

    /**
     * Set Excluded Tax Amount 1
     *
     * @param float $excludedTaxAmount1
     * @return void
     */
    public function setExcludedTaxAmount1(float $excludedTaxAmount1);

    /**
     * Get Excluded Tax Amount 2
     *
     * @return float
     */
    public function getExcludedTaxAmount2();

    /**
     * Set Excluded Tax Amount 2
     *
     * @param float $excludedTaxAmount2
     * @return void
     */
    public function setExcludedTaxAmount2(float $excludedTaxAmount2);

    /**
     * Get Excluded Tax Amount 3
     *
     * @return float
     */
    public function getExcludedTaxAmount3();

    /**
     * Set Excluded Tax Amount 3
     *
     * @param float $excludedTaxAmount3
     * @return void
     */
    public function setExcludedTaxAmount3(float $excludedTaxAmount3);

    /**
     * Get Excluded Tax Amount 4
     *
     * @return float
     */
    public function getExcludedTaxAmount4();

    /**
     * Set Excluded Tax Amount 4
     *
     * @param float $excludedTaxAmount4
     * @return void
     */
    public function setExcludedTaxAmount4(float $excludedTaxAmount4);

    /**
     * Get Excluded Tax Amount 5
     *
     * @return float
     */
    public function getExcludedTaxAmount5();

    /**
     * Set Excluded Tax Amount 5
     *
     * @param float $excludedTaxAmount5
     * @return void
     */
    public function setExcludedTaxAmount5(float $excludedTaxAmount5);

    /**
     * Get Included Tax Amount 1
     *
     * @return float
     */
    public function getIncludedTaxAmount1();

    /**
     * Set Included Tax Amount 1
     *
     * @param float $includedTaxAmount1
     * @return void
     */
    public function setIncludedTaxAmount1(float $includedTaxAmount1);

    /**
     * Get Included Tax Amount 2
     *
     * @return float
     */
    public function getIncludedTaxAmount2();

    /**
     * Set Included Tax Amount 2
     *
     * @param float $includedTaxAmount2
     * @return void
     */
    public function setIncludedTaxAmount2(float $includedTaxAmount2);

    /**
     * Get Included Tax Amount 3
     *
     * @return float
     */
    public function getIncludedTaxAmount3();

    /**
     * Set Included Tax Amount 3
     *
     * @param float $includedTaxAmount3
     * @return void
     */
    public function setIncludedTaxAmount3(float $includedTaxAmount3);

    /**
     * Get Included Tax Amount 4
     *
     * @return float
     */
    public function getIncludedTaxAmount4();

    /**
     * Set Included Tax Amount 4
     *
     * @param float $includedTaxAmount4
     * @return void
     */
    public function setIncludedTaxAmount4(float $includedTaxAmount4);

    /**
     * Get Included Tax Amount 5
     *
     * @return float
     */
    public function getIncludedTaxAmount5();

    /**
     * Set Included Tax Amount 5
     *
     * @param float $includedTaxAmount5
     * @return void
     */
    public function setIncludedTaxAmount5(float $includedTaxAmount5);

    /**
     * Get Order Total Including Tax (INVITAXTOT)
     *
     * @return float
     */
    public function getOrderTotalIncludingTax();

    /**
     * Set Order Total Including Tax
     *
     * @param float $orderTotalIncludingTax
     * @return void
     */
    public function setOrderTotalIncludingTax(float $orderTotalIncludingTax);

    /**
     * Get Order Discount Amount (INVDISCAMT)
     *
     * @return float
     */
    public function getOrderDiscountAmount();

    /**
     * Set Order Discount Amount
     *
     * @param float $orderDiscountAmount
     * @return void
     */
    public function setOrderDiscountAmount(float $orderDiscountAmount);

    /**
     * Get Order Subtotal Amount (INVSUBTOT)
     *
     * @return float
     */
    public function getOrderSubtotalAmount();

    /**
     * Set Order Subtotal Amount
     *
     * @param float $orderSubtotalAmount
     * @return void
     */
    public function setOrderSubtotalAmount(float $orderSubtotalAmount);

    /**
     * Get Order Total (INVNETWTX)
     *
     * @return float
     */
    public function getOrderTotal();

    /**
     * Set Order Total
     *
     * @param float $orderTotal
     * @return void
     */
    public function setOrderTotal(float $orderTotal);

    /**
     * Get Oeordd collection
     *
     * @return \ECInternet\Sage300Account\Api\Data\OeorddInterface[]
     */
    public function getOrderDetails();
}
