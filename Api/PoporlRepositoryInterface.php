<?php
/**
 * Copyright (C) EC Brands Corporation - All Rights Reserved
 * Contact Licensing@ECInternet.com for use guidelines
 */
declare(strict_types=1);

namespace ECInternet\Sage300Account\Api;

use Magento\Framework\Api\SearchCriteriaInterface;
use ECInternet\Sage300Account\Api\Data\PoporlInterface;

/**
 * @SuppressWarnings(PHPMD.ShortVariable)
 */
interface PoporlRepositoryInterface
{
    /**
     * Save POPORL record
     *
     * @param \ECInternet\Sage300Account\Api\Data\PoporlInterface $poporl
     *
     * @return \ECInternet\Sage300Account\Api\Data\PoporlInterface
     *
     * @throws \Magento\Framework\Exception\AlreadyExistsException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(PoporlInterface $poporl);

    /**
     * Bulk save POPORL records
     *
     * @param \ECInternet\Sage300Account\Api\Data\PoporlInterface[] $poporlArray
     *
     * @return bool[]
     */
    public function bulkSave(array $poporlArray);

    /**
     * Get POPORL record by SequenceKey and LineNumber
     *
     * @param int $sequenceKey
     * @param int $lineNumber
     *
     * @return \ECInternet\Sage300Account\Api\Data\PoporlInterface|null
     */
    public function get(int $sequenceKey, int $lineNumber);

    /**
     * Get POPORL record by ID
     *
     * @param int $poporlId
     *
     * @return \ECInternet\Sage300Account\Api\Data\PoporlInterface|null
     */
    public function getById(int $poporlId);

    /**
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     *
     * @return \ECInternet\Sage300Account\Api\Data\PoporlSearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria);

    /**
     * Delete POPORL record
     *
     * @param int $poporlId
     *
     * @return bool
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById(int $poporlId);

    /**
     * Deactivate POPORL records by Id
     *
     * @param int $poporlId
     *
     * @return bool
     */
    public function deactivateById(int $poporlId);
}
