<?php
/**
 * Copyright (C) EC Brands Corporation - All Rights Reserved
 * Contact Licensing@ECInternet.com for use guidelines
 */
declare(strict_types=1);

namespace ECInternet\Sage300Account\Model;

use ECInternet\Sage300Account\Api\UomRepositoryInterface;
use ECInternet\Sage300Account\Model\Data\Uom;
use ECInternet\Sage300Account\Model\ResourceModel\Uom\CollectionFactory as UomCollectionFactory;
use Psr\Log\LoggerInterface;

/**
 * Poporl model repository
 *
 * @SuppressWarnings(PHPMD.LongVariable)
 * @SuppressWarnings(PHPMD.ShortVariable)
 */
class UomRepository implements UomRepositoryInterface
{
    /**
     * @var \ECInternet\Sage300Account\Model\ResourceModel\Uom\CollectionFactory
     */
    protected $uomCollectionFactory;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * UomRepository constructor.
     *
     * @param \ECInternet\Sage300Account\Model\ResourceModel\Uom\CollectionFactory $uomCollectionFactory
     * @param \Psr\Log\LoggerInterface                                             $logger
     */
    public function __construct(
        UomCollectionFactory $uomCollectionFactory,
        LoggerInterface $logger
    ) {
        $this->uomCollectionFactory = $uomCollectionFactory;
        $this->logger               = $logger;
    }

    public function get(
        string $sku,
        string $pricelist
    ) {
        /** @var \ECInternet\Sage300Account\Model\ResourceModel\Uom\Collection $collection */
        $collection = $this->uomCollectionFactory->create()
            ->addFieldToFilter(Uom::COLUMN_ITEMNO, $sku)
            ->addFieldToFilter(Uom::COLUMN_PRICELIST, $pricelist);

        $collectionCount = $collection->getSize();
        if ($collectionCount === 1) {
            $uom = $collection->getFirstItem();
            if ($uom instanceof Uom) {
                return $uom;
            }
        }

        return null;
    }
}
