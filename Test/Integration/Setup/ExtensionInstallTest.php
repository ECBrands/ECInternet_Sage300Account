<?php
/**
 * Copyright (C) EC Brands Corporation - All Rights Reserved
 * Contact Licensing@ECInternet.com for use guidelines
 */
declare(strict_types=1);

namespace ECInternet\Sage300Account\Test\Integration\Setup;

use Magento\Eav\Model\Config as EavConfig;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\TestFramework\Helper\Bootstrap;
use Exception;
use PHPUnit\Framework\TestCase;

class ExtensionInstallTest extends TestCase
{
    /**
     * @var \Magento\Eav\Model\Config
     */
    private $eavConfig;

    /**
     * @var \Magento\Framework\App\ResourceConnection
     */
    private $resourceConnection;

    protected function setUp(): void
    {
        $objectManager            = Bootstrap::getObjectManager();
        $this->eavConfig          = $objectManager->get(EavConfig::class);
        $this->resourceConnection = $objectManager->get(ResourceConnection::class);
    }

    // -------------------------------------------------------------------------
    // Product EAV attributes
    // -------------------------------------------------------------------------

    public function testProductAttributeUomWasCreatedCorrectly(): void
    {
        $attribute = $this->getAttribute('catalog_product', 'uom');
        if ($attribute === null) {
            $this->fail('Product attribute "uom" does not exist.');
        }

        $this->assertEquals('varchar', $attribute->getBackendType());
        $this->assertEquals('UOM', $attribute->getStoreLabel());
        $this->assertEquals(0, $attribute->getIsRequired());
        $this->assertEquals(1, $attribute->getData('is_visible'));
        $this->assertEquals(0, $attribute->getIsUserDefined());
        $this->assertEquals(0, $attribute->getIsUnique());
        $this->assertEquals(0, $attribute->getData('is_filterable'));
    }

    // -------------------------------------------------------------------------
    // Flat table columns (UpgradeData)
    // -------------------------------------------------------------------------

    public function testQuoteItemColumnsWereCreated(): void
    {
        $connection = $this->getConnection();
        $table      = $this->resourceConnection->getTableName('quote_item');

        $this->assertTrue($connection->tableColumnExists($table, 'invoice_docnumber'), 'quote_item.invoice_docnumber column should exist.');
        $this->assertTrue($connection->tableColumnExists($table, 'uom'),               'quote_item.uom column should exist.');
    }

    public function testOrderItemColumnsWereCreated(): void
    {
        $connection = $this->getConnection();
        $table      = $this->resourceConnection->getTableName('sales_order_item');

        $this->assertTrue($connection->tableColumnExists($table, 'invoice_docnumber'), 'sales_order_item.invoice_docnumber column should exist.');
        $this->assertTrue($connection->tableColumnExists($table, 'uom'),               'sales_order_item.uom column should exist.');
    }

    public function testSalesOrderColumnsWereCreated(): void
    {
        $connection = $this->getConnection();
        $table      = $this->resourceConnection->getTableName('sales_order');

        $this->assertTrue($connection->tableColumnExists($table, 'is_invoice_payment'), 'sales_order.is_invoice_payment column should exist.');
    }

    // -------------------------------------------------------------------------
    // Custom tables (db_schema.xml)
    // -------------------------------------------------------------------------

    public function testAroblTableWasCreated(): void
    {
        $connection = $this->getConnection();
        $table      = $this->resourceConnection->getTableName('ecinternet_sage300account_arobl');

        $this->assertTrue($connection->isTableExists($table), 'ecinternet_sage300account_arobl table should exist.');
        $this->assertTrue($connection->tableColumnExists($table, 'entity_id'), 'arobl.entity_id column should exist.');
        $this->assertTrue($connection->tableColumnExists($table, 'IDCUST'),    'arobl.IDCUST column should exist.');
        $this->assertTrue($connection->tableColumnExists($table, 'IDINVC'),    'arobl.IDINVC column should exist.');
        $this->assertTrue($connection->tableColumnExists($table, 'AMTDUEHC'), 'arobl.AMTDUEHC column should exist.');
        $this->assertTrue($connection->tableColumnExists($table, 'SWPAID'),    'arobl.SWPAID column should exist.');
    }

