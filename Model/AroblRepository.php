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
use ECInternet\Sage300Account\Api\Data\AroblInterface;
use ECInternet\Sage300Account\Api\Data\AroblSearchResultsInterfaceFactory;
use ECInternet\Sage300Account\Api\AroblRepositoryInterface;
use ECInternet\Sage300Account\Logger\Logger;
use ECInternet\Sage300Account\Model\Data\Arobl;
use ECInternet\Sage300Account\Model\ResourceModel\Arobl as AroblResource;
use ECInternet\Sage300Account\Model\ResourceModel\Arobl\CollectionFactory as AroblCollectionFactory;
use Exception;

/**
 * Arobl model repository
 *
 * @SuppressWarnings(PHPMD.LongVariable)
 */
class AroblRepository implements AroblRepositoryInterface
{
    /**
     * @var \Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface
     */
    private $collectionProcessor;

    /**
     * @var \ECInternet\Sage300Account\Api\Data\AroblSearchResultsInterfaceFactory
     */
    protected $searchResultsFactory;

    /**
     * @var \ECInternet\Sage300Account\Logger\Logger
     */
    private $logger;

    /**
     * @var \ECInternet\Sage300Account\Model\ResourceModel\Arobl
     */
    private $resourceModel;

    /**
     * @var \ECInternet\Sage300Account\Model\ResourceModel\Arobl\CollectionFactory
     */
    private $aroblCollectionFactory;

    /**
     * AroblRepository constructor.
     *
     * @param \Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface     $collectionProcessor
     * @param \ECInternet\Sage300Account\Api\Data\AroblSearchResultsInterfaceFactory $aroblSearchResultsFactory
     * @param \ECInternet\Sage300Account\Logger\Logger                               $logger
     * @param \ECInternet\Sage300Account\Model\ResourceModel\Arobl                   $resourceModel
     * @param \ECInternet\Sage300Account\Model\ResourceModel\Arobl\CollectionFactory $aroblCollectionFactory
     */
    public function __construct(
        CollectionProcessorInterface $collectionProcessor,
        AroblSearchResultsInterfaceFactory $aroblSearchResultsFactory,
        Logger $logger,
        AroblResource $resourceModel,
        AroblCollectionFactory $aroblCollectionFactory
    ) {
        $this->collectionProcessor    = $collectionProcessor;
        $this->searchResultsFactory   = $aroblSearchResultsFactory;
        $this->logger                 = $logger;
        $this->resourceModel          = $resourceModel;
        $this->aroblCollectionFactory = $aroblCollectionFactory;
    }

    public function save(
        AroblInterface $arobl
    ) {
        $this->log('save()', ['arobl' => $arobl->getData()]);

        $this->validate($arobl);

        // If we find existing, grab the ID and set on incoming record
        if ($this->doesRecordExist($arobl)) {
            $model = $this->get($arobl->getCustomerNumber(), $arobl->getDocumentNumber());
            $arobl->setId($model->getId());
        }

        try {
            $this->resourceModel->save($arobl);
        } catch (Exception $e) {
            $this->log('save()', [
                'class'     => get_class($e),
                'exception' => $e->getMessage(),
                'trace'     => $e->getTraceAsString()
            ]);

            throw $e;
        }

        return $arobl;
    }

