<?php
/**
 * Copyright (C) EC Brands Corporation - All Rights Reserved
 * Contact Licensing@ECInternet.com for use guidelines
 */
declare(strict_types=1);

namespace ECInternet\Sage300Account\Model\ResourceModel\Artcp;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

/**
 * Artcp Collection
 */
class Collection extends AbstractCollection
{
    protected $_idFieldName = 'entity_id';

    protected $_eventPrefix = 'ecinternet_sage300account_artcp_collection';

    protected $_eventObject = 'artcp_collection';

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(
            'ECInternet\Sage300Account\Model\Data\Artcp',
            'ECInternet\Sage300Account\Model\ResourceModel\Artcp'
        );
    }
}
