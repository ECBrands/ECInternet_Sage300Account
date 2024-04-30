<?php
/**
 * Copyright (C) EC Brands Corporation - All Rights Reserved
 * Contact Licensing@ECInternet.com for use guidelines
 */
declare(strict_types=1);

namespace ECInternet\Sage300Account\Plugin\Sales\Api;

use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\Data\OrderItemExtensionFactory;
use Magento\Sales\Api\Data\OrderSearchResultInterface;
use Magento\Sales\Api\OrderRepositoryInterface;

/**
 * Plugin for Magento\Sales\Api\OrderRepositoryInterface
 *
 * @SuppressWarnings(PHPMD.LongVariable)
 */
class OrderRepositoryInterfacePlugin
{
    /**
     * @var \Magento\Sales\Api\Data\OrderItemExtensionFactory
     */
    private $orderItemExtensionFactory;

    /**
     * OrderRepositoryInterfacePlugin constructor.
     *
     * @param \Magento\Sales\Api\Data\OrderItemExtensionFactory $orderItemExtensionFactory
     */
    public function __construct(
        OrderItemExtensionFactory $orderItemExtensionFactory
    ) {
        $this->orderItemExtensionFactory = $orderItemExtensionFactory;
    }

    /**
     * Set OrderItemExtensionAttributes for single Order
     *
     * @param \Magento\Sales\Api\OrderRepositoryInterface $subject
     * @param \Magento\Sales\Api\Data\OrderInterface      $resultOrder
     *
     * @return \Magento\Sales\Api\Data\OrderInterface
     */
    public function afterGet(
        /** @noinspection PhpUnusedParameterInspection */ OrderRepositoryInterface $subject,
        OrderInterface $resultOrder
    ) {
        return $this->setOrderItemExtensionAttributes($resultOrder);
    }

    /**
     * Set OrderItemExtensionAttributes for array of Orders
     *
     * @param \Magento\Sales\Api\OrderRepositoryInterface        $subject
     * @param \Magento\Sales\Api\Data\OrderSearchResultInterface $searchResult
     *
     * @return \Magento\Sales\Api\Data\OrderSearchResultInterface
     */
    public function afterGetList(
        /** @noinspection PhpUnusedParameterInspection */ OrderRepositoryInterface $subject,
        OrderSearchResultInterface $searchResult
    ) {
        /** @var \Magento\Sales\Api\Data\OrderInterface[] $orders */
        $orders = $searchResult->getItems();
        foreach ($orders as $order) {
            $this->setOrderItemExtensionAttributes($order);
        }

        return $searchResult;
    }

    /**
     * Set ExtensionAttributes on OrderItem
     *
     * @param \Magento\Sales\Api\Data\OrderInterface $order
     *
     * @return \Magento\Sales\Api\Data\OrderInterface
     */
    private function setOrderItemExtensionAttributes(
        OrderInterface $order
    ) {
        /** @var \Magento\Sales\Api\Data\OrderItemInterface $item */
        foreach ($order->getItems() as $item) {
            /** @var \Magento\Sales\Api\Data\OrderItemExtensionInterface|null $extensionAttributes */
            $extensionAttributes = $item->getExtensionAttributes();

            /** @var \Magento\Sales\Api\Data\OrderItemExtension $orderItemExtension */
            $orderItemExtension = $extensionAttributes ?: $this->orderItemExtensionFactory->create();

            // Set data on the ExtensionAttributes
            $orderItemExtension->setInvoiceDocnumber($item->getData('invoice_docnumber'));
            $orderItemExtension->setUom($item->getData('uom'));

            // Update the ExtensionAttributes on the OrderItem
            $item->setExtensionAttributes($orderItemExtension);
        }

        return $order;
    }
}
