# Magento2 Module ECInternet_Sage300Account
``ecinternet_sage300account - 1.6.9.0``

- [Requirements](#requirements-header)
- [Overview](#overview-header)
- [Installation](#installation-header)
- [Configuration](#configuration-header)
- [Design Modifications](#design-modifications-header)
- [Specifications](#specifications-header)
- [Attributes](#attributes-header)
- [Notes](#notes-header)
- [Version History](#version-history-header)

## Requirements

## Overview
Sage300Account module adds Sage invoice and order history information to the Magento 2 backend for viewing historical invoices and orders.

## Installation
- Unzip the zip file in `app/code/ECInternet`
- Enable the module by running `php bin/magento module:enable ECInternet_Sage300Account`
- Apply database updates by running `php bin/magento setup:upgrade`
- Recompile code by running `php bin/magento setup:di:compile`
- Flush the cache by running `php bin/magento cache:flush`

## Configuration

## Specifications
### Data Changes
#### order
- `is_invoice_payment`
- `uom`
#### order_item
- `invoice_docnumber`
#### sales_order_status
- `invoice_payment_complete`
#### quote_item
- `invoice_docnumber`
- `uom`

## Attributes
#### Product
- `uom`

## Features

## Notes
- Adding an invoice payment to the cart will empty the existing cart.
- Leaving checkout with an invoice payment in your cart will also cause the Customer's cart to be emptied.

### Reorder Custom Products - Product Collection Query
```
SELECT
	cpe.`entity_id`
	,cpe.`sku`
	,csi.`qty_increments`
	,oeshdt.`is_active`
	,oeshdt.`CUSTOMER`
	,oeshdt.`SHIPDATE`
FROM
	catalog_product_entity cpe

LEFT OUTER JOIN
	cataloginventory_stock_item csi
ON
	cpe.`entity_id` = csi.`product_id`

LEFT OUTER JOIN
	ecinternet_sage300account_oeshdt oeshdt
ON
	cpe.`sku` = oeshdt.`ITEM`

WHERE
	csi.`product_id` IS NOT NULL
	AND csi.`qty_increments` > 0
	AND oeshdt.`ITEM` IS NOT NULL

ORDER BY
	oeshdt.`CUSTOMER`, cpe.`sku`
```

#### Reorder Custom Products - Requests
- Add 'uom'
- Add "Last purchased date"
- Add +/- to quantity box
#### Reorder Custom Products - Issues
- Requesting quantity which does not exist redirects user to product page.  We want to block this because these products will have visiliby = 1 so product isn't viewable.

## Known Issues

## Version History
- 1.6.9.0 - Customer Account sidenav display now respect store id.
- 1.6.4.0 - Fix calculation of open invoice amount.  Fix calculation of extended price amount for invoice line items. 
