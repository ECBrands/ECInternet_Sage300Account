<?php
/**
 * Copyright (C) EC Brands Corporation - All Rights Reserved
 * Contact Licensing@ECInternet.com for use guidelines
 */
declare(strict_types=1);

namespace ECInternet\Sage300Account\Model\ResourceModel\Uom;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

/**
 * UOM Collection
 */
class Collection extends AbstractCollection
{
    protected $_idFieldName = 'entity_id';

    protected $_eventPrefix = 'ecinternet_sage300account_uom_collection';

    protected $_eventObject = 'uom_collection';

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(
            'ECInternet\Sage300Account\Model\Data\Uom',
            'ECInternet\Sage300Account\Model\ResourceModel\Uom'
        );
    }
}
