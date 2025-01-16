<?php
/**
 * Copyright (C) EC Brands Corporation - All Rights Reserved
 * Contact Licensing@ECInternet.com for use guidelines
 */
declare(strict_types=1);

namespace ECInternet\Sage300Account\Plugin\Catalog\Block\Product;

use Magento\Catalog\Block\Product\ListProduct;
use Magento\Catalog\Model\Product;
use Magento\Framework\View\Element\BlockFactory;
use ECInternet\Sage300Account\Helper\Data;
use ECInternet\Sage300Account\Logger\Logger;

/**
 * Plugin for Magento\Catalog\Model\Product
 */
class ListProductPlugin
{
    /**
     * @var \Magento\Framework\View\Element\BlockFactory
     */
    private $blockFactory;

    /**
     * @var \ECInternet\Sage300Account\Helper\Data
     */
    private $helper;

    /**
     * @var \ECInternet\Sage300Account\Logger\Logger
     */
    private $logger;

    /**
     * ListProductPlugin constructor.
     *
     * @param \Magento\Framework\View\Element\BlockFactory $blockFactory
     * @param \ECInternet\Sage300Account\Helper\Data       $helper
     * @param \ECInternet\Sage300Account\Logger\Logger     $logger
     */
    public function __construct(
        BlockFactory $blockFactory,
        Data $helper,
        Logger $logger
    ) {
        $this->blockFactory = $blockFactory;
        $this->helper       = $helper;
        $this->logger       = $logger;
    }

    /**
     * Add UOM to product list without breaking theme compatibility
     *
     * @param \Magento\Catalog\Block\Product\ListProduct $subject
     * @param string                                     $result
     * @param Product                                    $product
     *
     * @return string
     */
    public function afterGetProductPrice(
        /* @noinspection PhpUnusedParameterInspection */ ListProduct $subject,
        string $result,
        Product $product
    ) {
        if ($this->helper->isModuleEnabled() && $this->helper->shouldDisplayUom()) {
            $this->log('---------------------------------------');
            $this->log('afterGetProductPrice()');

            $uomDisplay = $this->helper->getUomDisplayValue($product->getSku());
            $this->log('afterGetProductPrice()', ['sku' => $product->getSku(), 'uom' => $uomDisplay]);

            if (!empty($uomDisplay)) {
                // Fill template, add to HTML.
                /** @var \ECInternet\Sage300Account\Block\Catalog\Product\UomList $uomBlock */
                if ($uomBlock = $this->createUomListBlock()) {
                    $this->log('aroundGetProductPrice() - UomList block created. Adding to price display...');

                    // Update block with current product
                    $uomBlock->setProduct($product);

                    // Build uom html and add before price html
                    $result = $uomBlock->toHtml() . $result;
                } else {
                    $this->log('aroundGetProductPrice() - Could not load UomList block.');
                }
            }

            $this->log('---------------------------------------' . PHP_EOL);
        }

        return $result;
    }

    /**
     * Create UomList block
     *
     * @return \Magento\Framework\View\Element\BlockInterface
     */
    private function createUomListBlock()
    {
        return $this->blockFactory->createBlock('ECInternet\Sage300Account\Block\Catalog\Product\UomList');
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
        $this->logger->info('Plugin/Catalog/Block/Product/ListProductPlugin - ' . $message, $extra);
    }
}
