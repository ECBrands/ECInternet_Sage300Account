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
use ECInternet\Sage300Account\Model\Data\Poporl;
use ECInternet\Sage300Account\Model\ResourceModel\Poporl as PoporlResource;
use ECInternet\Sage300Account\Model\ResourceModel\Poporl\CollectionFactory as PoporlCollectionFactory;
use Exception;
use Psr\Log\LoggerInterface;

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
     * @var \ECInternet\Sage300Account\Model\ResourceModel\Poporl
     */
    protected $resourceModel;

    /**
     * @var \ECInternet\Sage300Account\Model\ResourceModel\Poporl\CollectionFactory
     */
    protected $poporlCollectionFactory;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * PoporlRepository constructor.
     *
     * @param \Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface      $collectionProcessor
     * @param \ECInternet\Sage300Account\Api\Data\PoporlSearchResultsInterfaceFactory $poporlSearchResultsFactory
     * @param \ECInternet\Sage300Account\Model\ResourceModel\Poporl                   $resourceModel
     * @param \ECInternet\Sage300Account\Model\ResourceModel\Poporl\CollectionFactory $poporlCollection
     * @param \Psr\Log\LoggerInterface                                                $logger
     */
    public function __construct(
        CollectionProcessorInterface $collectionProcessor,
        PoporlSearchResultsInterfaceFactory $poporlSearchResultsFactory,
        PoporlResource $resourceModel,
        PoporlCollectionFactory $poporlCollection,
        LoggerInterface $logger
    ) {
        $this->collectionProcessor     = $collectionProcessor;
        $this->searchResultsFactory    = $poporlSearchResultsFactory;
        $this->resourceModel           = $resourceModel;
        $this->poporlCollectionFactory = $poporlCollection;
        $this->logger                  = $logger;
    }

    public function save(
        PoporlInterface $poporl
    ) {
        if (!$this->validate($poporl)) {
            $this->log('save() - Failed validation.');
            return false;
        }

        // If we find existing, grab the ID and set on incoming record
        if ($this->doesRecordExist($poporl)) {
            $model = $this->get((int)$poporl->getPurchaseOrderSequenceKey(), (int)$poporl->getLineNumber());
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
        $results = [];

        foreach ($poporlArray as $poporl) {
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
        /** @var \ECInternet\Sage300Account\Model\ResourceModel\Poporl\Collection $collection */
        $collection = $this->poporlCollectionFactory->create()
            ->addFieldToFilter(Poporl::COLUMN_PORHSEQ, $sequenceKey)
            ->addFieldToFilter(Poporl::COLUMN_PORLREV, $lineNumber);

        $collectionCount = $collection->getSize();
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
        /** @var \ECInternet\Sage300Account\Model\ResourceModel\Poporl\Collection $collection */
        $collection = $this->poporlCollectionFactory->create()
            ->addFieldToFilter(Poporl::COLUMN_ID, $poporlId);

        $collectionCount = $collection->getSize();
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
                $this->log('deleteById()', ['exception' => $e->getMessage()]);
            }
        }

        return false;
    }

    public function deactivateById($poporlId)
    {
        if ($poporl = $this->getById($poporlId)) {
            $poporl->setIsActive(false);

            try {
                $this->resourceModel->save($poporl);
                return true;
            } catch (Exception $e) {
                $this->log('deactivateById()', [
                    'poporlId'  => $poporlId,
                    'exception' => $e->getMessage()
                ]);
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
        return (
            !empty($poporl->getPurchaseOrderSequenceKey()) &&
            !empty($poporl->getPurchaseOrderLineSequenceKey())
        );
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
