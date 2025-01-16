<?php
/**
 * Copyright (C) EC Brands Corporation - All Rights Reserved
 * Contact Licensing@ECInternet.com for use guidelines
 */
declare(strict_types=1);

namespace ECInternet\Sage300Account\Block\Order;

use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\View\Element\Template;
use ECInternet\Sage300Account\Model\Data\Oeordd;
use ECInternet\Sage300Account\Model\Data\Oeordh;
use ECInternet\Sage300Account\Model\ResourceModel\Oeordh\CollectionFactory as OeordhCollectionFactory;

/**
 * Order View Block
 *
 * @SuppressWarnings(PHPMD.LongVariable)
 */
class View extends Template
{
    /**
     * @var \Magento\Framework\Pricing\PriceCurrencyInterface
     */
    private $priceCurrency;

    /**
     * @var \ECInternet\Sage300Account\Model\ResourceModel\Oeordh\CollectionFactory
     */
    private $oeordhCollectionFactory;

    /**
     * View constructor.
     *
     * @param \Magento\Framework\View\Element\Template\Context                        $context
     * @param \Magento\Framework\Pricing\PriceCurrencyInterface                       $priceCurrency
     * @param \ECInternet\Sage300Account\Model\ResourceModel\Oeordh\CollectionFactory $oeordhCollectionFactory
     * @param array                                                                   $data
     */
    public function __construct(
        Context $context,
        PriceCurrencyInterface $priceCurrency,
        OeordhCollectionFactory $oeordhCollectionFactory,
        array $data = []
    ) {
        parent::__construct($context, $data);
    
        $this->priceCurrency           = $priceCurrency;
        $this->oeordhCollectionFactory = $oeordhCollectionFactory;
    }

    /**
     * Get first Oeordh matching parameter 'id'
     *
     * @return \ECInternet\Sage300Account\Model\Data\Oeordh|null
     */
    public function getOrder()
    {
        $orderId = $this->getRequest()->getParam('id');
        if ($orderId) {
            /** @var \ECInternet\Sage300Account\Model\ResourceModel\Oeordh\Collection $oeordhCollection */
            $oeordhCollection = $this->oeordhCollectionFactory->create()
                ->addFieldToFilter(Oeordh::COLUMN_ID, ['eq' => $orderId]);

            if ($oeordhCollection->getSize() == 1) {
                $oeordh = $oeordhCollection->getFirstItem();
                if ($oeordh instanceof Oeordh) {
                    return $oeordh;
                }
            }
        }

        return null;
    }

    /**
     * Get Order details: Oeordd Collection
     *
     * @return \ECInternet\Sage300Account\Model\Data\Oeordd[]
     */
    public function getOrderDetails()
    {
        /** @var \ECInternet\Sage300Account\Model\Data\Oeordh $order */
        if ($order = $this->getOrder()) {
            return $order->getOrderDetails();
        }

        return [];
    }

    /**
     * Get the formatted DiscountAmount
     *
     * @param \ECInternet\Sage300Account\Model\Data\Oeordh $order
     *
     * @return string
     */
    public function getDiscountAmountHtml(
        Oeordh $order
    ) {
        return $this->format($order->getOrderDiscountAmount());
    }

    /**
     * Get the formatted TaxAmount1
     *
     * @param \ECInternet\Sage300Account\Model\Data\Oeordh $order
     *
     * @return string
     */
    public function getTaxAmount1Html(
        Oeordh $order
    ) {
        if (!empty($order->getTaxAuthority1())) {
            return $this->format($order->getTaxAmount1());
        }

        return '';
    }

    /**
     * Get the formatted TaxAmount2
     *
     * @param \ECInternet\Sage300Account\Model\Data\Oeordh $order
     *
     * @return string
     */
    public function getTaxAmount2Html(
        Oeordh $order
    ) {
        if (!empty($order->getTaxAuthority2())) {
            return $this->format($order->getTaxAmount2());
        }

        return '';
    }

    /**
     * Get the formatted Subtotal
     *
     * @param \ECInternet\Sage300Account\Model\Data\Oeordh $order
     *
     * @return string
     */
    public function getSubtotalHtml(
        Oeordh $order
    ) {
        return $this->format($order->getSubtotal());
    }

    /**
     * Get the formatted TotalTaxAmount
     *
     * @param \ECInternet\Sage300Account\Model\Data\Oeordh $order
     *
     * @return string
     */
    public function getTotalTaxAmountHtml(
        Oeordh $order
    ) {
        return $this->format($order->getTotalTaxAmount());
    }

    /**
     * Get the formatted OrderTotal
     *
     * @param \ECInternet\Sage300Account\Model\Data\Oeordh $order
     *
     * @return string
     */
    public function getOrderTotalHtml(
        Oeordh $order
    ) {
        return $this->format($order->getOrderTotal());
    }

    /**
     * Get the formatted OrderUnitPrice
     *
     * @param \ECInternet\Sage300Account\Model\Data\Oeordd $orderDetail
     *
     * @return string
     */
    public function getOrderUnitPriceHtml(
        Oeordd $orderDetail
    ) {
        return $this->format($orderDetail->getOrderUnitPrice());
    }

    /**
     * Get the formatted ExtendedPrice
     *
     * @param \ECInternet\Sage300Account\Model\Data\Oeordd $orderDetail
     *
     * @return string
     */
    public function getExtendedPriceHtml(
        Oeordd $orderDetail
    ) {
        return $this->format($orderDetail->getExtendedPrice());
    }

    /**
     * Convert and format price value
     *
     * @param float $value
     *
     * @return string
     */
    private function format(float $value)
    {
        return $this->priceCurrency->convertAndFormat($value);
    }
}
