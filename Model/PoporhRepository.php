<?php
/**
 * Copyright (C) EC Brands Corporation - All Rights Reserved
 * Contact Licensing@ECInternet.com for use guidelines
 */
declare(strict_types=1);

namespace ECInternet\Sage300Account\Model;

use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use ECInternet\Sage300Account\Api\Data\PoporhInterface;
use ECInternet\Sage300Account\Api\Data\PoporhSearchResultsInterfaceFactory;
use ECInternet\Sage300Account\Api\PoporhRepositoryInterface;
use ECInternet\Sage300Account\Logger\Logger;
use ECInternet\Sage300Account\Model\Data\Poporh;
use ECInternet\Sage300Account\Model\Data\Poporl;
use ECInternet\Sage300Account\Model\ResourceModel\Poporh as PoporhResource;
use ECInternet\Sage300Account\Model\ResourceModel\Poporh\CollectionFactory as PoporhCollectionFactory;
use ECInternet\Sage300Account\Model\ResourceModel\Poporl\CollectionFactory as PoporlCollectionFactory;
use Exception;

/**
 * Poporh Model Repository
 *
 * @SuppressWarnings(PHPMD.LongVariable)
 * @SuppressWarnings(PHPMD.ShortVariable)
 */
class PoporhRepository implements PoporhRepositoryInterface
{
    /**
     * @var \Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface
     */
    private $collectionProcessor;

    /**
     * @var \ECInternet\Sage300Account\Api\Data\PoporhSearchResultsInterfaceFactory
     */
    protected $searchResultsFactory;

    /**
     * @var \ECInternet\Sage300Account\Logger\Logger
     */
    protected $logger;

    /**
     * @var \ECInternet\Sage300Account\Model\ResourceModel\Poporh
     */
    private $resourceModel;

    /**
     * @var \ECInternet\Sage300Account\Model\ResourceModel\Poporh\CollectionFactory
     */
    private $poporhCollectionFactory;

    /**
     * @var \ECInternet\Sage300Account\Model\ResourceModel\Poporl\CollectionFactory
     */
    private $poporlCollectionFactory;

    /**
     * PoporhRepository constructor.
     *
     * @param \Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface      $collectionProcessor
     * @param \ECInternet\Sage300Account\Api\Data\PoporhSearchResultsInterfaceFactory $poporhSearchResultsFactory
     * @param \ECInternet\Sage300Account\Logger\Logger                                $logger
     * @param \ECInternet\Sage300Account\Model\ResourceModel\Poporh                   $resourceModel
     * @param \ECInternet\Sage300Account\Model\ResourceModel\Poporh\CollectionFactory $poporhCollectionFactory
     * @param \ECInternet\Sage300Account\Model\ResourceModel\Poporl\CollectionFactory $poporlCollectionFactory
     */
    public function __construct(
        CollectionProcessorInterface $collectionProcessor,
        PoporhSearchResultsInterfaceFactory $poporhSearchResultsFactory,
        Logger $logger,
        PoporhResource $resourceModel,
        PoporhCollectionFactory $poporhCollectionFactory,
        PoporlCollectionFactory $poporlCollectionFactory
    ) {
        $this->collectionProcessor     = $collectionProcessor;
        $this->searchResultsFactory    = $poporhSearchResultsFactory;
        $this->logger                  = $logger;
        $this->resourceModel           = $resourceModel;
        $this->poporhCollectionFactory = $poporhCollectionFactory;
        $this->poporlCollectionFactory = $poporlCollectionFactory;
    }

    public function save(
        PoporhInterface $poporh
    ) {
        //$this->log('save()', ['poporh' => $poporh->getData()]);

        if (!$this->validate($poporh)) {
            return false;
        }

        // If we find existing, grab the ID and set on incoming record
        if ($this->doesRecordExist($poporh)) {
            $model = $this->get($poporh->getPurchaseOrderSequenceKey());
            $poporh->setId($model->getId());
        }

        try {
            $this->resourceModel->save($poporh);
        } catch (Exception $e) {
            $this->log('save()', [
                'class'     => get_class($e),
                'exception' => $e->getMessage(),
                'trace'     => $e->getTraceAsString()
            ]);

            throw $e;
        }

        return $poporh;
    }

    public function bulkSave(
        array $poporhArray
    ) {
        $this->log('bulkSave()');

        $results = [];

        foreach ($poporhArray as $poporh) {
            $this->log('bulkSave()', ['poporh' => $poporh->getData()]);

            try {
                $this->save($poporh);
                $results[] = true;
            } catch (Exception $e) {
                $this->log('bulkSave()', ['exception' => $e->getMessage()]);
                $results[] = false;
            }
        }

        return $results;
    }

    public function get(
        $sequenceKey
    ) {
        //$this->log('get()', ['sequenceKey' => $sequenceKey]);

        /** @var \ECInternet\Sage300Account\Model\ResourceModel\Poporh\Collection $collection */
        $collection = $this->poporhCollectionFactory->create()
            ->addFieldToSelect('*')
            ->addFieldToFilter(Poporh::COLUMN_PORHSEQ, $sequenceKey);

        $collectionCount = $collection->getSize();
        $this->log('get()', [
            'select'          => $collection->getSelect(),
            'collectionCount' => $collectionCount
        ]);

        if ($collectionCount === 1) {
            $poporh = $collection->getFirstItem();
            if ($poporh instanceof Poporh) {
                return $poporh;
            }
        }

        return null;
    }

