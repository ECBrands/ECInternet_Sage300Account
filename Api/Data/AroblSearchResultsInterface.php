<?php
/**
 * Copyright (C) EC Brands Corporation - All Rights Reserved
 * Contact Licensing@ECInternet.com for use guidelines
 */
declare(strict_types=1);

namespace ECInternet\Sage300Account\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

interface AroblSearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get items
     *
     * @return \ECInternet\Sage300Account\Api\Data\AroblInterface[]
     */
    public function getItems();

    /**
     * Set items
     *
     * @param \ECInternet\Sage300Account\Api\Data\AroblInterface[] $items
     *
     * @return void
     */
    public function setItems(array $items);
}
