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
### Database Changes
#### Sales Order
- `is_invoice_payment`
- `uom`
#### Sales Order Status
- `invoice_payment_complete`

## Attributes
#### Catalog Product
- `invoice_docnumber`
- `uom`
### Quote Item
- `invoice_docnumber`
- `uom`



## Features
### Events
`sales_order_place_after`
- Checks if order is invoice payment, and if so, sets order status and status to `invoice_payment`, and sets `is_invoice_payment` to `1`.

`sales_quote_product_add_after`
- Sets `uom` on quote item.

`layout_generate_blocks_after` (frontend)
- Removes sidenav links from Customer Account page.

### Plugins
`Magento\Catalog\Block\Product\ListProduct`
- Adds uom template to product list display.

`Magento\CatalogSearch\Block\Result`
- Redirects the Customer to the custom 'reorder' page if they are searching for a product they've previously purchased.

`Magento\Framework\View\Result\Layout`
- Empties the Customer's cart if they have an invoice payment in their cart, but they navigate away from the checkout page.

`Magento\Quote\Model\Quote\Item\ToOrderItem.convert()`
- Extracts `invoice_docnumber` and `uom` from quote item and set them on order item.

`Magento\Sales\Api\OrderRepositoryInterfacePlugin.get()`
- Sets `is_invoice_payment` and `uom` on order item for API response.



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