    public function bulkSave(
        array $aroblArray
    ) {
        $this->log('bulkSave()');

        $results = [];

        foreach ($aroblArray as $arobl) {
            $this->log('bulkSave()', ['arobl' => $arobl->getData()]);

            try {
                $this->save($arobl);
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
        string $documentNumber
    ) {
        $this->log('get()', [
            'customerNumber' => $customerNumber,
            'documentNumber' => $documentNumber
        ]);

        /** @var \ECInternet\Sage300Account\Model\ResourceModel\Arobl\Collection $collection */
        $collection = $this->aroblCollectionFactory->create()
            ->addFieldToFilter(Arobl::COLUMN_IDCUST, $customerNumber)
            ->addFieldToFilter(Arobl::COLUMN_IDINVC, $documentNumber);

        $collectionCount = $collection->getSize();
        $this->log('get()', [
            'select'          => $collection->getSelect(),
            'collectionCount' => $collectionCount
        ]);

        // If we find no records, log it and return.
        if ($collectionCount === 0) {
            $this->log('get() - No AROBL records found.');

            return null;
        }

        // If we find multiple records, log and return.
        if ($collectionCount > 1) {
            $this->log('get() - More than one AROBL record found.');

            return null;
        }

        $arobl = $collection->getFirstItem();
        if ($arobl instanceof Arobl) {
            return $arobl;
        }

        return null;
    }

    public function getById(
        int $aroblId
    ) {
        $this->log('getById()', ['oeorddId' => $aroblId]);

        /** @var \ECInternet\Sage300Account\Model\ResourceModel\Poporl\Collection $collection */
        $collection = $this->aroblCollectionFactory->create()
            ->addFieldToFilter(Arobl::COLUMN_ID, $aroblId);

        $collectionCount = $collection->getSize();
        $this->log('getById()', [
            'select'          => $collection->getSelect(),
            'collectionCount' => $collectionCount
        ]);

        if ($collectionCount === 1) {
            $arobl = $collection->getFirstItem();
            if ($arobl instanceof Arobl) {
                return $arobl;
            }
        }

        return null;
    }

    public function getList(
        SearchCriteriaInterface $searchCriteria
    ) {
        $this->log('getList()');

        /** @var \ECInternet\Sage300Account\Api\Data\AroblSearchResultsInterface $searchResults */
        $searchResults = $this->searchResultsFactory->create();

        /** @noinspection PhpExpressionResultUnusedInspection */
        $searchResults->setSearchCriteria($searchCriteria);

        /** @var \ECInternet\Sage300Account\Model\ResourceModel\Arobl\Collection $collection */
        $collection = $this->aroblCollectionFactory->create();
        $this->collectionProcessor->process($searchCriteria, $collection);

        $searchResults->setTotalCount($collection->getSize());

        $collection->setCurPage($searchCriteria->getCurrentPage());
        $collection->setPageSize($searchCriteria->getPageSize());
        $searchResults->setItems($collection->getData());

        return $searchResults;
    }

    public function deleteById($aroblId)
    {
        if ($arobl = $this->getById($aroblId)) {
            try {
                $this->resourceModel->delete($arobl);

                return true;
            } catch (Exception $e) {
                $this->log('deleteById()', ['error' => $e->getMessage()]);
            }
        }

        return false;
    }

    /**
     * Validate AROBL record
     *
     * @param \ECInternet\Sage300Account\Api\Data\AroblInterface $arobl
     *
     * @return void
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function validate(
        AroblInterface $arobl
    ) {
        if (empty($arobl->getCustomerNumber())) {
            throw new LocalizedException(__('Customer number is required'));
        }

        if (empty($arobl->getDocumentNumber())) {
            throw new LocalizedException(__('Document number is required'));
        }
    }

    /**
     * Does AROBL record exist?
     *
     * @param \ECInternet\Sage300Account\Api\Data\AroblInterface $arobl
     *
     * @return bool
     */
    private function doesRecordExist(
        AroblInterface $arobl
    ) {
        $this->log('doesRecordExist()', [
            Arobl::COLUMN_IDCUST => $arobl->getCustomerNumber(),
            Arobl::COLUMN_IDINVC => $arobl->getDocumentNumber()
        ]);

        /** @var \ECInternet\Sage300Account\Model\ResourceModel\Arobl\Collection $collection */
        $collection = $this->aroblCollectionFactory->create()
            ->addFieldToFilter(Arobl::COLUMN_IDCUST, $arobl->getCustomerNumber())
            ->addFieldToFilter(Arobl::COLUMN_IDINVC, $arobl->getDocumentNumber());

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
        $this->logger->info('Model/AroblRepository - ' . $message, $extra);
    }
}
