<?php
/**
 * Copyright (C) EC Brands Corporation - All Rights Reserved
 * Contact Licensing@ECInternet.com for use guidelines
 */
declare(strict_types=1);

namespace ECInternet\Sage300Account\Model;

use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use ECInternet\Sage300Account\Api\Data\OeinvhInterface;
use ECInternet\Sage300Account\Api\Data\OeinvhSearchResultsInterfaceFactory;
use ECInternet\Sage300Account\Api\OeinvhRepositoryInterface;
use ECInternet\Sage300Account\Logger\Logger;
use ECInternet\Sage300Account\Model\Data\Oeinvh;
use ECInternet\Sage300Account\Model\ResourceModel\Oeinvh as OeinvhResource;
use ECInternet\Sage300Account\Model\ResourceModel\Oeinvh\CollectionFactory as OeinvhCollectionFactory;
use Exception;

/**
 * Oeinvh model repository
 */
class OeinvhRepository implements OeinvhRepositoryInterface
{
    /**
     * @var \Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface
     */
    private $collectionProcessor;

    /**
     * @var \ECInternet\Sage300Account\Api\Data\OeinvhSearchResultsInterfaceFactory
     */
    protected $searchResultsFactory;

    /**
     * @var \ECInternet\Sage300Account\Logger\Logger
     */
    private $logger;

    /**
     * @var \ECInternet\Sage300Account\Model\ResourceModel\Oeinvh
     */
    private $resourceModel;

    /**
     * @var \ECInternet\Sage300Account\Model\ResourceModel\Oeinvh\CollectionFactory
     */
    private $oeinvhCollectionFactory;

    /**
     * OeinvhRepository constructor.
     *
     * @param \Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface      $collectionProcessor
     * @param \ECInternet\Sage300Account\Api\Data\OeinvhSearchResultsInterfaceFactory $oeinvhSearchResultsFactory
     * @param \ECInternet\Sage300Account\Logger\Logger                                $logger
     * @param \ECInternet\Sage300Account\Model\ResourceModel\Oeinvh                   $resourceModel
     * @param \ECInternet\Sage300Account\Model\ResourceModel\Oeinvh\CollectionFactory $oeinvhCollectionFactory
     */
    public function __construct(
        CollectionProcessorInterface $collectionProcessor,
        OeinvhSearchResultsInterfaceFactory $oeinvhSearchResultsFactory,
        Logger $logger,
        OeinvhResource $resourceModel,
        OeinvhCollectionFactory $oeinvhCollectionFactory
    ) {
        $this->collectionProcessor     = $collectionProcessor;
        $this->searchResultsFactory    = $oeinvhSearchResultsFactory;
        $this->logger                  = $logger;
        $this->resourceModel           = $resourceModel;
        $this->oeinvhCollectionFactory = $oeinvhCollectionFactory;
    }

    public function save(
        OeinvhInterface $oeinvh
    ) {
        $this->log('save()', ['oeinvh' => $oeinvh->getData()]);

        $this->validate($oeinvh);

        // If we find existing, grab the ID and set on incoming record
        if ($this->doesRecordExist($oeinvh)) {
            $this->log('save() - Existing record found --> UPDATE');
            $model = $this->getByInvoiceUniquifier($oeinvh->getInvoiceUniquifier());
            $oeinvh->setId($model->getId());
        } else {
            $this->log('save() - Existing record not found --> INSERT');
        }

        try {
            $this->resourceModel->save($oeinvh);
        } catch (Exception $e) {
            $this->log('save()', [
                'class'     => get_class($e),
                'exception' => $e->getMessage(),
                'trace'     => $e->getTraceAsString()
            ]);

            throw $e;
        }

        return $oeinvh;
    }

    public function bulkSave(
        array $oeinvhArray
    ) {
        $this->log('bulkSave()');

        $results = [];

        foreach ($oeinvhArray as $oeinvh) {
            //$this->log('bulkSave()', ['oeinvh' => $oeinvh->getData()]);

            try {
                $this->save($oeinvh);
                $results[] = true;
            } catch (Exception $e) {
                $this->log('bulkSave()', ['exception' => $e->getMessage()]);
                $results[] = false;
            }
        }

        return $results;
    }

