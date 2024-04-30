<?php
/**
 * Copyright (C) EC Brands Corporation - All Rights Reserved
 * Contact Licensing@ECInternet.com for use guidelines
 */
declare(strict_types=1);

namespace ECInternet\Sage300Account\Api;

use Magento\Framework\Api\SearchCriteriaInterface;
use ECInternet\Sage300Account\Api\Data\PoporloInterface;

interface PoporloRepositoryInterface
{
    /**
     * Save POPORLO record
     *
     * @param \ECInternet\Sage300Account\Api\Data\PoporloInterface $poporlo
     *
     * @return \ECInternet\Sage300Account\Api\Data\PoporloInterface
     *
     * @throws \Magento\Framework\Exception\AlreadyExistsException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(PoporloInterface $poporlo);

    /**
     * Bulk save POPORLO records
     *
     * @param \ECInternet\Sage300Account\Api\Data\PoporloInterface[] $poporloArray
     *
     * @return bool[]
     */
    public function bulkSave(array $poporloArray);

    /**
     * Get POPORLO by SequenceKey and LineNumber
     *
     * @param int    $sequenceKey
     * @param int    $lineNumber
     * @param string $optionalField
     *
     * @return \ECInternet\Sage300Account\Api\Data\PoporloInterface|null
     */
    public function get(int $sequenceKey, int $lineNumber, string $optionalField);

    /**
     * Get POPORLO record by ID
     *
     * @param int $poporloId
     *
     * @return \ECInternet\Sage300Account\Api\Data\PoporloInterface|null
     */
    public function getById(int $poporloId);

    /**
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     *
     * @return \ECInternet\Sage300Account\Api\Data\PoporloSearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria);

    /**
     * Delete POPORLO record
     *
     * @param int $poporloId
     *
     * @return bool
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById(int $poporloId);
}
