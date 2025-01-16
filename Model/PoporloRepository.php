<?php
/**
 * Copyright (C) EC Brands Corporation - All Rights Reserved
 * Contact Licensing@ECInternet.com for use guidelines
 */
declare(strict_types=1);

namespace ECInternet\Sage300Account\Model;

use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Exception\AlreadyExistsException;
use ECInternet\Sage300Account\Api\Data\PoporloInterface;
use ECInternet\Sage300Account\Api\Data\PoporloSearchResultsInterfaceFactory;
use ECInternet\Sage300Account\Api\PoporloRepositoryInterface;
use ECInternet\Sage300Account\Logger\Logger;
use ECInternet\Sage300Account\Model\Data\Poporlo;
use ECInternet\Sage300Account\Model\ResourceModel\Poporlo as PoporloResource;
use ECInternet\Sage300Account\Model\ResourceModel\Poporlo\CollectionFactory as PoporloCollectionFactory;
use Exception;

/**
 * Poporlo Model Repository
 *
 * @SuppressWarnings(PHPMD.ShortVariable)
 */
class PoporloRepository implements PoporloRepositoryInterface
{
    /**
     * @var \Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface
     */
    private $collectionProcessor;

    /**
     * @var \ECInternet\Sage300Account\Api\Data\PoporloSearchResultsInterfaceFactory
     */
    protected $searchResultsFactory;

    /**
     * @var \ECInternet\Sage300Account\Logger\Logger
     */
    private $logger;

    /**
     * @var \ECInternet\Sage300Account\Model\ResourceModel\Poporlo
     */
    private $resourceModel;

    /**
     * @var \ECInternet\Sage300Account\Model\ResourceModel\Poporlo\CollectionFactory
     */
    private $poporloCollectionFactory;

    /**
     * PoporloRepository constructor.
     *
     * @param \Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface       $collectionProcessor
     * @param \ECInternet\Sage300Account\Api\Data\PoporloSearchResultsInterfaceFactory $poporloSearchResultsFactory
     * @param \ECInternet\Sage300Account\Logger\Logger                                 $logger
     * @param \ECInternet\Sage300Account\Model\ResourceModel\Poporlo                   $resourceModel
     * @param \ECInternet\Sage300Account\Model\ResourceModel\Poporlo\CollectionFactory $poporloCollectionFactory
     */
    public function __construct(
        CollectionProcessorInterface $collectionProcessor,
        PoporloSearchResultsInterfaceFactory $poporloSearchResultsFactory,
        Logger $logger,
        PoporloResource $resourceModel,
        PoporloCollectionFactory $poporloCollectionFactory
    ) {
        $this->collectionProcessor      = $collectionProcessor;
        $this->searchResultsFactory     = $poporloSearchResultsFactory;
        $this->logger                   = $logger;
        $this->resourceModel            = $resourceModel;
        $this->poporloCollectionFactory = $poporloCollectionFactory;
    }

    public function save(
        PoporloInterface $poporlo
    ) {
        $this->log('save()', ['poporlo' => $poporlo->getData()]);

        $this->validate($poporlo);

        if ($this->doesRecordExist($poporlo)) {
            $model = $this->get(
                $poporlo->getPurchaseOrderSequenceKey(),
                $poporlo->getLineNumber(),
                $poporlo->getOptionalField()
            );
            $poporlo->setId($model->getId());
        }

        try {
            $this->resourceModel->save($poporlo);
        } catch (AlreadyExistsException $e) {
            $this->log('save()', ['alreadyExistsException' => $e->getMessage()]);
        } catch (Exception $e) {
            $this->log('save()', [
                'class'     => get_class($e),
                'exception' => $e->getMessage(),
                'trace'     => $e->getTraceAsString()
            ]);
        }

        return $poporlo;
    }

