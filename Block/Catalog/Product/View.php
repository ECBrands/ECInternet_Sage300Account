<?php
/**
 * Copyright (C) EC Brands Corporation - All Rights Reserved
 * Contact Licensing@ECInternet.com for use guidelines
 */
declare(strict_types=1);

namespace ECInternet\Sage300Account\Block\Catalog\Product;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Block\Product\Context;
use Magento\Catalog\Helper\Product as ProductHelper;
use Magento\Catalog\Model\ProductTypes\ConfigInterface;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\Json\EncoderInterface as JsonEncoderInterface;
use Magento\Framework\Locale\FormatInterface;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\Framework\Stdlib\StringUtils;
use Magento\Framework\Url\EncoderInterface as UrlEncoderInterface;
use ECInternet\Sage300Account\Helper\Data;

/**
 * Catalog Product View Block
 */
class View extends \Magento\Catalog\Block\Product\View
{
    /**
     * @var \ECInternet\Sage300Account\Helper\Data
     */
    private $helper;

    /**
     * View constructor.
     *
     * @param \Magento\Catalog\Block\Product\Context              $context
     * @param \Magento\Catalog\Api\ProductRepositoryInterface     $productRepository
     * @param \Magento\Catalog\Helper\Product                     $productHelper
     * @param \Magento\Catalog\Model\ProductTypes\ConfigInterface $productTypeConfig
     * @param \Magento\Customer\Model\Session                     $customerSession
     * @param \Magento\Framework\Json\EncoderInterface            $jsonEncoder
     * @param \Magento\Framework\Locale\FormatInterface           $localeFormat
     * @param \Magento\Framework\Pricing\PriceCurrencyInterface   $priceCurrency
     * @param \Magento\Framework\Stdlib\StringUtils               $string
     * @param \Magento\Framework\Url\EncoderInterface             $urlEncoder
     * @param \ECInternet\Sage300Account\Helper\Data              $helper
     * @param array                                               $data
     */
    public function __construct(
        Context $context,
        ProductRepositoryInterface $productRepository,
        ProductHelper $productHelper,
        ConfigInterface $productTypeConfig,
        CustomerSession $customerSession,
        JsonEncoderInterface $jsonEncoder,
        FormatInterface $localeFormat,
        PriceCurrencyInterface $priceCurrency,
        StringUtils $string,
        UrlEncoderInterface $urlEncoder,
        Data $helper,
        array $data = []
    ) {
        parent::__construct(
            $context,
            $urlEncoder,
            $jsonEncoder,
            $string,
            $productHelper,
            $productTypeConfig,
            $localeFormat,
            $customerSession,
            $productRepository,
            $priceCurrency,
            $data
        );

        $this->helper = $helper;
    }

    /**
     * Should we show the Block?
     *
     * @return bool
     */
    public function showBlock()
    {
        return $this->helper->shouldDisplayUom();
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
