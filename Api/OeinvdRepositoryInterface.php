<?php
/**
 * Copyright (C) EC Brands Corporation - All Rights Reserved
 * Contact Licensing@ECInternet.com for use guidelines
 */
declare(strict_types=1);

namespace ECInternet\Sage300Account\Api;

use Magento\Framework\Api\SearchCriteriaInterface;
use ECInternet\Sage300Account\Api\Data\OeinvdInterface;

interface OeinvdRepositoryInterface
{
    /**
     * Save OEINVD record
     *
     * @param \ECInternet\Sage300Account\Api\Data\OeinvdInterface $oeinvd
     *
     * @return \ECInternet\Sage300Account\Api\Data\OeinvdInterface
     *
     * @throws \Magento\Framework\Exception\AlreadyExistsException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(OeinvdInterface $oeinvd);

    /**
     * Bulk save OEINVD records
     *
     * @param \ECInternet\Sage300Account\Api\Data\OeinvdInterface[] $oeinvdArray
     *
     * @return bool[]
     */
    public function bulkSave(array $oeinvdArray);

    /**
     * Get OEINVD record by ID
     *
     * @param int $oeinvdId
     *
     * @return \ECInternet\Sage300Account\Api\Data\OeinvdInterface|null
     */
    public function getById(int $oeinvdId);

    /**
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     *
     * @return \ECInternet\Sage300Account\Api\Data\OeinvdSearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria);

    /**
     * Delete OEINVD record
     *
     * @param int $oeinvdId
     *
     * @return bool
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById(int $oeinvdId);
}
