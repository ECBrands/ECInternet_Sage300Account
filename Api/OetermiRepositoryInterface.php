<?php
/**
 * Copyright (C) EC Brands Corporation - All Rights Reserved
 * Contact Licensing@ECInternet.com for use guidelines
 */
declare(strict_types=1);

namespace ECInternet\Sage300Account\Api;

use Magento\Framework\Api\SearchCriteriaInterface;
use ECInternet\Sage300Account\Api\Data\OetermiInterface;

interface OetermiRepositoryInterface
{
    /**
     * Save OETERMI record
     *
     * @param \ECInternet\Sage300Account\Api\Data\OetermiInterface $oetermi
     *
     * @return \ECInternet\Sage300Account\Api\Data\OetermiInterface
     *
     * @throws \Magento\Framework\Exception\AlreadyExistsException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(OetermiInterface $oetermi);

    /**
     * Bulk save OETERMI records
     *
     * @param \ECInternet\Sage300Account\Api\Data\OetermiInterface[] $oetermiArray
     *
     * @return bool[]
     */
    public function bulkSave(array $oetermiArray);

    /**
     * Get OETERMI record
     *
     * @param int $invoiceUniquifier
     * @param int $paymentNumber
     *
     * @return \ECInternet\Sage300Account\Api\Data\OetermiInterface|null
     */
    public function get(int $invoiceUniquifier, int $paymentNumber);

    /**
     * Get OETERMI record by ID
     *
     * @param int $oetermiId
     *
     * @return \ECInternet\Sage300Account\Api\Data\OetermiInterface|null
     */
    public function getById(int $oetermiId);

    /**
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     *
     * @return \ECInternet\Sage300Account\Api\Data\OetermiSearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria);

    /**
     * Delete OETERMI record
     *
     * @param int $oetermiId
     *
     * @return bool
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById(int $oetermiId);
}
