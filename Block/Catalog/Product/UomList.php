<?php
/**
 * Copyright (C) EC Brands Corporation - All Rights Reserved
 * Contact Licensing@ECInternet.com for use guidelines
 */
declare(strict_types=1);

namespace ECInternet\Sage300Account\Block\Catalog\Product;

use Magento\Catalog\Model\Product;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use ECInternet\Sage300Account\Helper\Data;

/**
 * Catalog Product View Block - Manually loaded in ListProductPlugin
 */
class UomList extends Template
{
    protected $_template = 'ECInternet_Sage300Account::catalog/product/list/uom.phtml';

    /**
     * @var \Magento\Catalog\Model\Product
     */
    private $product;

    /**
     * @var \ECInternet\Sage300Account\Helper\Data
     */
    private $helper;

    /**
     * UomList constructor.
     *
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \ECInternet\Sage300Account\Helper\Data           $helper
     * @param array                                            $data
     */
    public function __construct(
        Context $context,
        Data $helper,
        array $data = []
    ) {
        parent::__construct($context, $data);

        $this->helper = $helper;
    }

    /**
     * Set local product
     *
     * @param \Magento\Catalog\Model\Product $product
     *
     * @return $this
     */
    public function setProduct(Product $product)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * Get local product
     *
     * @return \Magento\Catalog\Model\Product
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * Get text for UOM display
     *
     * @return string
     */
    public function getUomDisplayValue()
    {
        return $this->helper->getUomDisplayValue($this->getProduct()->getSku());
    }
}
