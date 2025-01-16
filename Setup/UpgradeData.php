<?php
/**
 * Copyright (C) EC Brands Corporation - All Rights Reserved
 * Contact Licensing@ECInternet.com for use guidelines
 */
declare(strict_types=1);

namespace ECInternet\Sage300Account\Setup;

use Magento\Catalog\Model\Product;
use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\UpgradeDataInterface;
use Magento\Quote\Setup\QuoteSetupFactory;
use Magento\Sales\Setup\SalesSetupFactory;

/**
 * Data upgrade script
 */
class UpgradeData implements UpgradeDataInterface
{
    /**
     * @var \Magento\Eav\Setup\EavSetupFactory
     */
    private $eavSetupFactory;

    /**
     * @var \Magento\Quote\Setup\QuoteSetupFactory
     */
    private $quoteSetupFactory;

    /**
     * @var \Magento\Sales\Setup\SalesSetupFactory
     */
    private $salesSetupFactory;

    /**
     * UpgradeData constructor.
     *
     * @param \Magento\Eav\Setup\EavSetupFactory     $eavSetupFactory
     * @param \Magento\Quote\Setup\QuoteSetupFactory $quoteSetupFactory
     * @param \Magento\Sales\Setup\SalesSetupFactory $salesSetupFactory
     */
    public function __construct(
        EavSetupFactory $eavSetupFactory,
        QuoteSetupFactory $quoteSetupFactory,
        SalesSetupFactory $salesSetupFactory
    ) {
        $this->eavSetupFactory   = $eavSetupFactory;
        $this->quoteSetupFactory = $quoteSetupFactory;
        $this->salesSetupFactory = $salesSetupFactory;
    }

    /**
     * Upgrade DB for a module
     *
     * @param \Magento\Framework\Setup\ModuleDataSetupInterface $setup
     * @param \Magento\Framework\Setup\ModuleContextInterface   $context
     *
     * @return void
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Zend_Validate_Exception
     */
    public function upgrade(
        ModuleDataSetupInterface $setup,
        ModuleContextInterface $context
    ) {
        $installer = $setup;
        $installer->startSetup();

        if (version_compare($context->getVersion(), '1.4.1', '<')) {
            /** @var \Magento\Quote\Setup\QuoteSetup $quoteSetup */
            $quoteSetup = $this->quoteSetupFactory->create(['setup' => $setup]);

            /** @var \Magento\Sales\Setup\SalesSetup $salesSetup */
            $salesSetup = $this->salesSetupFactory->create(['setup' => $setup]);

            $quoteSetup->addAttribute(
                'quote_item',
                'invoice_docnumber',
                [
                    'type'     => Table::TYPE_TEXT,
                    'length'   => 22,
                    'visible'  => true,
                    'nullable' => true
                ]
            );

            $salesSetup->addAttribute(
                'order_item',
                'invoice_docnumber',
                [
                    'type'     => Table::TYPE_TEXT,
                    'length'   => 22,
                    'visible'  => true,
                    'nullable' => true
                ]
            );
        }

        if (version_compare($context->getVersion(), '1.5.1', '<')) {
            /** @var \Magento\Sales\Setup\SalesSetup $salesSetup */
            $salesSetup = $this->salesSetupFactory->create(['resourceName' => 'sales_setup', 'setup' => $setup]);

            $salesSetup->addAttribute('order', 'is_invoice_payment', [
                'type'     => Table::TYPE_INTEGER,
                'visible'  => true,
                'nullable' => false,
                'default'  => 0
            ]);
        }

        if (version_compare($context->getVersion(), '1.6.5', '<')) {
            $tableName = $setup->getTable('sales_order_status');
            $status[]  = ['status' => 'invoice_payment', 'label' => 'Invoice Payment'];

            // Make this safe for sites where 'invoice_payment' has already been added.
            $setup->getConnection()->insertOnDuplicate($tableName, $status);
        }

        if (version_compare($context->getVersion(), '1.6.6', '<')) {
            $tableName = $setup->getTable('sales_order_status');
            $status[]  = ['status' => 'invoice_payment_complete', 'label' => 'Completed Invoice Payment'];

            // Make this safe for sites where 'invoice_payment_complete' has already been added.
            $setup->getConnection()->insertOnDuplicate($tableName, $status);
        }

        if (version_compare($context->getVersion(), '1.6.7', '<')) {
            /** @var \Magento\Quote\Setup\QuoteSetup $quoteSetup */
            $quoteSetup = $this->quoteSetupFactory->create(['setup' => $setup]);

            /** @var \Magento\Sales\Setup\SalesSetup $salesSetup */
            $salesSetup = $this->salesSetupFactory->create(['setup' => $setup]);

            $quoteSetup->addAttribute(
                'quote_item',
                'uom',
                [
                    'type'     => Table::TYPE_TEXT,
                    'length'   => 12,
                    'visible'  => true,
                    'nullable' => true
                ]
            );

            $salesSetup->addAttribute(
                'order_item',
                'uom',
                [
                    'type'     => Table::TYPE_TEXT,
                    'length'   => 12,
                    'visible'  => true,
                    'nullable' => true
                ]
            );
        }

        if (version_compare($context->getVersion(), '1.6.8', '<')) {
            /** @var \Magento\Eav\Setup\EavSetup $eavSetup */
            $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);

            $eavSetup->addAttribute(
                Product::ENTITY,
                'uom',
                [
                    'type' => 'varchar',
                    'label' => 'UOM',
                    'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                    'visible' => true,
                    'required' => false,
                    'user_defined' => false,
                    'default' => '',
                    'searchable' => true,
                    'filterable' => true,
                    'comparable' => true,
                    'visible_on_front' => true,
                    'used_in_product_listing' => true,
                    'unique' => false,
                    'apply_to' => '',
                    'nullable' => true
                ]
            );
        }

        if (version_compare($context->getVersion(), '1.6.10.0', '<')) {
            /** @var \Magento\Eav\Setup\EavSetup $eavSetup */
            $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);

            $eavSetup->updateAttribute(Product::ENTITY, 'uom', 'is_filterable', 'false');
        }

        $installer->endSetup();
    }
}