    public function getById(
        int $oeinvhId
    ) {
        $this->log('getById()', ['oeinvhId' => $oeinvhId]);

        /** @var \ECInternet\Sage300Account\Model\ResourceModel\Oeinvh\Collection $collection */
        $collection = $this->oeinvhCollectionFactory->create()
            ->addFieldToFilter(Oeinvh::COLUMN_ID, $oeinvhId);

        $collectionCount = $collection->getSize();
        $this->log('getById()', [
            'select'          => $collection->getSelect(),
            'collectionCount' => $collectionCount
        ]);

        // If we find no records, log it and return.
        if ($collectionCount === 0) {
            $this->log('getById() - No OEINVH records found.');

            return null;
        }

        // If we find multiple records, log and return.
        if ($collectionCount > 1) {
            $this->log('getById() - More than one OEINVH record found.');

            return null;
        }

        $oeinvh = $collection->getFirstItem();
        if ($oeinvh instanceof OeinvhInterface) {
            return $oeinvh;
        }

        return null;
    }

    public function getByInvoiceUniquifier(
        int $invoiceUniquifier
    ) {
        $this->log('getByInvoiceUniquifier()', ['invoiceUniquifier' => $invoiceUniquifier]);

        $collection = $this->oeinvhCollectionFactory->create()
            ->addFieldToFilter(Oeinvh::COLUMN_INVUNIQ, $invoiceUniquifier);

        $collectionCount = $collection->getSize();
        $this->log('getByInvoiceUniquifier()', ['collectionCount' => $collectionCount]);

        if ($collectionCount === 1) {
            $oeinvh = $collection->getFirstItem();
            if ($oeinvh instanceof Oeinvh) {
                return $oeinvh;
            }
        }

        return null;
    }

    public function getList(
        SearchCriteriaInterface $searchCriteria
    ) {
        $this->log('getList()');

        /** @var \ECInternet\Sage300Account\Api\Data\OeinvhSearchResultsInterface $searchResults */
        $searchResults = $this->searchResultsFactory->create();

        /** @noinspection PhpExpressionResultUnusedInspection */
        $searchResults->setSearchCriteria($searchCriteria);

        /** @var \ECInternet\Sage300Account\Model\ResourceModel\Poporl\Collection $collection */
        $collection = $this->oeinvhCollectionFactory->create();
        $this->collectionProcessor->process($searchCriteria, $collection);

        $searchResults->setTotalCount($collection->getSize());

        $collection->setCurPage($searchCriteria->getCurrentPage());
        $collection->setPageSize($searchCriteria->getPageSize());
        $searchResults->setItems($collection->getData());

        return $searchResults;
    }

    public function deleteById(int $oeinvhId)
    {
        if ($oeinvh = $this->getById($oeinvhId)) {
            try {
                $this->resourceModel->delete($oeinvh);

                return true;
            } catch (Exception $e) {
                $this->log('deleteById()', ['oeinvhId' => $oeinvhId, 'error' => $e->getMessage()]);
            }
        }

        return false;
    }
    
    /**
     * Validate OEINVH record
     *
     * @param \ECInternet\Sage300Account\Api\Data\OeinvhInterface $oeinvh
     *
     * @return void
     */
    protected function validate(
        OeinvhInterface $oeinvh
    ) {
    }

    /**
     * Does OEINVH record exist?
     *
     * @param \ECInternet\Sage300Account\Api\Data\OeinvhInterface $oeinvh
     *
     * @return bool
     */
    private function doesRecordExist(
        OeinvhInterface $oeinvh
    ) {
        $this->log('doesRecordExist()', [
            Oeinvh::COLUMN_INVUNIQ => $oeinvh->getInvoiceUniquifier()
        ]);

        $collection = $this->oeinvhCollectionFactory->create()
            ->addFieldToFilter(Oeinvh::COLUMN_INVUNIQ, $oeinvh->getInvoiceUniquifier());

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
        $this->logger->info('Model/OeinvhRepository - ' . $message, $extra);
    }
}
