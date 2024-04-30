<?php
/**
 * Copyright (C) EC Brands Corporation - All Rights Reserved
 * Contact Licensing@ECInternet.com for use guidelines
 */
declare(strict_types=1);

namespace ECInternet\Sage300Account\Api;

use Magento\Framework\Api\SearchCriteriaInterface;
use ECInternet\Sage300Account\Api\Data\OeordhInterface;

/**
 * @SuppressWarnings(PHPMD.ShortVariable)
 */
interface OeordhRepositoryInterface
{
    /**
     * Save OEORDH record
     *
     * @param \ECInternet\Sage300Account\Api\Data\OeordhInterface $oeordh
     *
     * @return \ECInternet\Sage300Account\Api\Data\OeordhInterface
     *
     * @throws \Magento\Framework\Exception\AlreadyExistsException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(OeordhInterface $oeordh);

    /**
     * Bulk save OEORDH records
     *
     * @param \ECInternet\Sage300Account\Api\Data\OeordhInterface[] $oeordhArray
     *
     * @return bool[]
     */
    public function bulkSave(array $oeordhArray);

    /**
     * Get OEORDH record by ID
     *
     * @param int $oeordhId
     *
     * @return \ECInternet\Sage300Account\Api\Data\OeordhInterface|null
     */
    public function getById(int $oeordhId);

    /**
     * Get OEORDH record by ORDUNIQ
     *
     * @param int $orderUniquifier
     *
     * @return \ECInternet\Sage300Account\Api\Data\OeordhInterface|null
     */
    public function getByOrderUniquifier(int $orderUniquifier);

    /**
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     *
     * @return \ECInternet\Sage300Account\Api\Data\OeordhSearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria);

    /**
     * Delete OEORDH record by ID
     *
     * @param int $oeordhId
     *
     * @return bool
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById(int $oeordhId);
}
