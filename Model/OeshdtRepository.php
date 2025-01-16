<?php
/**
 * Copyright (C) EC Brands Corporation - All Rights Reserved
 * Contact Licensing@ECInternet.com for use guidelines
 */
declare(strict_types=1);

namespace ECInternet\Sage300Account\Model;

use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Exception\LocalizedException;
use ECInternet\Sage300Account\Api\Data\OeshdtInterface;
use ECInternet\Sage300Account\Api\Data\OeshdtSearchResultsInterfaceFactory;
use ECInternet\Sage300Account\Api\OeshdtRepositoryInterface;
use ECInternet\Sage300Account\Logger\Logger;
use ECInternet\Sage300Account\Model\Data\Oeshdt;
use ECInternet\Sage300Account\Model\ResourceModel\Oeshdt as OeshdtResource;
use ECInternet\Sage300Account\Model\ResourceModel\Oeshdt\CollectionFactory as OeshdtCollectionFactory;
use Exception;

/**
 * Oeshdt model repository
 *
 * @SuppressWarnings(PHPMD.LongVariable)
 * @SuppressWarnings(PHPMD.ShortVariable)
 */
class OeshdtRepository implements OeshdtRepositoryInterface
{
    /**
     * @var \Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface
     */
    private $collectionProcessor;

    /**
     * @var \ECInternet\Sage300Account\Api\Data\OeshdtSearchResultsInterfaceFactory
     */
    protected $searchResultsFactory;

    /**
     * @var \ECInternet\Sage300Account\Logger\Logger
     */
    private $logger;

    /**
     * @var \ECInternet\Sage300Account\Model\ResourceModel\Oeshdt
     */
    private $resourceModel;

    /**
     * @var \ECInternet\Sage300Account\Model\ResourceModel\Oeshdt\CollectionFactory
     */
    private $oeshdtCollectionFactory;

    /**
     * OeshdtRepository constructor.
     *
     * @param \Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface      $collectionProcessor
     * @param \ECInternet\Sage300Account\Api\Data\OeshdtSearchResultsInterfaceFactory $oeshdtSearchResultsFactory
     * @param \ECInternet\Sage300Account\Logger\Logger                                $logger
     * @param \ECInternet\Sage300Account\Model\ResourceModel\Oeshdt                   $resourceModel
     * @param \ECInternet\Sage300Account\Model\ResourceModel\Oeshdt\CollectionFactory $oeshdtCollectionFactory
     */
    public function __construct(
        CollectionProcessorInterface $collectionProcessor,
        OeshdtSearchResultsInterfaceFactory $oeshdtSearchResultsFactory,
        Logger $logger,
        OeshdtResource $resourceModel,
        OeshdtCollectionFactory $oeshdtCollectionFactory
    ) {
        $this->collectionProcessor     = $collectionProcessor;
        $this->searchResultsFactory    = $oeshdtSearchResultsFactory;
        $this->logger                  = $logger;
        $this->resourceModel           = $resourceModel;
        $this->oeshdtCollectionFactory = $oeshdtCollectionFactory;
    }

    public function save(
        OeshdtInterface $oeshdt
    ) {
        $this->log('save()', ['oeshdt' => $oeshdt->getData()]);

        $this->validate($oeshdt);

        // If we find existing, grab the ID and set on incoming record
        if ($this->doesRecordExist($oeshdt)) {
            $model = $this->get($oeshdt->getCustomerNumber(), $oeshdt->getItemNumber());
            $oeshdt->setId($model->getId());
        }

        try {
            $this->resourceModel->save($oeshdt);
        } catch (Exception $e) {
            $this->log('save()', [
                'class'     => get_class($e),
                'exception' => $e->getMessage(),
                'trace'     => $e->getTraceAsString()
            ]);

            throw $e;
        }

        return $oeshdt;
    }

    public function bulkSave(
        array $oeshdtArray
    ) {
        $this->log('bulkSave()');

        $results = [];

        foreach ($oeshdtArray as $oeshdt) {
            //$this->log('bulkSave()', ['oeshdt' => $oeshdt->getData()]);

            try {
                $this->save($oeshdt);
                $results[] = true;
            } catch (Exception $e) {
                $this->log('bulkSave()', ['exception' => $e->getMessage()]);
                $results[] = false;
            }
        }

        return $results;
    }

