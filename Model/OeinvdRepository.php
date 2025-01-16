<?php
/**
 * Copyright (C) EC Brands Corporation - All Rights Reserved
 * Contact Licensing@ECInternet.com for use guidelines
 */
declare(strict_types=1);

namespace ECInternet\Sage300Account\Model;

use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use ECInternet\Sage300Account\Api\OeinvdRepositoryInterface;
use ECInternet\Sage300Account\Api\Data\OeinvdInterface;
use ECInternet\Sage300Account\Api\Data\OeinvdSearchResultsInterfaceFactory;
use ECInternet\Sage300Account\Logger\Logger;
use ECInternet\Sage300Account\Model\Data\Oeinvd;
use ECInternet\Sage300Account\Model\ResourceModel\Oeinvd as OeinvdResource;
use ECInternet\Sage300Account\Model\ResourceModel\Oeinvd\CollectionFactory as OeinvdCollectionFactory;
use Exception;

/**
 * Oeinvd model repository
 *
 * @SuppressWarnings(PHPMD.LongVariable)
 * @SuppressWarnings(PHPMD.ShortVariable)
 */
class OeinvdRepository implements OeinvdRepositoryInterface
{
    /**
     * @var \Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface
     */
    private $collectionProcessor;

    /**
     * @var \ECInternet\Sage300Account\Api\Data\OeinvdSearchResultsInterfaceFactory
     */
    protected $searchResultsFactory;

    /**
     * @var \ECInternet\Sage300Account\Logger\Logger
     */
    private $logger;

    /**
     * @var \ECInternet\Sage300Account\Model\ResourceModel\Oeinvd
     */
    private $resourceModel;

    /**
     * @var \ECInternet\Sage300Account\Model\ResourceModel\Oeinvd\CollectionFactory
     */
    private $oeinvdCollectionFactory;

    /**
     * OeinvdRepository constructor.
     *
     * @param \Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface      $collectionProcessor
     * @param \ECInternet\Sage300Account\Api\Data\OeinvdSearchResultsInterfaceFactory $oeinvdSearchResultsFactory
     * @param \ECInternet\Sage300Account\Logger\Logger                                $logger
     * @param \ECInternet\Sage300Account\Model\ResourceModel\Oeinvd                   $resourceModel
     * @param \ECInternet\Sage300Account\Model\ResourceModel\Oeinvd\CollectionFactory $oeinvdCollectionFactory
     */
    public function __construct(
        CollectionProcessorInterface $collectionProcessor,
        OeinvdSearchResultsInterfaceFactory $oeinvdSearchResultsFactory,
        Logger $logger,
        OeinvdResource $resourceModel,
        OeinvdCollectionFactory $oeinvdCollectionFactory
    ) {
        $this->collectionProcessor     = $collectionProcessor;
        $this->searchResultsFactory    = $oeinvdSearchResultsFactory;
        $this->logger                  = $logger;
        $this->resourceModel           = $resourceModel;
        $this->oeinvdCollectionFactory = $oeinvdCollectionFactory;
    }

    public function save(
        OeinvdInterface $oeinvd
    ) {
        $this->log('save()', ['oeinvd' => $oeinvd->getData()]);

        $this->validate($oeinvd);

        // If we find existing, grab the ID and set on incoming record
        if ($existing = $this->get($oeinvd->getInvoiceUniquifier(), $oeinvd->getLineUniquifier())) {
            $oeinvd->setId($existing->getId());
        }

        try {
            $this->resourceModel->save($oeinvd);
        } catch (Exception $e) {
            $this->log('save()', [
                'class'     => get_class($e),
                'exception' => $e->getMessage(),
                'trace'     => $e->getTraceAsString()
            ]);

            throw $e;
        }

        return $oeinvd;
    }