    public function testArtcpTableWasCreated(): void
    {
        $connection = $this->getConnection();
        $table      = $this->resourceConnection->getTableName('ecinternet_sage300account_artcp');

        $this->assertTrue($connection->isTableExists($table), 'ecinternet_sage300account_artcp table should exist.');
        $this->assertTrue($connection->tableColumnExists($table, 'entity_id'), 'artcp.entity_id column should exist.');
        $this->assertTrue($connection->tableColumnExists($table, 'CODEPAYM'),  'artcp.CODEPAYM column should exist.');
        $this->assertTrue($connection->tableColumnExists($table, 'IDINVC'),    'artcp.IDINVC column should exist.');
        $this->assertTrue($connection->tableColumnExists($table, 'AMTPAYM'),   'artcp.AMTPAYM column should exist.');
    }

    public function testOeinvdTableWasCreated(): void
    {
        $connection = $this->getConnection();
        $table      = $this->resourceConnection->getTableName('ecinternet_sage300account_oeinvd');

        $this->assertTrue($connection->isTableExists($table), 'ecinternet_sage300account_oeinvd table should exist.');
        $this->assertTrue($connection->tableColumnExists($table, 'entity_id'), 'oeinvd.entity_id column should exist.');
        $this->assertTrue($connection->tableColumnExists($table, 'INVUNIQ'),   'oeinvd.INVUNIQ column should exist.');
        $this->assertTrue($connection->tableColumnExists($table, 'ITEM'),      'oeinvd.ITEM column should exist.');
        $this->assertTrue($connection->tableColumnExists($table, 'UNITPRICE'), 'oeinvd.UNITPRICE column should exist.');
    }

    public function testOeinvhTableWasCreated(): void
    {
        $connection = $this->getConnection();
        $table      = $this->resourceConnection->getTableName('ecinternet_sage300account_oeinvh');

        $this->assertTrue($connection->isTableExists($table), 'ecinternet_sage300account_oeinvh table should exist.');
        $this->assertTrue($connection->tableColumnExists($table, 'entity_id'), 'oeinvh.entity_id column should exist.');
        $this->assertTrue($connection->tableColumnExists($table, 'INVUNIQ'),   'oeinvh.INVUNIQ column should exist.');
        $this->assertTrue($connection->tableColumnExists($table, 'CUSTOMER'),  'oeinvh.CUSTOMER column should exist.');
        $this->assertTrue($connection->tableColumnExists($table, 'INVNUMBER'), 'oeinvh.INVNUMBER column should exist.');
    }

    public function testOeorddTableWasCreated(): void
    {
        $connection = $this->getConnection();
        $table      = $this->resourceConnection->getTableName('ecinternet_sage300account_oeordd');

        $this->assertTrue($connection->isTableExists($table), 'ecinternet_sage300account_oeordd table should exist.');
        $this->assertTrue($connection->tableColumnExists($table, 'entity_id'), 'oeordd.entity_id column should exist.');
        $this->assertTrue($connection->tableColumnExists($table, 'ORDUNIQ'),   'oeordd.ORDUNIQ column should exist.');
        $this->assertTrue($connection->tableColumnExists($table, 'ITEM'),      'oeordd.ITEM column should exist.');
        $this->assertTrue($connection->tableColumnExists($table, 'UNITPRICE'), 'oeordd.UNITPRICE column should exist.');
    }

