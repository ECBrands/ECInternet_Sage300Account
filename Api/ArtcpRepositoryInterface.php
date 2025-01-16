<?php
/**
 * Copyright (C) EC Brands Corporation - All Rights Reserved
 * Contact Licensing@ECInternet.com for use guidelines
 */
declare(strict_types=1);

namespace ECInternet\Sage300Account\Api;

use Magento\Framework\Api\SearchCriteriaInterface;
use ECInternet\Sage300Account\Api\Data\ArtcpInterface;

interface ArtcpRepositoryInterface
{
    /**
     * Save ARTCP record
     *
     * @param \ECInternet\Sage300Account\Api\Data\ArtcpInterface $artcp
     *
     * @return \ECInternet\Sage300Account\Api\Data\ArtcpInterface
     *
     * @throws \Magento\Framework\Exception\AlreadyExistsException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(ArtcpInterface $artcp);

    /**
     * Bulk save ARTCP records
     *
     * @param \ECInternet\Sage300Account\Api\Data\ArtcpInterface[] $artcpArray
     *
     * @return bool[]
     */
    public function bulkSave(array $artcpArray);

    /**
     * Get ARTCP record by ID
     *
     * @param int $artcpId
     *
     * @return \ECInternet\Sage300Account\Api\Data\ArtcpInterface|null
     */
    public function getById(int $artcpId);

    /**
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     *
     * @return \ECInternet\Sage300Account\Api\Data\ArtcpSearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria);

    /**
     * Delete ARTCP record
     *
     * @param int $artcpId
     *
     * @return bool
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById(int $artcpId);
}
