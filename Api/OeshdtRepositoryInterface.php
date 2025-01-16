<?php
/**
 * Copyright (C) EC Brands Corporation - All Rights Reserved
 * Contact Licensing@ECInternet.com for use guidelines
 */
declare(strict_types=1);

namespace ECInternet\Sage300Account\Api;

use Magento\Framework\Api\SearchCriteriaInterface;
use ECInternet\Sage300Account\Api\Data\OeshdtInterface;

interface OeshdtRepositoryInterface
{
    /**
     * Save OESHDT record
     *
     * @param \ECInternet\Sage300Account\Api\Data\OeshdtInterface $oeshdt
     *
     * @return \ECInternet\Sage300Account\Api\Data\OeshdtInterface
     *
     * @throws \Magento\Framework\Exception\AlreadyExistsException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(OeshdtInterface $oeshdt);

    /**
     * Bulk save OESHDT records
     *
     * @param \ECInternet\Sage300Account\Api\Data\OeshdtInterface[] $oeshdtArray
     *
     * @return bool[]
     */
    public function bulkSave(array $oeshdtArray);

    /**
     * Get OESHDT record by CustomerNumber and ItemNumber
     *
     * @param string $customerNumber
     * @param string $itemNumber
     *
     * @return \ECInternet\Sage300Account\Api\Data\OeshdtInterface|null
     */
    public function get(string $customerNumber, string $itemNumber);

    /**
     * Get OESHDT record by ID
     *
     * @param int $oeshdtId
     *
     * @return \ECInternet\Sage300Account\Api\Data\OeshdtInterface|null
     */
    public function getById(int $oeshdtId);

    /**
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     *
     * @return \ECInternet\Sage300Account\Api\Data\OeshdtSearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria);

    /**
     * Delete OESHDT record
     *
     * @param int $oeshdtId
     *
     * @return bool
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById(int $oeshdtId);
}
