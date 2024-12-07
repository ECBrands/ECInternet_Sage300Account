<?php
/**
 * Copyright (C) EC Brands Corporation - All Rights Reserved
 * Contact Licensing@ECInternet.com for use guidelines
 */
declare(strict_types=1);

namespace ECInternet\Sage300Account\Model;

use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use ECInternet\Sage300Account\Api\Data\PoporlInterface;
use ECInternet\Sage300Account\Api\Data\PoporlSearchResultsInterfaceFactory;
use ECInternet\Sage300Account\Api\PoporlRepositoryInterface;
use ECInternet\Sage300Account\Logger\Logger;
use ECInternet\Sage300Account\Model\Data\Poporl;
use ECInternet\Sage300Account\Model\ResourceModel\Poporl as PoporlResource;
use ECInternet\Sage300Account\Model\ResourceModel\Poporl\CollectionFactory as PoporlCollectionFactory;
use Exception;

/**
 * Poporl model repository
 *
 * @SuppressWarnings(PHPMD.LongVariable)
 * @SuppressWarnings(PHPMD.ShortVariable)
 */
class PoporlRepository implements PoporlRepositoryInterface
{
    /**
     * @var \Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface
     */
    private $collectionProcessor;

    /**
     * @var \ECInternet\Sage300Account\Api\Data\PoporlSearchResultsInterfaceFactory
     */
    protected $searchResultsFactory;

    /**
     * @var \ECInternet\Sage300Account\Logger\Logger
     */
    protected $logger;

    /**
     * @var \ECInternet\Sage300Account\Model\ResourceModel\Poporl
     */
    protected $resourceModel;

    /**
     * @var \ECInternet\Sage300Account\Model\ResourceModel\Poporl\CollectionFactory
     */
    protected $poporlCollectionFactory;

    /**
     * PoporlRepository constructor.
     *
     * @param \Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface      $collectionProcessor
     * @param \ECInternet\Sage300Account\Api\Data\PoporlSearchResultsInterfaceFactory $poporlSearchResultsFactory
     * @param \ECInternet\Sage300Account\Logger\Logger                                $logger
     * @param \ECInternet\Sage300Account\Model\ResourceModel\Poporl                   $resourceModel
     * @param \ECInternet\Sage300Account\Model\ResourceModel\Poporl\CollectionFactory $poporlCollection
     */
    public function __construct(
        CollectionProcessorInterface $collectionProcessor,
        PoporlSearchResultsInterfaceFactory $poporlSearchResultsFactory,
        Logger $logger,
        PoporlResource $resourceModel,
        PoporlCollectionFactory $poporlCollection
    ) {
        $this->collectionProcessor     = $collectionProcessor;
        $this->searchResultsFactory    = $poporlSearchResultsFactory;
        $this->logger                  = $logger;
        $this->resourceModel           = $resourceModel;
        $this->poporlCollectionFactory = $poporlCollection;
    }

    public function save(
        PoporlInterface $poporl
    ) {
        $this->log('save()', ['poporl' => $poporl->getData()]);

        if (!$this->validate($poporl)) {
            $this->log('save() - Failed validation.');
            return false;
        }

        // If we find existing, grab the ID and set on incoming record
        if ($this->doesRecordExist($poporl)) {
            $model = $this->get((int)$poporl->getPurchaseOrderSequenceKey(), $poporl->getLineNumber());
            $poporl->setId($model->getId());
        }

        try {
            $this->resourceModel->save($poporl);
        } catch (Exception $e) {
            $this->log('save()', [
                'class'     => get_class($e),
                'exception' => $e->getMessage(),
                'trace'     => $e->getTraceAsString()
            ]);

            throw $e;
        }

        return $poporl;
    }

    public function bulkSave(
        array $poporlArray
    ) {
        $this->log('bulkSave()');

        $results = [];

        foreach ($poporlArray as $poporl) {
            //$this->log('bulkSave()', ['poporl' => $poporl->getData()]);

            try {
                $this->save($poporl);
                $results[] = true;
            } catch (Exception $e) {
                $this->log('bulkSave()', ['exception' => $e->getMessage()]);
                $results[] = false;
            }
        }

        return $results;
    }