    public function bulkSave(
        array $oeinvdArray
    ) {
        $this->log('bulkSave()');

        $results = [];

        foreach ($oeinvdArray as $oeinvd) {
            $this->log('bulkSave()', ['oeinvd' => $oeinvd->getData()]);

            try {
                $this->save($oeinvd);
                $results[] = true;
            } catch (Exception $e) {
                $this->log('bulkSave()', ['exception' => $e->getMessage()]);
                $results[] = false;
            }
        }

        return $results;
    }

    public function get(
        int $invoiceUniquifier,
        int $lineNumber
    ) {
        $this->log('get()', [
            'invoiceUniquifier' => $invoiceUniquifier,
            'lineNumber'        => $lineNumber
        ]);

        $collection = $this->oeinvdCollectionFactory->create()
            ->addFieldToFilter(Oeinvd::COLUMN_INVUNIQ, ['eq' => $invoiceUniquifier])
            ->addFieldToFilter(Oeinvd::COLUMN_LINENUM, ['eq' => $lineNumber]);

        $collectionCount = $collection->getSize();
        $this->log('get()', [
            'select'          => $collection->getSelect(),
            'collectionCount' => $collectionCount
        ]);

        // If we find no records, log it and return.
        if ($collectionCount === 0) {
            $this->log('get() - No OEINVD records found.');

            return null;
        }

        // If we find multiple records, log and return.
        if ($collectionCount > 1) {
            $this->log('get() - More than one OEINVD record found.');

            return null;
        }

        $oeinvd = $collection->getFirstItem();
        if ($oeinvd instanceof OeinvdInterface) {
            return $oeinvd;
        }

        return null;
    }

    public function getById(
        int $oeinvdId
    ) {
        $this->log('getById()', ['oeinvdId' => $oeinvdId]);

        /** @var \ECInternet\Sage300Account\Model\ResourceModel\Poporl\Collection $collection */
        $collection = $this->oeinvdCollectionFactory->create()
            ->addFieldToFilter(Oeinvd::COLUMN_ID, $oeinvdId);

        $collectionCount = $collection->getSize();
        $this->log('getById()', [
            'select'          => $collection->getSelect(),
            'collectionCount' => $collectionCount
        ]);

        if ($collectionCount === 1) {
            $oeinvd = $collection->getFirstItem();
            if ($oeinvd instanceof Oeinvd) {
                return $oeinvd;
            }
        }

        return null;
    }

    public function getList(
        SearchCriteriaInterface $searchCriteria
    ) {
        $this->log('getList()');

        /** @var \ECInternet\Sage300Account\Api\Data\OeinvdSearchResultsInterface $searchResults */
        $searchResults = $this->searchResultsFactory->create();

        /** @noinspection PhpExpressionResultUnusedInspection */
        $searchResults->setSearchCriteria($searchCriteria);

        /** @var \ECInternet\Sage300Account\Model\ResourceModel\Poporl\Collection $collection */
        $collection = $this->oeinvdCollectionFactory->create();
        $this->collectionProcessor->process($searchCriteria, $collection);

        $searchResults->setTotalCount($collection->getSize());

        $collection->setCurPage($searchCriteria->getCurrentPage());
        $collection->setPageSize($searchCriteria->getPageSize());
        $searchResults->setItems($collection->getData());

        return $searchResults;
    }

    public function deleteById(int $oeinvdId)
    {
        if ($oeinvd = $this->getById($oeinvdId)) {
            try {
                $this->resourceModel->delete($oeinvd);

                return true;
            } catch (Exception $e) {
                $this->log('deleteById()', ['error' => $e->getMessage()]);
            }
        }

        return false;
    }

    /**
     * Validate OEINVD record
     *
     * @param \ECInternet\Sage300Account\Api\Data\OeinvdInterface $oeinvd
     *
     * @return void
     */
    protected function validate(
        OeinvdInterface $oeinvd
    ) {
    }

    /**
     * Write to extension log
     *
     * @param string $message
     * @param array  $extra
     *
     * @return void
     */
    private function log(string $message, array $extra = [])
    {
        $this->logger->info('Model/OeinvdRepository - ' . $message, $extra);
    }
}
