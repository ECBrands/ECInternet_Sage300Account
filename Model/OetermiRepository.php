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
use ECInternet\Sage300Account\Api\Data\OetermiInterface;
use ECInternet\Sage300Account\Api\Data\OetermiSearchResultsInterfaceFactory;
use ECInternet\Sage300Account\Api\OetermiRepositoryInterface;
use ECInternet\Sage300Account\Logger\Logger;
use ECInternet\Sage300Account\Model\Data\Oetermi;
use ECInternet\Sage300Account\Model\ResourceModel\Oetermi as OetermiResource;
use ECInternet\Sage300Account\Model\ResourceModel\Oetermi\CollectionFactory as OetermiCollectionFactory;
use Exception;

/**
 * Oetermi Model Repository
 *
 * @SuppressWarnings(PHPMD.ShortVariable)
 */
class OetermiRepository implements OetermiRepositoryInterface
{
    /**
     * @var \Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface
     */
    private $collectionProcessor;

    /**
     * @var \ECInternet\Sage300Account\Api\Data\OetermiSearchResultsInterfaceFactory
     */
    protected $searchResultsFactory;

    /**
     * @var \ECInternet\Sage300Account\Logger\Logger
     */
    private $logger;

    /**
     * @var \ECInternet\Sage300Account\Model\ResourceModel\Oetermi
     */
    private $resourceModel;

    /**
     * @var \ECInternet\Sage300Account\Model\ResourceModel\Oetermi\CollectionFactory
     */
    private $oetermiCollectionFactory;

    /**
     * OetermiRepository constructor.
     *
     * @param \Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface       $collectionProcessor
     * @param \ECInternet\Sage300Account\Api\Data\OetermiSearchResultsInterfaceFactory $oetermiSearchResultsFactory
     * @param \ECInternet\Sage300Account\Logger\Logger                                 $logger
     * @param \ECInternet\Sage300Account\Model\ResourceModel\Oetermi                   $resourceModel
     * @param \ECInternet\Sage300Account\Model\ResourceModel\Oetermi\CollectionFactory $oetermiCollectionFactory
     */
    public function __construct(
        CollectionProcessorInterface $collectionProcessor,
        OetermiSearchResultsInterfaceFactory $oetermiSearchResultsFactory,
        Logger $logger,
        OetermiResource $resourceModel,
        OetermiCollectionFactory $oetermiCollectionFactory
    ) {
        $this->collectionProcessor      = $collectionProcessor;
        $this->searchResultsFactory     = $oetermiSearchResultsFactory;
        $this->logger                   = $logger;
        $this->resourceModel            = $resourceModel;
        $this->oetermiCollectionFactory = $oetermiCollectionFactory;
    }

    public function save(
        OetermiInterface $oetermi
    ) {
        $this->log('save()', ['oetermi' => $oetermi->getData()]);

        $this->validate($oetermi);

        // If we find existing, grab the ID and set on incoming record
        if ($this->doesRecordExist($oetermi)) {
            /** @var \ECInternet\Sage300Account\Api\Data\OetermiInterface $model */
            $model = $this->get($oetermi->getInvoiceUniquifier(), $oetermi->getPaymentNumber());
            $oetermi->setId($model->getId());
        }

        try {
            $this->resourceModel->save($oetermi);
        } catch (Exception $e) {
            $this->log('save()', [
                'class'     => get_class($e),
                'exception' => $e->getMessage(),
                'trace'     => $e->getTraceAsString()
            ]);

            throw $e;
        }

        return $oetermi;
    }

    public function bulkSave(
        array $oetermiArray
    ) {
        $this->log('bulkSave()');

        $results = [];

        foreach ($oetermiArray as $oetermi) {
            $this->log('bulkSave()', ['oetermi' => $oetermi->getData()]);

            try {
                $this->save($oetermi);
                $results[] = true;
            } catch (Exception $e) {
                $this->log('bulkSave()', ['exception' => $e->getMessage()]);
                $results[] = false;
            }
        }

        return $results;
    }

