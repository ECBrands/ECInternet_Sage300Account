<?php
/**
 * Copyright (C) EC Brands Corporation - All Rights Reserved
 * Contact Licensing@ECInternet.com for use guidelines
 */
declare(strict_types=1);

namespace ECInternet\Sage300Account\Model;

use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use ECInternet\Sage300Account\Api\OeppreRepositoryInterface;
use ECInternet\Sage300Account\Api\Data\OeppreInterface;
use ECInternet\Sage300Account\Api\Data\OeppreSearchResultsInterfaceFactory;
use ECInternet\Sage300Account\Logger\Logger;
use ECInternet\Sage300Account\Model\Data\Oeppre;
use ECInternet\Sage300Account\Model\ResourceModel\Oeppre as OeppreResource;
use ECInternet\Sage300Account\Model\ResourceModel\Oeppre\CollectionFactory as OeppreCollectionFactory;
use Exception;

/**
 * Oeppre model repository
 */
class OeppreRepository implements OeppreRepositoryInterface
{
    /**
     * @var \Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface
     */
    private $collectionProcessor;

    /**
     * @var \ECInternet\Sage300Account\Api\Data\OeppreSearchResultsInterfaceFactory
     */
    protected $searchResultsFactory;

    /**
     * @var \ECInternet\Sage300Account\Logger\Logger
     */
    private $logger;

    /**
     * @var \ECInternet\Sage300Account\Model\ResourceModel\Oeppre
     */
    private $resourceModel;

    /**
     * @var \ECInternet\Sage300Account\Model\ResourceModel\Oeppre\CollectionFactory
     */
    private $oeppreCollectionFactory;

    /**
     * OeppreRepository constructor.
     *
     * @param \Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface      $collectionProcessor
     * @param \ECInternet\Sage300Account\Api\Data\OeppreSearchResultsInterfaceFactory $oeppreSearchResultsFactory
     * @param \ECInternet\Sage300Account\Logger\Logger                                $logger
     * @param \ECInternet\Sage300Account\Model\ResourceModel\Oeppre                   $resourceModel
     * @param \ECInternet\Sage300Account\Model\ResourceModel\Oeppre\CollectionFactory $oeppreCollectionFactory
     */
    public function __construct(
        CollectionProcessorInterface $collectionProcessor,
        OeppreSearchResultsInterfaceFactory $oeppreSearchResultsFactory,
        Logger $logger,
        OeppreResource $resourceModel,
        OeppreCollectionFactory $oeppreCollectionFactory
    ) {
        $this->collectionProcessor     = $collectionProcessor;
        $this->searchResultsFactory    = $oeppreSearchResultsFactory;
        $this->logger                  = $logger;
        $this->resourceModel           = $resourceModel;
        $this->oeppreCollectionFactory = $oeppreCollectionFactory;
    }

    public function save(
        OeppreInterface $oeppre
    ) {
        $this->log('save()', ['artcp' => $oeppre->getData()]);

        $this->validate($oeppre);

        // If we find existing, grab the ID and set on incoming record
        if ($this->doesRecordExist($oeppre)) {
            $model = $this->get(
                $oeppre->getApplyTo(),
                $oeppre->getDocumentNumber(),
                $oeppre->getPrepaymentNumber()
            );
            $oeppre->setId($model->getId());
        }

        try {
            $this->resourceModel->save($oeppre);
        } catch (Exception $e) {
            $this->log('save()', [
                'class'     => get_class($e),
                'exception' => $e->getMessage(),
                'trace'     => $e->getTraceAsString()
            ]);

            throw $e;
        }

        return $oeppre;
    }

    public function bulkSave(
        array $oeppreArray
    ) {
        $this->log('bulkSave()');

        $results = [];

        foreach ($oeppreArray as $oeppre) {
            $this->log('bulkSave()', ['artcp' => $oeppre->getData()]);

            try {
                $this->save($oeppre);
                $results[] = true;
            } catch (Exception $e) {
                $this->log('bulkSave()', ['exception' => $e->getMessage()]);
                $results[] = false;
            }
        }

        return $results;
    }

