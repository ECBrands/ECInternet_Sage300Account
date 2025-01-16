<?php
/**
 * Copyright (C) EC Brands Corporation - All Rights Reserved
 * Contact Licensing@ECInternet.com for use guidelines
 */
declare(strict_types=1);

namespace ECInternet\Sage300Account\Api;

use Magento\Framework\Api\SearchCriteriaInterface;
use ECInternet\Sage300Account\Api\Data\AroblInterface;

interface AroblRepositoryInterface
{
    /**
     * Save AROBL record
     *
     * @param \ECInternet\Sage300Account\Api\Data\AroblInterface $arobl
     *
     * @return \ECInternet\Sage300Account\Api\Data\AroblInterface
     *
     * @throws \Magento\Framework\Exception\AlreadyExistsException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(AroblInterface $arobl);

    /**
     * Bulk save AROBL records
     *
     * @param \ECInternet\Sage300Account\Api\Data\AroblInterface[] $aroblArray
     *
     * @return bool[]
     */
    public function bulkSave(array $aroblArray);

    /**
     * Get AROBL record by ID
     *
     * @param int $aroblId
     *
     * @return \ECInternet\Sage300Account\Api\Data\AroblInterface|null
     */
    public function getById(int $aroblId);

    /**
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     *
     * @return \ECInternet\Sage300Account\Api\Data\AroblSearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria);

    /**
     * Delete AROBL record by ID
     *
     * @param int $aroblId
     *
     * @return bool
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById(int $aroblId);
}
