<?php
/**
 * Copyright (C) EC Brands Corporation - All Rights Reserved
 * Contact Licensing@ECInternet.com for use guidelines
 */
declare(strict_types=1);

namespace ECInternet\Sage300Account\Api;

use Magento\Framework\Api\SearchCriteriaInterface;
use ECInternet\Sage300Account\Api\Data\OeorddInterface;

interface OeorddRepositoryInterface
{
    /**
     * Save OEORDD record
     *
     * @param \ECInternet\Sage300Account\Api\Data\OeorddInterface $oeordd
     *
     * @return \ECInternet\Sage300Account\Api\Data\OeorddInterface
     *
     * @throws \Magento\Framework\Exception\AlreadyExistsException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(OeorddInterface $oeordd);

    /**
     * Bulk save OEORDD records
     *
     * @param \ECInternet\Sage300Account\Api\Data\OeorddInterface[] $oeorddArray
     *
     * @return bool[]
     */
    public function bulkSave(array $oeorddArray);

    /**
     * Get OEORDD record
     *
     * @param int $orderUniquifier
     * @param int $lineNumber
     *
     * @return \ECInternet\Sage300Account\Api\Data\OeorddInterface|null
     */
    public function get(int $orderUniquifier, int $lineNumber);

    /**
     * Get OEORDD record by ID
     *
     * @param int $oeorddId
     *
     * @return \ECInternet\Sage300Account\Api\Data\OeorddInterface|null
     */
    public function getById(int $oeorddId);

    /**
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     *
     * @return \ECInternet\Sage300Account\Api\Data\OeorddSearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria);

    /**
     * Delete OEORDD record
     *
     * @param int $oeorddId
     *
     * @return bool
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById(int $oeorddId);
}
