<?php
/**
 * Copyright (C) EC Brands Corporation - All Rights Reserved
 * Contact Licensing@ECInternet.com for use guidelines
 */
declare(strict_types=1);

namespace ECInternet\Sage300Account\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * Oeordh Resource Model
 */
class Oeordh extends AbstractDb
{
    const TABLE_NAME    = 'ecinternet_sage300account_oeordh';

    const ID_FIELD_NAME = 'entity_id';
    /**
     * Initialize resource model
     *
     * @return void
     */
    public function _construct()
    {
        $this->_init('ecinternet_sage300account_oeordh', 'entity_id');
    }
}
