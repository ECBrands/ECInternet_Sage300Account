<?php
/**
 * Copyright (C) EC Brands Corporation - All Rights Reserved
 * Contact Licensing@ECInternet.com for use guidelines
 */
declare(strict_types=1);

namespace ECInternet\Sage300Account\Model;

use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use ECInternet\Sage300Account\Api\Data\OeordhInterface;
use ECInternet\Sage300Account\Api\Data\OeordhSearchResultsInterfaceFactory;
use ECInternet\Sage300Account\Api\OeordhRepositoryInterface;
use ECInternet\Sage300Account\Logger\Logger;
use ECInternet\Sage300Account\Model\Data\Oeordh;
use ECInternet\Sage300Account\Model\ResourceModel\Oeordh as OeordhResource;
use ECInternet\Sage300Account\Model\ResourceModel\Oeordh\CollectionFactory as OeordhCollectionFactory;
use Exception;

/**
 * Oeordh model repository
 *
 * @SuppressWarnings(PHPMD.LongVariable)
 * @SuppressWarnings(PHPMD.ShortVariable)
 */
class OeordhRepository implements OeordhRepositoryInterface
{
    /**
     * @var \Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface
     */
    private $collectionProcessor;

    /**
     * @var \ECInternet\Sage300Account\Api\Data\OeordhSearchResultsInterfaceFactory
     */
    protected $searchResultsFactory;

    /**
     * @var \ECInternet\Sage300Account\Logger\Logger
     */
    private $logger;

    /**
     * @var \ECInternet\Sage300Account\Model\ResourceModel\Oeordh
     */
    private $resourceModel;

    /**
     * @var \ECInternet\Sage300Account\Model\ResourceModel\Oeordh\CollectionFactory
     */
    private $oeordhCollectionFactory;

    /**
     * OeordhRepository constructor.
     *
     * @param \Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface      $collectionProcessor
     * @param \ECInternet\Sage300Account\Api\Data\OeordhSearchResultsInterfaceFactory $oeordhSearchResults
     * @param \ECInternet\Sage300Account\Logger\Logger                                $logger
     * @param \ECInternet\Sage300Account\Model\ResourceModel\Oeordh                   $resourceModel
     * @param \ECInternet\Sage300Account\Model\ResourceModel\Oeordh\CollectionFactory $oeordhCollectionFactory
     */
    public function __construct(
        CollectionProcessorInterface $collectionProcessor,
        OeordhSearchResultsInterfaceFactory $oeordhSearchResults,
        Logger $logger,
        OeordhResource $resourceModel,
        OeordhCollectionFactory $oeordhCollectionFactory
    ) {
        $this->collectionProcessor     = $collectionProcessor;
        $this->searchResultsFactory    = $oeordhSearchResults;
        $this->logger                  = $logger;
        $this->resourceModel           = $resourceModel;
        $this->oeordhCollectionFactory = $oeordhCollectionFactory;
    }

    public function save(
        OeordhInterface $oeordh
    ) {
        $this->log('save()', ['oeordh' => $oeordh->getData()]);

        $this->validate($oeordh);

        // If we find existing, grab the ID and set on incoming record
        if ($this->doesRecordExist($oeordh)) {
            /** @var \ECInternet\Sage300Account\Api\Data\OeordhInterface $model */
            $model = $this->getByOrderUniquifier($oeordh->getOrderUniquifier());
            $oeordh->setId($model->getId());
        }

        try {
            $this->resourceModel->save($oeordh);
        } catch (Exception $e) {
            $this->log('save()', [
                'class'     => get_class($e),
                'exception' => $e->getMessage(),
                'trace'     => $e->getTraceAsString()
            ]);

            throw $e;
        }

        return $oeordh;
    }

    public function bulkSave(
        array $oeordhArray
    ) {
        $this->log('bulkSave()');

        $results = [];

        foreach ($oeordhArray as $oeordh) {
            $this->log('bulkSave()', ['oeordh' => $oeordh->getData()]);

            try {
                $this->save($oeordh);
                $results[] = true;
            } catch (Exception $e) {
                $this->log('bulkSave()', ['exception' => $e->getMessage()]);
                $results[] = false;
            }
        }

        return $results;
    }

