<?php
/**
 * Copyright (C) EC Brands Corporation - All Rights Reserved
 * Contact Licensing@ECInternet.com for use guidelines
 */
declare(strict_types=1);

namespace ECInternet\Sage300Account\Setup\Patch\Data;

use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;

class AddInvoicePaymentOrderStatuses implements DataPatchInterface
{
    /**
     * @var \Magento\Framework\Setup\ModuleDataSetupInterface
     */
    private $setup;

    public function __construct(
        ModuleDataSetupInterface $setup
    ) {
        $this->setup = $setup;
    }

    public static function getDependencies(): array
    {
        return [];
    }

    public function getAliases(): array
    {
        return [];
    }

    /**
     * @return void
     */
    public function apply(): void
    {
        $this->setup->getConnection()->startSetup();

        $tableName = $this->setup->getTable('sales_order_status');

        $this->setup->getConnection()->insertOnDuplicate($tableName, [
            ['status' => 'invoice_payment', 'label' => 'Invoice Payment'],
        ]);

        $this->setup->getConnection()->insertOnDuplicate($tableName, [
            ['status' => 'invoice_payment_complete', 'label' => 'Completed Invoice Payment'],
        ]);

        $this->setup->getConnection()->endSetup();
    }
}