    public function get(
        string $customerNumber,
        string $itemNumber
    ) {
        $this->log('get()', [
            'customerNumber' => $customerNumber,
            'itemNumber'     => $itemNumber
        ]);

        /** @var \ECInternet\Sage300Account\Model\ResourceModel\Oeshdt\Collection $collection */
        $collection = $this->oeshdtCollectionFactory->create()
            ->addFieldToSelect('*')
            ->addFieldToFilter(Oeshdt::COLUMN_CUSTOMER, $customerNumber)
            ->addFieldToFilter(Oeshdt::COLUMN_ITEM, $itemNumber);

        $collectionCount = $collection->getSize();
        $this->log('get()', [
            'select'          => $collection->getSelect(),
            'collectionCount' => $collectionCount
        ]);

        if ($collectionCount === 1) {
            $oeshdt = $collection->getFirstItem();
            if ($oeshdt instanceof Oeshdt) {
                return $oeshdt;
            }
        }

        return null;
    }

    public function getById(
        int $oeshdtId
    ) {
        $this->log('getById()', ['oeshdtId' => $oeshdtId]);

        /** @var \ECInternet\Sage300Account\Model\ResourceModel\Poporl\Collection $collection */
        $collection = $this->oeshdtCollectionFactory->create()
            ->addFieldToFilter(Oeshdt::COLUMN_ID, $oeshdtId);

        $collectionCount = $collection->getSize();
        $this->log('getById()', [
            'select'          => $collection->getSelect(),
            'collectionCount' => $collectionCount
        ]);

        if ($collectionCount === 1) {
            $oeshdt = $collection->getFirstItem();
            if ($oeshdt instanceof Oeshdt) {
                return $oeshdt;
            }
        }

        return null;
    }

    public function getList(
        SearchCriteriaInterface $searchCriteria
    ) {
        $this->log('getList()');

        /** @var \ECInternet\Sage300Account\Api\Data\OeshdtSearchResultsInterface $searchResults */
        $searchResults = $this->searchResultsFactory->create();

        $searchResults->setSearchCriteria($searchCriteria);

        /** @var \ECInternet\Sage300Account\Model\ResourceModel\Oeshdt\Collection $collection */
        $collection = $this->oeshdtCollectionFactory->create();
        $this->collectionProcessor->process($searchCriteria, $collection);

        $searchResults->setTotalCount($collection->getSize());

        $collection->setCurPage($searchCriteria->getCurrentPage());
        $collection->setPageSize($searchCriteria->getPageSize());
        $searchResults->setItems($collection->getData());

        return $searchResults;
    }

    public function deleteById(int $oeshdtId)
    {
        if ($oeshdt = $this->getById($oeshdtId)) {
            try {
                $this->resourceModel->delete($oeshdt);

                return true;
            } catch (Exception $e) {
                $this->log('deleteById()', ['oeshdtId' => $oeshdtId, 'error' => $e->getMessage()]);
            }
        }

        return false;
    }

    /**
     * Validate OESHDT record
     *
     * @param \ECInternet\Sage300Account\Api\Data\OeshdtInterface $oeshdt
     *
     * @return void
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function validate(
        OeshdtInterface $oeshdt
    ) {
        if (empty($oeshdt->getCustomerNumber())) {
            throw new LocalizedException(__('CustomerNumber not set'));
        }

        if (empty($oeshdt->getItemNumber())) {
            throw new LocalizedException(__('ItemNumber not set'));
        }
    }

    /**
     * Does OESHDT record exist?
     *
     * @param \ECInternet\Sage300Account\Api\Data\OeshdtInterface $oeshdt
     *
     * @return bool
     */
    private function doesRecordExist(
        OeshdtInterface $oeshdt
    ) {
        $this->log('doesRecordExist()', [
            Oeshdt::COLUMN_CUSTOMER => $oeshdt->getCustomerNumber(),
            Oeshdt::COLUMN_ITEM     => $oeshdt->getItemNumber()
        ]);

        /** @var \ECInternet\Sage300Account\Model\ResourceModel\Oeshdt\Collection $collection */
        $collection = $this->oeshdtCollectionFactory->create()
            ->addFieldToFilter(Oeshdt::COLUMN_CUSTOMER, $oeshdt->getCustomerNumber())
            ->addFieldToFilter(Oeshdt::COLUMN_ITEM, $oeshdt->getItemNumber());

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
        $this->logger->info('Model/OeshdtRepository - ' . $message, $extra);
    }
}