    public function get(
        int $invoiceUniquifier,
        int $paymentNumber
    ) {
        //$this->log('get()', ['invoiceUniquifier' => $invoiceUniquifier, 'paymentNumber' => $paymentNumber]);

        /** @var \ECInternet\Sage300Account\Model\ResourceModel\Oetermi\Collection $collection */
        $collection = $this->oetermiCollectionFactory->create()
            ->addFieldToSelect('*')
            ->addFieldToFilter(Oetermi::COLUMN_INVUNIQ, $invoiceUniquifier)
            ->addFieldToFilter(Oetermi::COLUMN_PAYMENT, $paymentNumber);

        $collectionCount = $collection->getSize();
        $this->log('get()', [
            'select'          => $collection->getSelect(),
            'collectionCount' => $collectionCount
        ]);

        if ($collectionCount === 1) {
            $oetermi = $collection->getFirstItem();
            if ($oetermi instanceof Oetermi) {
                return $oetermi;
            }
        }

        return null;
    }

    public function getById(
        int $oetermiId
    ) {
        $this->log('getById()', ['oetermiId' => $oetermiId]);

        /** @var \ECInternet\Sage300Account\Model\ResourceModel\Poporl\Collection $collection */
        $collection = $this->oetermiCollectionFactory->create()
            ->addFieldToFilter(Oetermi::COLUMN_ID, $oetermiId);

        $collectionCount = $collection->getSize();
        $this->log('getById()', [
            'select'          => $collection->getSelect(),
            'collectionCount' => $collectionCount
        ]);

        if ($collectionCount === 1) {
            $oetermi = $collection->getFirstItem();
            if ($oetermi instanceof Oetermi) {
                return $oetermi;
            }
        }

        return null;
    }

    public function getList(
        SearchCriteriaInterface $searchCriteria
    ) {
        $this->log('getList()');

        /** @var \ECInternet\Sage300Account\Api\Data\OetermiSearchResultsInterface $searchResults */
        $searchResults = $this->searchResultsFactory->create();

        /** @noinspection PhpExpressionResultUnusedInspection */
        $searchResults->setSearchCriteria($searchCriteria);

        /** @var \ECInternet\Sage300Account\Model\ResourceModel\Oetermi\Collection $collection */
        $collection = $this->oetermiCollectionFactory->create();
        $this->collectionProcessor->process($searchCriteria, $collection);

        $searchResults->setTotalCount($collection->getSize());

        $collection->setCurPage($searchCriteria->getCurrentPage());
        $collection->setPageSize($searchCriteria->getPageSize());
        $searchResults->setItems($collection->getData());

        return $searchResults;
    }

    public function deleteById(int $oetermiId)
    {
        if ($oetermi = $this->getById($oetermiId)) {
            try {
                $this->resourceModel->delete($oetermi);

                return true;
            } catch (Exception $e) {
                $this->log('deleteById()', ['oetermiId' => $oetermiId, 'error' => $e->getMessage()]);
            }
        }

        return false;
    }
    
    /**
     * Validate OETERMI record
     *
     * @param \ECInternet\Sage300Account\Api\Data\OetermiInterface $oetermi
     *
     * @return void
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function validate(
        OetermiInterface $oetermi
    ) {
        if (empty($oetermi->getInvoiceUniquifier())) {
            throw new LocalizedException(__('InvoiceUniquifier not set'));
        }

        if (empty($oetermi->getPaymentNumber())) {
            throw new LocalizedException(__('PaymentNumber not set'));
        }
    }

    /**
     * Does OETERMI record exist?
     *
     * @param \ECInternet\Sage300Account\Api\Data\OetermiInterface $oetermi
     *
     * @return bool
     */
    private function doesRecordExist(
        OetermiInterface $oetermi
    ) {
        $this->log('doesRecordExist()', [
            Oetermi::COLUMN_INVUNIQ => $oetermi->getInvoiceUniquifier(),
            Oetermi::COLUMN_PAYMENT => $oetermi->getPaymentNumber()
        ]);

        /** @var \ECInternet\Sage300Account\Model\ResourceModel\Oetermi\Collection $collection */
        $collection = $this->oetermiCollectionFactory->create()
            ->addFieldToFilter(Oetermi::COLUMN_INVUNIQ, $oetermi->getInvoiceUniquifier())
            ->addFieldToFilter(Oetermi::COLUMN_PAYMENT, $oetermi->getPaymentNumber());

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
        $this->logger->info('Model/OetermiRepository - ' . $message, $extra);
    }
}
