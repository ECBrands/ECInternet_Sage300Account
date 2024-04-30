<?php
/**
 * Copyright (C) EC Brands Corporation - All Rights Reserved
 * Contact Licensing@ECInternet.com for use guidelines
 */
declare(strict_types=1);

namespace ECInternet\Sage300Account\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Model\Order;
use ECInternet\Sage300Account\Helper\Data;

/**
 * Observer for 'sales_order_place_after' event
 */
class SalesOrderPlaceAfter implements ObserverInterface
{
    /**
     * @var \Magento\Sales\Api\OrderRepositoryInterface
     */
    private $orderRepository;

    /**
     * @var \ECInternet\Sage300Account\Helper\Data
     */
    private $helper;

    /**
     * SalesOrderPlaceAfter constructor.
     *
     * @param \Magento\Sales\Api\OrderRepositoryInterface $orderRepository
     * @param \ECInternet\Sage300Account\Helper\Data      $helper
     */
    public function __construct(
        OrderRepositoryInterface $orderRepository,
        Data $helper
    ) {
        $this->orderRepository = $orderRepository;
        $this->helper          = $helper;
    }

    /**
     * Update Order values if it's an InvoicePayment
     *
     * @param \Magento\Framework\Event\Observer $observer
     *
     * @return void
     */
    public function execute(
        Observer $observer
    ) {
        /** @var \Magento\Sales\Model\Order $order */
        $order = $observer->getData('order');
        if ($this->isInvoicePaymentOrder($order)) {
            $order->setState(Data::INVOICE_PAYMENT_ORDER_STATUS);
            $order->setStatus(Data::INVOICE_PAYMENT_ORDER_STATUS);
            $order->setData('is_invoice_payment', 1);

            $this->orderRepository->save($order);
        }
    }

    /**
     * Is this order an InvoicePayment?
     *
     * @param \Magento\Sales\Model\Order $order
     *
     * @return bool
     */
    private function isInvoicePaymentOrder(
        Order $order
    ) {
        // Make sure we have a sku to compare against
        if ($invoicePaymentSku = $this->helper->getInvoicePaymentSku()) {
            /** @var \Magento\Sales\Api\Data\OrderItemInterface[] $orderItems */
            $orderItems = $order->getItems();
            if (count($orderItems)) {
                foreach ($orderItems as $orderItem) {
                    /** @var \Magento\Sales\Api\Data\OrderItemInterface $orderItem */
                    if ($orderItem->getSku() !== $invoicePaymentSku) {
                        return false;
                    }
                }

                return true;
            }
        }

        return false;
    }
}