    public function getById(
        int $oeordhId
    ) {
        $this->log('getById()', ['oeordhId' => $oeordhId]);

        /** @var \ECInternet\Sage300Account\Model\ResourceModel\Oeordh\Collection $collection */
        $collection = $this->oeordhCollectionFactory->create()
            ->addFieldToFilter(Oeordh::COLUMN_ID, ['eq' => $oeordhId]);

        $collectionCount = $collection->getSize();
        $this->log('getById()', [
            'select'          => $collection->getSelect(),
            'collectionCount' => $collectionCount
        ]);

        // If we find no records, log it and return.
        if ($collectionCount === 0) {
            $this->log('getById() - No OEORDH records found.');

            return null;
        }

        // If we find multiple records, log and return.
        if ($collectionCount > 1) {
            $this->log('getById() - More than one OEORDH record found.');

            return null;
        }

        $oeordh = $collection->getFirstItem();
        if ($oeordh instanceof Oeordh) {
            return $oeordh;
        }

        return null;
    }

    public function getByOrderUniquifier(
        int $orderUniquifier
    ) {
        $this->log('getByOrderUniquifier()', ['orderUniquifier' => $orderUniquifier]);

        $collection = $this->oeordhCollectionFactory->create()
            ->addFieldToFilter(Oeordh::COLUMN_ORDUNIQ, $orderUniquifier);

        $collectionCount = $collection->getSize();
        $this->log('getByOrderUniquifier()', ['collectionCount' => $collectionCount]);

        if ($collectionCount === 1) {
            $oeordh = $collection->getFirstItem();
            if ($oeordh instanceof Oeordh) {
                return $oeordh;
            }
        }

        return null;
    }

    public function getList(
        SearchCriteriaInterface $searchCriteria
    ) {
        $this->log('getList()');

        /** @var \ECInternet\Sage300Account\Api\Data\OeordhSearchResultsInterface $searchResults */
        $searchResults = $this->searchResultsFactory->create();

        /** @noinspection PhpExpressionResultUnusedInspection */
        $searchResults->setSearchCriteria($searchCriteria);

        /** @var \ECInternet\Sage300Account\Model\ResourceModel\Oeordh\Collection $collection */
        $collection = $this->oeordhCollectionFactory->create();
        $this->collectionProcessor->process($searchCriteria, $collection);

        $searchResults->setTotalCount($collection->getSize());

        $collection->setCurPage($searchCriteria->getCurrentPage());
        $collection->setPageSize($searchCriteria->getPageSize());
        $searchResults->setItems($collection->getData());

        return $searchResults;
    }

    public function deleteById(int $oeordhId)
    {
        if ($oeordh = $this->getById($oeordhId)) {
            try {
                $this->resourceModel->delete($oeordh);

                return true;
            } catch (Exception $e) {
                $this->log('deleteById()', ['oeordh' => $oeordhId, 'error' => $e->getMessage()]);
            }
        }

        return false;
    }

    /**
     * Validate OEORDH record
     *
     * @param \ECInternet\Sage300Account\Api\Data\OeordhInterface $oeordh
     *
     * @return void
     */
    protected function validate(
        OeordhInterface $oeordh
    ) {
    }

    /**
     * Does OEORDH record exist?
     *
     * @param \ECInternet\Sage300Account\Api\Data\OeordhInterface $oeordh
     *
     * @return bool
     */
    private function doesRecordExist(
        OeordhInterface $oeordh
    ) {
        $this->log('doesRecordExist()', [
            Oeordh::COLUMN_ORDUNIQ => $oeordh->getOrderUniquifier(),
        ]);

        /** @var \ECInternet\Sage300Account\Model\ResourceModel\Oeordh\Collection $collection */
        $collection = $this->oeordhCollectionFactory->create()
            ->addFieldToFilter(Oeordh::COLUMN_ORDUNIQ, $oeordh->getOrderUniquifier());

        return $collection->getSize() > 0;
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
        $this->logger->info('Model/OeordhRepository - ' . $message, $extra);
    }
}
