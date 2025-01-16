<?php
/**
 * Copyright (C) EC Brands Corporation - All Rights Reserved
 * Contact Licensing@ECInternet.com for use guidelines
 */
declare(strict_types=1);

namespace ECInternet\Sage300Account\Model\ResourceModel\Oeinvd;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

/**
 * Oeinvd Collection
 */
class Collection extends AbstractCollection
{
    protected $_idFieldName = 'entity_id';

    protected $_eventPrefix = 'ecinternet_sage300account_oeinvd_collection';

    protected $_eventObject = 'oeinvd_collection';

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(
            '\ECInternet\Sage300Account\Model\Data\Oeinvd',
            '\ECInternet\Sage300Account\Model\ResourceModel\Oeinvd'
        );
    }
}
