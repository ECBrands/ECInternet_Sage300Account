<?php
/**
 * Copyright (C) EC Brands Corporation - All Rights Reserved
 * Contact Licensing@ECInternet.com for use guidelines
 */
declare(strict_types=1);

namespace ECInternet\Sage300Account\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

interface OeordhSearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get items
     *
     * @return \ECInternet\Sage300Account\Api\Data\OeordhInterface[]
     */
    public function getItems();

    /**
     * Set items
     *
     * @param \ECInternet\Sage300Account\Api\Data\OeordhInterface[] $items
     *
     * @return void
     */
    public function setItems(array $items);
}
