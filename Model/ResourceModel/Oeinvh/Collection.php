<?php
/**
 * Copyright (C) EC Brands Corporation - All Rights Reserved
 * Contact Licensing@ECInternet.com for use guidelines
 */
declare(strict_types=1);

namespace ECInternet\Sage300Account\Model\ResourceModel\Oeinvh;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

/**
 * Oeinvh Collection
 */
class Collection extends AbstractCollection
{
    protected $_idFieldName = 'entity_id';

    protected $_eventPrefix = 'ecinternet_sage300account_oeinvh_collection';

    protected $_eventObject = 'oeinvh_collection';

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(
            'ECInternet\Sage300Account\Model\Data\Oeinvh',
            'ECInternet\Sage300Account\Model\ResourceModel\Oeinvh'
        );
    }

    /**
     * @return float
     */
    public function getTotal()
    {
        $total = 0.0;

        foreach ($this->getItems() as $item) {
            /** @var \ECInternet\Sage300Account\Model\Data\Oeinvh $item */
            $total += $item->getTotalDue();
        }

        return $total;
    }
}
