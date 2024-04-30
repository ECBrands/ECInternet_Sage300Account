<?php
/**
 * Copyright (C) EC Brands Corporation - All Rights Reserved
 * Contact Licensing@ECInternet.com for use guidelines
 */
declare(strict_types=1);

namespace ECInternet\Sage300Account\Api;

use Magento\Framework\Api\SearchCriteriaInterface;
use ECInternet\Sage300Account\Api\Data\OeinvhInterface;

/**
 * @SuppressWarnings(PHPMD.ShortVariable)
 */
interface OeinvhRepositoryInterface
{
    /**
     * Save OEINVH record
     *
     * @param \ECInternet\Sage300Account\Api\Data\OeinvhInterface $oeinvh
     *
     * @return \ECInternet\Sage300Account\Api\Data\OeinvhInterface
     *
     * @throws \Magento\Framework\Exception\AlreadyExistsException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(OeinvhInterface $oeinvh);

    /**
     * Bulk save OEINVH record
     *
     * @param \ECInternet\Sage300Account\Api\Data\OeinvhInterface[] $oeinvhArray
     *
     * @return bool[]
     */
    public function bulkSave(array $oeinvhArray);

    /**
     * Get OEINVH record by ID
     *
     * @param int $oeinvhId
     *
     * @return \ECInternet\Sage300Account\Api\Data\OeinvhInterface|null
     */
    public function getById(int $oeinvhId);

    /**
     * Get OEINVH by InvoiceUniquifier
     *
     * @param int $invoiceUniquifier
     *
     * @return \ECInternet\Sage300Account\Api\Data\OeinvhInterface|null
     */
    public function getByInvoiceUniquifier(int $invoiceUniquifier);

    /**
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     *
     * @return \ECInternet\Sage300Account\Api\Data\OeinvhSearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria);

    /**
     * Delete OEINVH record
     *
     * @param int $oeinvhId
     *
     * @return bool
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById(int $oeinvhId);
}
