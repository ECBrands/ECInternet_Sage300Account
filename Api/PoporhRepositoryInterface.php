<?php
/**
 * Copyright (C) EC Brands Corporation - All Rights Reserved
 * Contact Licensing@ECInternet.com for use guidelines
 */
declare(strict_types=1);

namespace ECInternet\Sage300Account\Api;

use Magento\Framework\Api\SearchCriteriaInterface;
use ECInternet\Sage300Account\Api\Data\PoporhInterface;

/**
 * @SuppressWarnings(PHPMD.ShortVariable)
 */
interface PoporhRepositoryInterface
{
    /**
     * Save POPORH record
     *
     * @param \ECInternet\Sage300Account\Api\Data\PoporhInterface $poporh
     *
     * @return \ECInternet\Sage300Account\Api\Data\PoporhInterface
     *
     * @throws \Magento\Framework\Exception\AlreadyExistsException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(PoporhInterface $poporh);

    /**
     * Bulk save POPORH records
     *
     * @param \ECInternet\Sage300Account\Api\Data\PoporhInterface[] $poporhArray
     *
     * @return bool[]
     */
    public function bulkSave(array $poporhArray);

    /**
     * Get POPORH by SequenceKey
     *
     * @param int $sequenceKey
     *
     * @return \ECInternet\Sage300Account\Api\Data\PoporhInterface|null
     */
    public function get(int $sequenceKey);

    /**
     * Get POPORH record by ID
     *
     * @param int $poporhPoporhId
     *
     * @return \ECInternet\Sage300Account\Api\Data\PoporhInterface|null
     */
    public function getById(int $poporhId);

    /**
     * @param int $lineId
     *
     * @return \ECInternet\Sage300Account\Api\Data\PoporhInterface|null
     */
    public function getByLineId(int $lineId);

    /**
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     *
     * @return \ECInternet\Sage300Account\Api\Data\PoporhSearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria);

    /**
     * @param \ECInternet\Sage300Account\Api\Data\PoporhInterface $poporh
     *
     * @return \ECInternet\Sage300Account\Model\Data\Poporl[]
     */
    public function getDetailLines(PoporhInterface $poporh);

    /**
     * Delete POPORH record
     *
     * @param int $poporhId
     *
     * @return bool
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById(int $poporhId);
}