    public function getById(
        int $poporhId
    ) {
        $this->log('getById()', ['id' => $poporhId]);

        /** @var \ECInternet\Sage300Account\Model\ResourceModel\Poporh\Collection $collection */
        $collection = $this->poporhCollectionFactory->create()
            ->addFieldToFilter(Poporh::COLUMN_ID, $poporhId);

        $collectionCount = $collection->getSize();
        //$this->log('get()', ['collectionCount' => $collectionCount]);

        if ($collectionCount === 1) {
            $poporh = $collection->getFirstItem();
            if ($poporh instanceof Poporh) {
                return $poporh;
            }
        }

        return null;
    }

    /**
     * Get By Line Id
     *
     * @param int $lineId
     *
     * @return \ECInternet\Sage300Account\Api\Data\PoporhInterface|null
     */
    public function getByLineId(int $lineId)
    {
        $this->log('getByLineId()', ['lineId' => $lineId]);

        /** @var \ECInternet\Sage300Account\Model\ResourceModel\Poporl\Collection $poporlCollection */
        $poporlCollection = $this->poporlCollectionFactory->create()
            ->addFieldToFilter(Poporl::COLUMN_ID, $lineId);

        $poporlCollectionCount = $poporlCollection->getSize();
        //$this->log('getByLineId()', ['poporlCollectionCount' => $poporlCollectionCount]);

        if ($poporlCollectionCount === 1) {
            /** @var \ECInternet\Sage300Account\Model\Data\Poporl $poporl */
            $poporl = $poporlCollection->getFirstItem();

            $sequenceKey = $poporl->getPurchaseOrderSequenceKey();

            $poporhCollection = $this->poporhCollectionFactory->create()
                ->addFieldToFilter(Poporh::COLUMN_PORHSEQ, $sequenceKey);

            $poporhCollectionCount = $poporhCollection->getSize();
            //$this->log('getByLineId()', ['poporhCollectionCount' => $poporhCollectionCount]);

            if ($poporhCollectionCount === 1) {
                $poporh = $poporhCollection->getFirstItem();
                if ($poporh instanceof Poporh) {
                    return $poporh;
                }
            }
        }

        return null;
    }

    /**
     * @param \ECInternet\Sage300Account\Api\Data\PoporhInterface $poporh
     *
     * @return \ECInternet\Sage300Account\Model\Data\Poporl[]
     */
    public function getDetailLines(
        PoporhInterface $poporh
    ) {
        $detailLines = [];

        /** @var \ECInternet\Sage300Account\Model\ResourceModel\Poporl\Collection $collection */
        $collection = $this->poporlCollectionFactory->create()
            ->addFieldToSelect('*')
            ->addFieldToFilter(Poporl::COLUMN_PORHSEQ, $poporh->getPurchaseOrderSequenceKey());

        /** @var \ECInternet\Sage300Account\Model\Data\Poporl $detailLine */
        foreach ($collection->getItems() as $detailLine) {
            $detailLines[] = $detailLine;
        }

        return $detailLines;
    }

    public function getList(
        SearchCriteriaInterface $searchCriteria
    ) {
        $this->log('getList()');

        /** @var \ECInternet\Sage300Account\Api\Data\PoporhSearchResultsInterface $searchResults */
        $searchResults = $this->searchResultsFactory->create();

        $searchResults->setSearchCriteria($searchCriteria);

        /** @var \ECInternet\Sage300Account\Model\ResourceModel\Poporh\Collection $collection */
        $collection = $this->poporhCollectionFactory->create();
        $this->collectionProcessor->process($searchCriteria, $collection);

        $searchResults->setTotalCount($collection->getSize());

        $collection->setCurPage($searchCriteria->getCurrentPage());
        $collection->setPageSize($searchCriteria->getPageSize());
        $searchResults->setItems($collection->getData());

        return $searchResults;
    }

    public function deleteById(int $poporhId)
    {
        if ($poporh = $this->getById($poporhId)) {
            try {
                $this->resourceModel->delete($poporh);

                return true;
            } catch (Exception $e) {
                $this->log('deleteById()', ['poporhId' => $poporhId, 'error' => $e->getMessage()]);
            }
        }

        return false;
    }
    
    /**
     * Validate POPORH record
     *
     * @param \ECInternet\Sage300Account\Api\Data\PoporhInterface $poporh
     *
     * @return bool
     */
    protected function validate(
        PoporhInterface $poporh
    ) {
        return true;
    }

    /**
     * Does POPORH record exist?
     *
     * @param \ECInternet\Sage300Account\Api\Data\PoporhInterface $poporh
     *
     * @return bool
     */
    protected function doesRecordExist(
        PoporhInterface $poporh
    ) {
        $this->log('doesRecordExist()', [
            Poporh::COLUMN_PORHSEQ => $poporh->getPurchaseOrderSequenceKey()
        ]);

        /** @var \ECInternet\Sage300Account\Model\ResourceModel\Poporh\Collection $collection */
        $collection = $this->poporhCollectionFactory->create()
            ->addFieldToFilter(Poporh::COLUMN_PORHSEQ, $poporh->getPurchaseOrderSequenceKey());

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
        $this->logger->info('Model/PoporhRepository - ' . $message, $extra);
    }
}
