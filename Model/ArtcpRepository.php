<?php
/**
 * Copyright (C) EC Brands Corporation - All Rights Reserved
 * Contact Licensing@ECInternet.com for use guidelines
 */
declare(strict_types=1);

namespace ECInternet\Sage300Account\Model;

use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use ECInternet\Sage300Account\Api\ArtcpRepositoryInterface;
use ECInternet\Sage300Account\Api\Data\ArtcpInterface;
use ECInternet\Sage300Account\Api\Data\ArtcpSearchResultsInterfaceFactory;
use ECInternet\Sage300Account\Logger\Logger;
use ECInternet\Sage300Account\Model\Data\Artcp;
use ECInternet\Sage300Account\Model\ResourceModel\Artcp as ArtcpResource;
use ECInternet\Sage300Account\Model\ResourceModel\Artcp\CollectionFactory as ArtcpCollectionFactory;
use Exception;

/**
 * Artcp model repository
 */
class ArtcpRepository implements ArtcpRepositoryInterface
{
    /**
     * @var \Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface
     */
    private $collectionProcessor;

    /**
     * @var \ECInternet\Sage300Account\Api\Data\ArtcpSearchResultsInterfaceFactory
     */
    protected $searchResultsFactory;

    /**
     * @var \ECInternet\Sage300Account\Logger\Logger
     */
    private $logger;

    /**
     * @var \ECInternet\Sage300Account\Model\ResourceModel\Artcp
     */
    private $resourceModel;

    /**
     * @var \ECInternet\Sage300Account\Model\ResourceModel\Artcp\CollectionFactory
     */
    private $artcpCollectionFactory;

    /**
     * ArtcpRepository constructor.
     *
     * @param \Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface     $collectionProcessor
     * @param \ECInternet\Sage300Account\Api\Data\ArtcpSearchResultsInterfaceFactory $artcpSearchResultsFactory
     * @param \ECInternet\Sage300Account\Logger\Logger                               $logger
     * @param \ECInternet\Sage300Account\Model\ResourceModel\Artcp                   $resourceModel
     * @param \ECInternet\Sage300Account\Model\ResourceModel\Artcp\CollectionFactory $artcpCollectionFactory
     */
    public function __construct(
        CollectionProcessorInterface $collectionProcessor,
        ArtcpSearchResultsInterfaceFactory $artcpSearchResultsFactory,
        Logger $logger,
        ArtcpResource $resourceModel,
        ArtcpCollectionFactory $artcpCollectionFactory
    ) {
        $this->collectionProcessor    = $collectionProcessor;
        $this->searchResultsFactory   = $artcpSearchResultsFactory;
        $this->logger                 = $logger;
        $this->resourceModel          = $resourceModel;
        $this->artcpCollectionFactory = $artcpCollectionFactory;
    }

    public function save(
        ArtcpInterface $artcp
    ) {
        $this->log('save()', ['artcp' => $artcp->getData()]);

        $this->validate($artcp);

        // If we find existing, grab the ID and set on incoming record
        if ($this->doesRecordExist($artcp)) {
            $model = $this->get(
                $artcp->getBatchType(),
                $artcp->getBatchNumber(),
                $artcp->getEntryNumber(),
                $artcp->getLineNumber()
            );
            $artcp->setId($model->getId());
        }

        try {
            $this->resourceModel->save($artcp);
        } catch (Exception $e) {
            $this->log('save()', [
                'class'     => get_class($e),
                'exception' => $e->getMessage(),
                'trace'     => $e->getTraceAsString()
            ]);

            throw $e;
        }

        return $artcp;
    }

    public function bulkSave(
        array $artcpArray
    ) {
        $this->log('bulkSave()');

        $results = [];

        foreach ($artcpArray as $artcp) {
            $this->log('bulkSave()', ['artcp' => $artcp->getData()]);

            try {
                $this->save($artcp);
                $results[] = true;
            } catch (Exception $e) {
                $this->log('bulkSave()', ['exception' => $e->getMessage()]);
                $results[] = false;
            }
        }

        return $results;
    }

