<?php
/**
 * Copyright (C) EC Brands Corporation - All Rights Reserved
 * Contact Licensing@ECInternet.com for use guidelines
 */
declare(strict_types=1);

namespace ECInternet\Sage300Account\Api;

use Magento\Framework\Api\SearchCriteriaInterface;
use ECInternet\Sage300Account\Api\Data\OeppreInterface;

interface OeppreRepositoryInterface
{
    /**
     * Save OEPPRE record
     *
     * @param \ECInternet\Sage300Account\Api\Data\OeppreInterface $oeppre
     *
     * @return \ECInternet\Sage300Account\Api\Data\OeppreInterface
     *
     * @throws \Magento\Framework\Exception\AlreadyExistsException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(OeppreInterface $oeppre);

    /**
     * Bulk save OEPPRE records
     *
     * @param \ECInternet\Sage300Account\Api\Data\OeppreInterface[] $oeppreArray
     *
     * @return bool[]
     */
    public function bulkSave(array $oeppreArray);

    /**
     * Get OEPPRE record
     *
     * @param int    $applyTo
     * @param string $documentNumber
     * @param int    $prepaymentNumber
     *
     * @return \ECInternet\Sage300Account\Api\Data\OeppreInterface|null
     */
    public function get(int $applyTo, string $documentNumber, int $prepaymentNumber);

    /**
     * Get OEPPRE by ID
     *
     * @param int $oeppreId
     *
     * @return \ECInternet\Sage300Account\Api\Data\OeppreInterface|null
     */
    public function getById(int $oeppreId);

    /**
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     *
     * @return \ECInternet\Sage300Account\Api\Data\OeppreSearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria);

    /**
     * Delete OEPPRE record
     *
     * @param int $oeppreId
     *
     * @return bool
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById(int $oeppreId);
}