    public function testOeordhTableWasCreated(): void
    {
        $connection = $this->getConnection();
        $table      = $this->resourceConnection->getTableName('ecinternet_sage300account_oeordh');

        $this->assertTrue($connection->isTableExists($table), 'ecinternet_sage300account_oeordh table should exist.');
        $this->assertTrue($connection->tableColumnExists($table, 'entity_id'), 'oeordh.entity_id column should exist.');
        $this->assertTrue($connection->tableColumnExists($table, 'ORDUNIQ'),   'oeordh.ORDUNIQ column should exist.');
        $this->assertTrue($connection->tableColumnExists($table, 'CUSTOMER'),  'oeordh.CUSTOMER column should exist.');
        $this->assertTrue($connection->tableColumnExists($table, 'ORDNUMBER'), 'oeordh.ORDNUMBER column should exist.');
    }

    public function testOeppreTableWasCreated(): void
    {
        $connection = $this->getConnection();
        $table      = $this->resourceConnection->getTableName('ecinternet_sage300account_oeppre');

        $this->assertTrue($connection->isTableExists($table), 'ecinternet_sage300account_oeppre table should exist.');
        $this->assertTrue($connection->tableColumnExists($table, 'entity_id'), 'oeppre.entity_id column should exist.');
        $this->assertTrue($connection->tableColumnExists($table, 'DOCNUMBER'), 'oeppre.DOCNUMBER column should exist.');
        $this->assertTrue($connection->tableColumnExists($table, 'PAYMENT'),   'oeppre.PAYMENT column should exist.');
    }

    public function testOeshdtTableWasCreated(): void
    {
        $connection = $this->getConnection();
        $table      = $this->resourceConnection->getTableName('ecinternet_sage300account_oeshdt');

        $this->assertTrue($connection->isTableExists($table), 'ecinternet_sage300account_oeshdt table should exist.');
        $this->assertTrue($connection->tableColumnExists($table, 'entity_id'), 'oeshdt.entity_id column should exist.');
        $this->assertTrue($connection->tableColumnExists($table, 'CUSTOMER'),  'oeshdt.CUSTOMER column should exist.');
        $this->assertTrue($connection->tableColumnExists($table, 'ITEM'),      'oeshdt.ITEM column should exist.');
        $this->assertTrue($connection->tableColumnExists($table, 'SHIPDATE'),  'oeshdt.SHIPDATE column should exist.');
    }

    public function testOetermiTableWasCreated(): void
    {
        $connection = $this->getConnection();
        $table      = $this->resourceConnection->getTableName('ecinternet_sage300account_oetermi');

        $this->assertTrue($connection->isTableExists($table), 'ecinternet_sage300account_oetermi table should exist.');
        $this->assertTrue($connection->tableColumnExists($table, 'entity_id'), 'oetermi.entity_id column should exist.');
        $this->assertTrue($connection->tableColumnExists($table, 'INVUNIQ'),   'oetermi.INVUNIQ column should exist.');
        $this->assertTrue($connection->tableColumnExists($table, 'DUEDATE'),   'oetermi.DUEDATE column should exist.');
        $this->assertTrue($connection->tableColumnExists($table, 'DISCAMT'),   'oetermi.DISCAMT column should exist.');
    }

    public function testPoporhTableWasCreated(): void
    {
        $connection = $this->getConnection();
        $table      = $this->resourceConnection->getTableName('ecinternet_sage300account_poporh');

        $this->assertTrue($connection->isTableExists($table), 'ecinternet_sage300account_poporh table should exist.');
        $this->assertTrue($connection->tableColumnExists($table, 'entity_id'), 'poporh.entity_id column should exist.');
        $this->assertTrue($connection->tableColumnExists($table, 'PORHSEQ'),   'poporh.PORHSEQ column should exist.');
        $this->assertTrue($connection->tableColumnExists($table, 'PONUMBER'),  'poporh.PONUMBER column should exist.');
        $this->assertTrue($connection->tableColumnExists($table, 'VDCODE'),    'poporh.VDCODE column should exist.');
    }