    public function get(
        string $batchType,
        int $batchNumber,
        int $entryNumber,
        int $lineNumber
    ) {
        $this->log('get()', [
            'batchType'   => $batchType,
            'batchNumber' => $batchNumber,
            'entryNumber' => $entryNumber,
            'lineNumber'  => $lineNumber
        ]);

        /** @var \ECInternet\Sage300Account\Model\ResourceModel\Artcp\Collection $collection */
        $collection = $this->artcpCollectionFactory->create()
            ->addFieldToFilter(Artcp::COLUMN_CODEPAYM, $batchType)
            ->addFieldToFilter(Artcp::COLUMN_CNTBTCH, $batchNumber)
            ->addFieldToFilter(Artcp::COLUMN_CNTITEM, $entryNumber)
            ->addFieldToFilter(Artcp::COLUMN_CNTLINE, $lineNumber);

        $collectionCount = $collection->getSize();
        $this->log('get()', [
            'select'          => $collection->getSelect(),
            'collectionCount' => $collectionCount
        ]);

        // If we find no records, log it and return.
        if ($collectionCount === 0) {
            $this->log('get() - No ARTCP records found.');

            return null;
        }

        // If we find multiple records, log and return.
        if ($collectionCount > 1) {
            $this->log('get() - More than one ARTCP record found.');

            return null;
        }

        $artcp = $collection->getFirstItem();
        if ($artcp instanceof Artcp) {
            return $artcp;
        }

        return null;
    }

    public function getById(
        int $artcpId
    ) {
        $this->log('getById()', ['oeorddId' => $artcpId]);

        /** @var \ECInternet\Sage300Account\Model\ResourceModel\Poporl\Collection $collection */
        $collection = $this->artcpCollectionFactory->create()
            ->addFieldToFilter(Artcp::COLUMN_ID, $artcpId);

        $collectionCount = $collection->getSize();
        $this->log('getById()', [
            'select'          => $collection->getSelect(),
            'collectionCount' => $collectionCount
        ]);

        if ($collectionCount === 1) {
            $artcp = $collection->getFirstItem();
            if ($artcp instanceof Artcp) {
                return $artcp;
            }
        }

        return null;
    }

    public function getList(
        SearchCriteriaInterface $searchCriteria
    ) {
        $this->log('getList()');

        /** @var \ECInternet\Sage300Account\Api\Data\ArtcpSearchResultsInterface $searchResults */
        $searchResults = $this->searchResultsFactory->create();

        $searchResults->setSearchCriteria($searchCriteria);

        /** @var \ECInternet\Sage300Account\Model\ResourceModel\Artcp\Collection $collection */
        $collection = $this->artcpCollectionFactory->create();
        $this->collectionProcessor->process($searchCriteria, $collection);

        $searchResults->setTotalCount($collection->getSize());

        $collection->setCurPage($searchCriteria->getCurrentPage());
        $collection->setPageSize($searchCriteria->getPageSize());
        $searchResults->setItems($collection->getData());

        return $searchResults;
    }

    public function deleteById($artcpId)
    {
        if ($artcp = $this->getById($artcpId)) {
            try {
                $this->resourceModel->delete($artcp);

                return true;
            } catch (Exception $e) {
                $this->log('deleteById()', ['error' => $e->getMessage()]);
            }
        }

        return false;
    }

    /**
     * Validate ARTCP record
     *
     * @param \ECInternet\Sage300Account\Api\Data\ArtcpInterface $artcp
     *
     * @return void
     */
    protected function validate(
        ArtcpInterface $artcp
    ) {
    }

    /**
     * Does ARTCP record exist?
     *
     * @param \ECInternet\Sage300Account\Api\Data\ArtcpInterface $artcp
     *
     * @return bool
     */
    private function doesRecordExist(
        ArtcpInterface $artcp
    ) {
        $this->log('doesRecordExist()', [
            Artcp::COLUMN_CODEPAYM => $artcp->getBatchType(),
            Artcp::COLUMN_CNTBTCH  => $artcp->getBatchNumber(),
            Artcp::COLUMN_CNTITEM  => $artcp->getEntryNumber(),
            Artcp::COLUMN_CNTLINE  => $artcp->getLineNumber()
        ]);

        /** @var \ECInternet\Sage300Account\Model\ResourceModel\Artcp\Collection $collection */
        $collection = $this->artcpCollectionFactory->create()
            ->addFieldToFilter(Artcp::COLUMN_CODEPAYM, $artcp->getBatchType())
            ->addFieldToFilter(Artcp::COLUMN_CNTBTCH, $artcp->getBatchNumber())
            ->addFieldToFilter(Artcp::COLUMN_CNTITEM, $artcp->getEntryNumber())
            ->addFieldToFilter(Artcp::COLUMN_CNTLINE, $artcp->getLineNumber());

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
        $this->logger->info('Model/ArtcpRepository - ' . $message, $extra);
    }
}