    public function get(
        int $applyTo,
        string $documentNumber,
        int $prepaymentNumber
    ) {
        $this->log('get()', [
            'applyTo'          => $applyTo,
            'documentNumber'   => $documentNumber,
            'prepaymentNumber' => $prepaymentNumber
        ]);

        /** @var \ECInternet\Sage300Account\Model\ResourceModel\Oeppre\Collection $collectionCount */
        $collection = $this->oeppreCollectionFactory->create()
            ->addFieldToFilter(Oeppre::COLUMN_APPLYTO, $applyTo)
            ->addFieldToFilter(Oeppre::COLUMN_DOCNUMBER, $documentNumber)
            ->addFieldToFilter(Oeppre::COLUMN_PPNUMBER, $prepaymentNumber);

        $collectionCount = $collection->getSize();
        $this->log('get()', [
            'select'          => $collection->getSelect(),
            'collectionCount' => $collectionCount
        ]);

        // If we find no records, log it and return.
        if ($collectionCount === 0) {
            $this->log('get() - No OEPPRE records found.');

            return null;
        }

        // If we find multiple records, log and return.
        if ($collectionCount > 1) {
            $this->log('get() - More than one OEPPRE record found.');

            return null;
        }

        $oeppre = $collection->getFirstItem();
        if ($oeppre instanceof Oeppre) {
            return $oeppre;
        }

        return null;
    }

    public function getById(
        int $oeppreId
    ) {
        $this->log('getById()', ['oeppreId' => $oeppreId]);

        /** @var \ECInternet\Sage300Account\Model\ResourceModel\Poporl\Collection $collection */
        $collection = $this->oeppreCollectionFactory->create()
            ->addFieldToFilter(Oeppre::COLUMN_ID, $oeppreId);

        $collectionCount = $collection->getSize();
        $this->log('getById()', [
            'select'          => $collection->getSelect(),
            'collectionCount' => $collectionCount
        ]);

        if ($collectionCount === 1) {
            $oeppre = $collection->getFirstItem();
            if ($oeppre instanceof Oeppre) {
                return $oeppre;
            }
        }

        return null;
    }

    public function getList(
        SearchCriteriaInterface $searchCriteria
    ) {
        $this->log('getList()');

        /** @var \ECInternet\Sage300Account\Api\Data\OeppreSearchResultsInterface $searchResults */
        $searchResults = $this->searchResultsFactory->create();

        $searchResults->setSearchCriteria($searchCriteria);

        /** @var \ECInternet\Sage300Account\Model\ResourceModel\Oeppre\Collection $collection */
        $collection = $this->oeppreCollectionFactory->create();
        $this->collectionProcessor->process($searchCriteria, $collection);

        $searchResults->setTotalCount($collection->getSize());

        $collection->setCurPage($searchCriteria->getCurrentPage());
        $collection->setPageSize($searchCriteria->getPageSize());
        $searchResults->setItems($collection->getData());

        return $searchResults;
    }

    public function deleteById(int $oeppreId)
    {
        if ($oeppre = $this->getById($oeppreId)) {
            try {
                $this->resourceModel->delete($oeppre);

                return true;
            } catch (Exception $e) {
                $this->log('deleteById()', ['oeppreId' => $oeppreId, 'error' => $e->getMessage()]);
            }
        }

        return false;
    }

    /**
     * Validate OEPPRE record
     *
     * @param \ECInternet\Sage300Account\Api\Data\OeppreInterface $oeppre
     *
     * @return void
     */
    protected function validate(
        OeppreInterface $oeppre
    ) {
    }

    /**
     * Does OEPPRE record exist?
     *
     * @param \ECInternet\Sage300Account\Api\Data\OeppreInterface $oeppre
     *
     * @return bool
     */
    private function doesRecordExist(
        OeppreInterface $oeppre
    ) {
        $this->log('doesRecordExist()', [
            Oeppre::COLUMN_APPLYTO   => $oeppre->getApplyTo(),
            Oeppre::COLUMN_DOCNUMBER => $oeppre->getDocumentNumber(),
            Oeppre::COLUMN_PPNUMBER  => $oeppre->getPrepaymentNumber()
        ]);

        /** @var \ECInternet\Sage300Account\Model\ResourceModel\Oeppre\Collection $collectionCount */
        $collection = $this->oeppreCollectionFactory->create()
            ->addFieldToFilter(Oeppre::COLUMN_APPLYTO, $oeppre->getApplyTo())
            ->addFieldToFilter(Oeppre::COLUMN_DOCNUMBER, $oeppre->getDocumentNumber())
            ->addFieldToFilter(Oeppre::COLUMN_PPNUMBER, $oeppre->getPrepaymentNumber());

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
        $this->logger->info('Model/OeppreRepository - ' . $message, $extra);
    }
}
