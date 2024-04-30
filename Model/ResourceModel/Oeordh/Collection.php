<?php
/**
 * Copyright (C) EC Brands Corporation - All Rights Reserved
 * Contact Licensing@ECInternet.com for use guidelines
 */
declare(strict_types=1);

namespace ECInternet\Sage300Account\Model\ResourceModel\Oeordh;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

/**
 * Oeordh Collection
 */
class Collection extends AbstractCollection
{
    protected $_idFieldName = 'entity_id';

    protected $_eventPrefix = 'ecinternet_sage300account_oeordh_collection';

    protected $_eventObject = 'oeordh_collection';

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(
            'ECInternet\Sage300Account\Model\Data\Oeordh',
            'ECInternet\Sage300Account\Model\ResourceModel\Oeordh'
        );
    }
}
