<?php
/**
 * Copyright (C) EC Brands Corporation - All Rights Reserved
 * Contact Licensing@ECInternet.com for use guidelines
 */
declare(strict_types=1);

namespace ECInternet\Sage300Account\Model;

use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use ECInternet\Sage300Account\Api\Data\OeorddInterface;
use ECInternet\Sage300Account\Api\Data\OeorddSearchResultsInterfaceFactory;
use ECInternet\Sage300Account\Api\OeorddRepositoryInterface;
use ECInternet\Sage300Account\Logger\Logger;
use ECInternet\Sage300Account\Model\Data\Oeordd;
use ECInternet\Sage300Account\Model\ResourceModel\Oeordd as OeorddResource;
use ECInternet\Sage300Account\Model\ResourceModel\Oeordd\CollectionFactory as OeorddCollectionFactory;
use Exception;

/**
 * Oeordd model repository
 *
 * @SuppressWarnings(PHPMD.LongVariable)
 * @SuppressWarnings(PHPMD.ShortVariable)
 */
class OeorddRepository implements OeorddRepositoryInterface
{
    /**
     * @var \Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface
     */
    private $collectionProcessor;

    /**
     * @var \ECInternet\Sage300Account\Api\Data\OeorddSearchResultsInterfaceFactory
     */
    protected $searchResultsFactory;

    /**
     * @var \ECInternet\Sage300Account\Logger\Logger
     */
    private $logger;

    /**
     * @var \ECInternet\Sage300Account\Model\ResourceModel\Oeordd
     */
    private $resourceModel;

    /**
     * @var \ECInternet\Sage300Account\Model\ResourceModel\Oeordd\CollectionFactory
     */
    private $oeorddCollectionFactory;

    /**
     * OeorddRepository constructor.
     *
     * @param \Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface      $collectionProcessor
     * @param \ECInternet\Sage300Account\Api\Data\OeorddSearchResultsInterfaceFactory $oeorddSearchResultsFactory
     * @param \ECInternet\Sage300Account\Logger\Logger                                $logger
     * @param \ECInternet\Sage300Account\Model\ResourceModel\Oeordd                   $resourceModel
     * @param \ECInternet\Sage300Account\Model\ResourceModel\Oeordd\CollectionFactory $oeorddCollectionFactory
     */
    public function __construct(
        CollectionProcessorInterface $collectionProcessor,
        OeorddSearchResultsInterfaceFactory $oeorddSearchResultsFactory,
        Logger $logger,
        OeorddResource $resourceModel,
        OeorddCollectionFactory $oeorddCollectionFactory
    ) {
        $this->collectionProcessor     = $collectionProcessor;
        $this->searchResultsFactory    = $oeorddSearchResultsFactory;
        $this->logger                  = $logger;
        $this->resourceModel           = $resourceModel;
        $this->oeorddCollectionFactory = $oeorddCollectionFactory;
    }

    public function save(
        OeorddInterface $oeordd
    ) {
        $this->log('save()', ['oeordd' => $oeordd->getData()]);

        $this->validate($oeordd);

        if ($existing = $this->get($oeordd->getOrderUniquifier(), $oeordd->getLineNumber())) {
            $oeordd->setId($existing->getId());
        }

        try {
            $this->resourceModel->save($oeordd);
        } catch (Exception $e) {
            $this->log('save()', [
                'class'     => get_class($e),
                'exception' => $e->getMessage(),
                'trace'     => $e->getTraceAsString()
            ]);

            throw $e;
        }

        return $oeordd;
    }

    public function bulkSave(
        array $oeorddArray
    ) {
        $this->log('bulkSave()');

        $results = [];

        foreach ($oeorddArray as $oeordd) {
            $this->log('bulkSave()', ['oeordd' => $oeordd->getData()]);

            try {
                $this->save($oeordd);
                $results[] = true;
            } catch (Exception $e) {
                $this->log('bulkSave()', ['exception' => $e->getMessage()]);
                $results[] = false;
            }
        }

        return $results;
    }

    public function get(
        int $orderUniquifier,
        int $lineNumber
    ) {
        $this->log('get()', [
            'orderUniquifier' => $orderUniquifier,
            'lineNumber'      => $lineNumber
        ]);

        $collection = $this->oeorddCollectionFactory->create()
            ->addFieldToSelect('*')
            ->addFieldToFilter(Oeordd::COLUMN_ORDUNIQ, $orderUniquifier);

        $collectionCount = $collection->getSize();
        $this->log('get()', [
            'select'          => $collection->getSelect(),
            'collectionCount' => $collectionCount
        ]);

        if ($collectionCount === 1) {
            $oeordd = $collection->getFirstItem();
            if ($oeordd instanceof OeorddInterface) {
                return $oeordd;
            }
        }

        return null;
    }

    public function getById(
        int $oeorddId
    ) {
        $this->log('getById()', ['oeorddId' => $oeorddId]);

        /** @var \ECInternet\Sage300Account\Model\ResourceModel\Poporl\Collection $collection */
        $collection = $this->oeorddCollectionFactory->create()
            ->addFieldToFilter(Oeordd::COLUMN_ID, $oeorddId);

        $collectionCount = $collection->getSize();
        $this->log('getById()', [
            'select'          => $collection->getSelect(),
            'collectionCount' => $collectionCount
        ]);

        if ($collectionCount === 1) {
            $oeordd = $collection->getFirstItem();
            if ($oeordd instanceof Oeordd) {
                return $oeordd;
            }
        }

        return null;
    }

    public function getList(
        SearchCriteriaInterface $searchCriteria
    ) {
        $this->log('getList()');

        /** @var \ECInternet\Sage300Account\Api\Data\OeorddSearchResultsInterface $searchResults */
        $searchResults = $this->searchResultsFactory->create();

        $searchResults->setSearchCriteria($searchCriteria);

        /** @var \ECInternet\Sage300Account\Model\ResourceModel\Oeordd\Collection $collection */
        $collection = $this->oeorddCollectionFactory->create();
        $this->collectionProcessor->process($searchCriteria, $collection);

        $searchResults->setTotalCount($collection->getSize());

        $collection->setCurPage($searchCriteria->getCurrentPage());
        $collection->setPageSize($searchCriteria->getPageSize());
        $searchResults->setItems($collection->getData());

        return $searchResults;
    }

    public function deleteById(int $oeorddId)
    {
        if ($oeordd = $this->getById($oeorddId)) {
            try {
                $this->resourceModel->delete($oeordd);

                return true;
            } catch (Exception $e) {
                $this->log('deleteById()', ['oeorddId' => $oeorddId, 'error' => $e->getMessage()]);
            }
        }

        return false;
    }

    /**
     * Validate OEORDD record
     *
     * @param \ECInternet\Sage300Account\Api\Data\OeorddInterface $oeordd
     *
     * @return void
     */
    protected function validate(
        OeorddInterface $oeordd
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
        $this->logger->info('Model/OeorddRepository - ' . $message, $extra);
    }
}