    public function bulkSave(
        array $poporloArray
    ) {
        $this->log('bulkSave()', ['count' => count($poporloArray)]);

        $results = [];

        foreach ($poporloArray as $poporlo) {
            //$this->log('bulkSave()', ['poporlo' => $poporlo->getData()]);

            try {
                $this->save($poporlo);
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
        $lineNumber,
        $optionalField
    ) {
        $this->log('get()', [
            'sequenceKey'   => $sequenceKey,
            'lineNumber'    => $lineNumber,
            'optionalField' => $optionalField
        ]);

        /** @var \ECInternet\Sage300Account\Model\ResourceModel\Poporlo\Collection $collection */
        $collection = $this->poporloCollectionFactory->create()
            ->addFieldToFilter(Poporlo::COLUMN_PORHSEQ, $sequenceKey)
            ->addFieldToFilter(Poporlo::COLUMN_PORLREV, $lineNumber)
            ->addFieldToFilter(Poporlo::COLUMN_OPTFIELD, $optionalField);

        $collectionCount = $collection->getSize();
        //$this->log('get()', ['collectionCount' => $collectionCount]);

        if ($collectionCount === 1) {
            $poporlo = $collection->getFirstItem();
            if ($poporlo instanceof Poporlo) {
                return $poporlo;
            }
        }

        return null;
    }

    public function getById(
        int $poporloId
    ) {
        $this->log('getById()', ['poporloId' => $poporloId]);

        /** @var \ECInternet\Sage300Account\Model\ResourceModel\Poporl\Collection $collection */
        $collection = $this->poporloCollectionFactory->create()
            ->addFieldToFilter(Poporlo::COLUMN_ID, $poporloId);

        $collectionCount = $collection->getSize();
        $this->log('getById()', [
            'select'          => $collection->getSelect(),
            'collectionCount' => $collectionCount
        ]);

        if ($collectionCount === 1) {
            $poporlo = $collection->getFirstItem();
            if ($poporlo instanceof Poporlo) {
                return $poporlo;
            }
        }

        return null;
    }

    public function getList(
        SearchCriteriaInterface $searchCriteria
    ) {
        $this->log('getList()');

        /** @var \ECInternet\Sage300Account\Api\Data\PoporloSearchResultsInterface $searchResults */
        $searchResults = $this->searchResultsFactory->create();

        $searchResults->setSearchCriteria($searchCriteria);

        /** @var \ECInternet\Sage300Account\Model\ResourceModel\Poporlo\Collection $collection */
        $collection = $this->poporloCollectionFactory->create();
        $this->collectionProcessor->process($searchCriteria, $collection);

        $searchResults->setTotalCount($collection->getSize());

        $collection->setCurPage($searchCriteria->getCurrentPage());
        $collection->setPageSize($searchCriteria->getPageSize());
        $searchResults->setItems($collection->getData());

        return $searchResults;
    }

    public function deleteById(int $poporloId)
    {
        if ($poporlo = $this->getById($poporloId)) {
            try {
                $this->resourceModel->delete($poporlo);

                return true;
            } catch (Exception $e) {
                $this->log('deleteById()', ['poporloId' => $poporloId, 'error' => $e->getMessage()]);
            }
        }

        return false;
    }

    /**
     * Validate POPORLO record
     *
     * @param \ECInternet\Sage300Account\Api\Data\PoporloInterface $poporlo
     *
     * @return void
     */
    protected function validate(
        PoporloInterface $poporlo
    ) {
    }

    /**
     * Does POPORLO record exist?
     *
     * @param \ECInternet\Sage300Account\Api\Data\PoporloInterface $poporlo
     *
     * @return bool
     */
    private function doesRecordExist(
        PoporloInterface $poporlo
    ) {
        $this->log('doesRecordExist()', [
            Poporlo::COLUMN_PORHSEQ  => $poporlo->getPurchaseOrderSequenceKey(),
            Poporlo::COLUMN_PORLREV  => $poporlo->getLineNumber(),
            Poporlo::COLUMN_OPTFIELD => $poporlo->getOptionalField()
        ]);

        /** @var \ECInternet\Sage300Account\Model\ResourceModel\Poporh\Collection $collection */
        $collection = $this->poporloCollectionFactory->create()
            ->addFieldToFilter(Poporlo::COLUMN_PORHSEQ, $poporlo->getPurchaseOrderSequenceKey())
            ->addFieldToFilter(Poporlo::COLUMN_PORLREV, $poporlo->getLineNumber())
            ->addFieldToFilter(Poporlo::COLUMN_OPTFIELD, $poporlo->getOptionalField());

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
        $this->logger->info('Model/PoporloRepository - ' . $message, $extra);
    }
}
