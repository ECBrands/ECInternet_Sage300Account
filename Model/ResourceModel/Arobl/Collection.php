<?php
/**
 * Copyright (C) EC Brands Corporation - All Rights Reserved
 * Contact Licensing@ECInternet.com for use guidelines
 */
declare(strict_types=1);

namespace ECInternet\Sage300Account\Model\ResourceModel\Arobl;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

/**
 * Arobl Collection
 */
class Collection extends AbstractCollection
{
    protected $_idFieldName = 'entity_id';

    protected $_eventPrefix = 'ecinternet_sage300account_arobl_collection';

    protected $_eventObject = 'arobl_collection';

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(
            'ECInternet\Sage300Account\Model\Data\Arobl',
            'ECInternet\Sage300Account\Model\ResourceModel\Arobl'
        );
    }
}