    public function get(
        $sequenceKey,
        $lineNumber
    ) {
        $this->log('get()', ['sequenceKey' => $sequenceKey, 'lineNumber' => $lineNumber]);

        /** @var \ECInternet\Sage300Account\Model\ResourceModel\Poporl\Collection $collection */
        $collection = $this->poporlCollectionFactory->create()
            ->addFieldToFilter(Poporl::COLUMN_PORHSEQ, $sequenceKey)
            ->addFieldToFilter(Poporl::COLUMN_PORLREV, $lineNumber);

        $collectionCount = $collection->getSize();
        $this->log('get()', [
            'select'          => $collection->getSelect(),
            'collectionCount' => $collectionCount
        ]);

        if ($collectionCount === 1) {
            $poporl = $collection->getFirstItem();
            if ($poporl instanceof Poporl) {
                return $poporl;
            }
        }

        return null;
    }

    public function getById(
        int $poporlId
    ) {
        $this->log('getById()', ['poporlId' => $poporlId]);

        /** @var \ECInternet\Sage300Account\Model\ResourceModel\Poporl\Collection $collection */
        $collection = $this->poporlCollectionFactory->create()
            ->addFieldToFilter(Poporl::COLUMN_ID, $poporlId);

        $collectionCount = $collection->getSize();
        $this->log('getById()', [
            'select'          => $collection->getSelect(),
            'collectionCount' => $collectionCount
        ]);

        if ($collectionCount === 1) {
            $poporl = $collection->getFirstItem();
            if ($poporl instanceof Poporl) {
                return $poporl;
            }
        }

        return null;
    }

    public function getList(
        SearchCriteriaInterface $searchCriteria
    ) {
        $this->log('getList()');

        /** @var \ECInternet\Sage300Account\Api\Data\PoporlSearchResultsInterface $searchResults */
        $searchResults = $this->searchResultsFactory->create();

        $searchResults->setSearchCriteria($searchCriteria);

        /** @var \ECInternet\Sage300Account\Model\ResourceModel\Poporl\Collection $collection */
        $collection = $this->poporlCollectionFactory->create();
        $this->collectionProcessor->process($searchCriteria, $collection);

        $searchResults->setTotalCount($collection->getSize());

        $collection->setCurPage($searchCriteria->getCurrentPage());
        $collection->setPageSize($searchCriteria->getPageSize());
        $searchResults->setItems($collection->getData());

        return $searchResults;
    }

    public function deleteById($poporlId)
    {
        if ($poporl = $this->getById($poporlId)) {
            try {
                $this->resourceModel->delete($poporl);

                return true;
            } catch (Exception $e) {
                $this->log('deleteById()', ['error' => $e->getMessage()]);
            }
        }

        return false;
    }

    public function deactivateById($poporlId)
    {
        $this->log('deactivateById()', ['poporlId' => $poporlId]);

        if ($poporl = $this->getById($poporlId)) {
            $poporl->setIsActive(false);

            try {
                $this->resourceModel->save($poporl);
                return true;
            } catch (Exception $e) {
                $this->log('deactivateById()', ['poporlId' => $poporlId, 'error' => $e->getMessage()]);
            }
        }

        return false;
    }

    /**
     * Validate POPORL record
     *
     * @param \ECInternet\Sage300Account\Api\Data\PoporlInterface $poporl
     *
     * @return bool
     */
    protected function validate(
        PoporlInterface $poporl
    ) {
        return true;
    }

    /**
     * Does POPORL record exist?
     *
     * @param \ECInternet\Sage300Account\Api\Data\PoporlInterface $poporl
     *
     * @return bool
     */
    protected function doesRecordExist(
        PoporlInterface $poporl
    ) {
        $this->log('doesRecordExist()', [
            Poporl::COLUMN_PORHSEQ => $poporl->getPurchaseOrderSequenceKey(),
            Poporl::COLUMN_PORLREV => $poporl->getLineNumber()
        ]);

        /** @var \ECInternet\Sage300Account\Model\ResourceModel\Poporh\Collection $collection */
        $collection = $this->poporlCollectionFactory->create()
            ->addFieldToFilter(Poporl::COLUMN_PORHSEQ, $poporl->getPurchaseOrderSequenceKey())
            ->addFieldToFilter(Poporl::COLUMN_PORLREV, $poporl->getLineNumber());

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
    protected function log(string $message, array $extra = [])
    {
        $this->logger->info('Model/PoporlRepository - ' . $message, $extra);
    }
}
