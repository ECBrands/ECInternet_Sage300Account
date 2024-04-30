<?php
/**
 * Copyright (C) EC Brands Corporation - All Rights Reserved
 * Contact Licensing@ECInternet.com for use guidelines
 */
declare(strict_types=1);

namespace ECInternet\Sage300Account\Plugin\Quote\Item;

use Magento\Quote\Model\Quote\Item\ToOrderItem;
use Magento\Sales\Model\Order\Item as OrderItem;

/**
 * Plugin for Magento\Quote\Model\Quote\Item\ToOrderItemPlugin
 */
class ToOrderItemPlugin
{
    /**
     * Set 'invoice_docnumber' and 'uom' on OrderItem
     *
     * @param \Magento\Quote\Model\Quote\Item\ToOrderItem                             $subject
     * @param callable                                                                $proceed
     * @param \Magento\Quote\Model\Quote\Item|\Magento\Quote\Model\Quote\Address\Item $item
     * @param array                                                                   $data
     *
     * @return \Magento\Sales\Api\Data\OrderItemInterface
     * @noinspection PhpMissingParamTypeInspection
     */
    public function aroundConvert(
        /** @noinspection PhpUnusedParameterInspection */ ToOrderItem $subject,
        callable $proceed,
        $item,
        $data = []
    ) {
        /** @var \Magento\Sales\Api\Data\OrderItemInterface $orderItem */
        $orderItem = $proceed($item, $data);

        /** @var \Magento\Catalog\Model\Product\Configuration\Item\Option\OptionInterface $additionalOptions */
        $additionalOptions = $item->getOptionByCode('additional_options');

        // Check if there is any additional options in Quote Item
        if (!empty($additionalOptions)) {
            // Get Order Item's existing options
            $options = $orderItem->getProductOptions();

            // Set additional options to the Order Item
            $options['additional_options'] = json_decode($additionalOptions->getValue());
            $orderItem->setProductOptions($options);
        }

        // Set fields on OrderItem
        if ($orderItem instanceof OrderItem) {
            $orderItem->setData('invoice_docnumber', $item->getData('invoice_docnumber'));
            $orderItem->setData('uom', $item->getData('uom'));
        }

        return $orderItem;
    }
}