    public function testPoporlTableWasCreated(): void
    {
        $connection = $this->getConnection();
        $table      = $this->resourceConnection->getTableName('ecinternet_sage300account_poporl');

        $this->assertTrue($connection->isTableExists($table), 'ecinternet_sage300account_poporl table should exist.');
        $this->assertTrue($connection->tableColumnExists($table, 'entity_id'),   'poporl.entity_id column should exist.');
        $this->assertTrue($connection->tableColumnExists($table, 'PORHSEQ'),     'poporl.PORHSEQ column should exist.');
        $this->assertTrue($connection->tableColumnExists($table, 'ITEMNO'),      'poporl.ITEMNO column should exist.');
        $this->assertTrue($connection->tableColumnExists($table, 'OQORDERED'),   'poporl.OQORDERED column should exist.');
        $this->assertTrue($connection->tableColumnExists($table, 'OQRECEIVED'),  'poporl.OQRECEIVED column should exist.');
    }

    public function testPoporldTableWasCreated(): void
    {
        $connection = $this->getConnection();
        $table      = $this->resourceConnection->getTableName('ecinternet_sage300account_poporlo');

        $this->assertTrue($connection->isTableExists($table), 'ecinternet_sage300account_poporlo table should exist.');
        $this->assertTrue($connection->tableColumnExists($table, 'entity_id'), 'poporlo.entity_id column should exist.');
        $this->assertTrue($connection->tableColumnExists($table, 'PORHSEQ'),   'poporlo.PORHSEQ column should exist.');
        $this->assertTrue($connection->tableColumnExists($table, 'PORLREV'),   'poporlo.PORLREV column should exist.');
        $this->assertTrue($connection->tableColumnExists($table, 'OPTFIELD'),  'poporlo.OPTFIELD column should exist.');
        $this->assertTrue($connection->tableColumnExists($table, 'VALUE'),     'poporlo.VALUE column should exist.');
    }

    public function testUomTableWasCreated(): void
    {
        $connection = $this->getConnection();
        $table      = $this->resourceConnection->getTableName('ecinternet_sage300account_uom');

        $this->assertTrue($connection->isTableExists($table), 'ecinternet_sage300account_uom table should exist.');
        $this->assertTrue($connection->tableColumnExists($table, 'entity_id'), 'uom.entity_id column should exist.');
        $this->assertTrue($connection->tableColumnExists($table, 'ITEMNO'),    'uom.ITEMNO column should exist.');
        $this->assertTrue($connection->tableColumnExists($table, 'PRICELIST'), 'uom.PRICELIST column should exist.');
        $this->assertTrue($connection->tableColumnExists($table, 'UNIT'),      'uom.UNIT column should exist.');
        $this->assertTrue($connection->tableColumnExists($table, 'DEFCONV'),   'uom.DEFCONV column should exist.');
    }

    // -------------------------------------------------------------------------
    // Order status data (UpgradeData)
    // -------------------------------------------------------------------------

    public function testOrderStatusesWereCreated(): void
    {
        $connection = $this->getConnection();
        $table      = $this->resourceConnection->getTableName('sales_order_status');

        foreach (['invoice_payment' => 'Invoice Payment', 'invoice_payment_complete' => 'Completed Invoice Payment'] as $code => $label) {
            $select = $connection->select()->from($table)->where('status = ?', $code);
            $row    = $connection->fetchRow($select);

            $this->assertNotEmpty($row, "Order status \"$code\" should exist.");
            $this->assertEquals($label, $row['label'], "Order status \"$code\" should have label \"$label\".");
        }
    }

    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------

    private function getAttribute(string $entityTypeCode, string $attributeCode)
    {
        try {
            if ($attribute = $this->eavConfig->getAttribute($entityTypeCode, $attributeCode)) {
                if ($attribute->getAttributeId()) {
                    return $attribute;
                }
            }
        } catch (Exception) {
        }

        return null;
    }

    private function getConnection(): AdapterInterface
    {
        return $this->resourceConnection->getConnection();
    }
}
