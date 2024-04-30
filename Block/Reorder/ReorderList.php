<?php
/**
 * Copyright (C) EC Brands Corporation - All Rights Reserved
 * Contact Licensing@ECInternet.com for use guidelines
 */
declare(strict_types=1);

namespace ECInternet\Sage300Account\Block\Reorder;

use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Block\Product\Context;
use Magento\Catalog\Block\Product\View;
use Magento\Catalog\Helper\Output as OutputHelper;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\ProductTypes\ConfigInterface;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory as ProductCollection;
use Magento\Checkout\Model\SessionFactory as CheckoutSessionFactory;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Customer\Model\SessionFactory as CustomerSessionFactory;
use Magento\Customer\Api\CustomerRepositoryInterface as CustomerRepository;
use Magento\Framework\App\ActionInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Json\EncoderInterface as JsonEncoder;
use Magento\Framework\Locale\FormatInterface;
use Magento\Framework\Pricing\Helper\Data as PricingHelper;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\Framework\Stdlib\StringUtils;
use Magento\Framework\Url\EncoderInterface as UrlEncoder;
use Magento\Framework\Url\Helper\Data as UrlHelper;
use ECInternet\Sage300Account\Logger\Logger;
use ECInternet\Sage300Account\Model\Data\Oeshdt;
use ECInternet\Sage300Account\Model\ResourceModel\Oeshdt\CollectionFactory as OeshdtCollection;
use Exception;

/**
 * @SuppressWarnings(PHPMD.LongVariable)
 */
class ReorderList extends View
{
    const ATTRIBUTE_CUSTOMER_NUMBER = 'customer_number';

    /**
     * @var \Magento\Catalog\Helper\Output
     */
    private $_outputHelper;

    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\Collection
     */
    private $_productCollection;

    /**
     * @var \Magento\Checkout\Model\Session
     */
    private $_checkoutSession;

    /**
     * @var \Magento\Customer\Api\CustomerRepositoryInterface
     */
    private $_customerRepository;

    /**
     * @var \Magento\Customer\Model\SessionFactory
     */
    private $_customerSessionFactory;

    /**
     * @var \Magento\Framework\Pricing\Helper\Data
     */
    private $_pricingHelper;

    /**
     * @var \Magento\Framework\Url\Helper\Data
     */
    private $_urlHelper;

    /**
     * @var \ECInternet\Sage300Account\Logger\Logger
     */
    private $logger;

    /**
     * @var \ECInternet\Sage300Account\Model\ResourceModel\Oeshdt\CollectionFactory
     */
    private $_oeshdtCollectionFactory;

    /**
     * @var \ECInternet\Sage300Account\Model\ResourceModel\Oeshdt\Collection
     */
    private $_oeshdtCollection;

    /**
     * @param \Magento\Catalog\Block\Product\Context                                  $context
     * @param \Magento\Framework\Url\EncoderInterface                                 $urlEncoder
     * @param \Magento\Framework\Json\EncoderInterface                                $jsonEncoder
     * @param \Magento\Framework\Stdlib\StringUtils                                   $string
     * @param \Magento\Catalog\Helper\Product                                         $productHelper
     * @param \Magento\Catalog\Model\ProductTypes\ConfigInterface                     $productTypeConfig
     * @param \Magento\Framework\Locale\FormatInterface                               $localeFormat
     * @param \Magento\Customer\Model\Session                                         $customerSession
     * @param \Magento\Customer\Model\SessionFactory                                  $customerSessionFactory
     * @param \Magento\Catalog\Api\ProductRepositoryInterface                         $productRepository
     * @param \Magento\Framework\Pricing\PriceCurrencyInterface                       $priceCurrency
     * @param \Magento\Catalog\Helper\Output                                          $outputHelper
     * @param \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory          $productCollection
     * @param \Magento\Checkout\Model\SessionFactory                                  $checkoutSession
     * @param \Magento\Customer\Api\CustomerRepositoryInterface                       $customerRepository
     * @param \Magento\Framework\Pricing\Helper\Data                                  $pricingHelper
     * @param \Magento\Framework\Url\Helper\Data                                      $urlHelper
     * @param \ECInternet\Sage300Account\Logger\Logger                                $logger
     * @param \ECInternet\Sage300Account\Model\ResourceModel\Oeshdt\CollectionFactory $oeshdtCollectionFactory
     * @param array                                                                   $data
     */
    public function __construct(
        Context $context,
        UrlEncoder $urlEncoder,
        JsonEncoder $jsonEncoder,
        StringUtils $string,
        \Magento\Catalog\Helper\Product $productHelper,
        ConfigInterface $productTypeConfig,
        FormatInterface $localeFormat,
        CustomerSession $customerSession,
        CustomerSessionFactory $customerSessionFactory,
        ProductRepositoryInterface $productRepository,
        PriceCurrencyInterface $priceCurrency,
        OutputHelper $outputHelper,
        ProductCollection $productCollection,
        CheckoutSessionFactory $checkoutSession,
        CustomerRepository $customerRepository,
        PricingHelper $pricingHelper,
        UrlHelper $urlHelper,
        Logger $logger,
        OeshdtCollection $oeshdtCollectionFactory,
        array $data = []
    ) {
        $this->_outputHelper            = $outputHelper;
        $this->_productCollection       = $productCollection->create();
        $this->_checkoutSession         = $checkoutSession->create();
        $this->_customerSessionFactory  = $customerSessionFactory;
        $this->_customerRepository      = $customerRepository;
        $this->_pricingHelper           = $pricingHelper;
        $this->_urlHelper               = $urlHelper;
        $this->logger                   = $logger;
        $this->_oeshdtCollectionFactory = $oeshdtCollectionFactory;

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
    }

    /**
     * Get Custom products
     *
     * @return \Magento\Catalog\Model\ResourceModel\Product\Collection
     */
    public function getProductCollection()
    {
        $this->log('getProductCollection()');

        /** @var \Magento\Customer\Api\Data\CustomerInterface $customer */
        if ($customer = $this->getCurrentCustomer()) {
            if ($customerNumberAttribute = $customer->getCustomAttribute(self::ATTRIBUTE_CUSTOMER_NUMBER)) {
                if ($customerNumberValue = $customerNumberAttribute->getValue()) {
                    $this->log('getProductCollection()', [self::ATTRIBUTE_CUSTOMER_NUMBER => $customerNumberValue]);

                    $previouslyPurchasedSkus = $this->getPreviouslyPurchasedSkus((string)$customerNumberValue);
                    $this->log('getProductCollection()', ['previouslyPurchasedSkus' => $previouslyPurchasedSkus]);

                    // Limit to previously purchased SKUs
                    // Add 'short_description' and 'default_price_list_code' data
                    $this->_productCollection
                        ->addAttributeToFilter('sku', ['in' => $previouslyPurchasedSkus])
                        ->addAttributeToSelect('short_description')
                        ->addAttributeToSelect('default_price_list_code');
                } else {
                    $this->log("getProductCollection() - Customer does not have '".self::ATTRIBUTE_CUSTOMER_NUMBER."' attribute set.");
                }
            } else {
                $this->log("getProductCollection() - Customer does not have '".self::ATTRIBUTE_CUSTOMER_NUMBER."' attribute.");
            }
        } else {
            $this->log('getProductCollection() - CustomerSession did not return valid Customer.');
        }

        $this->log('getProductCollection()', ['query' => $this->_productCollection->getSelect()]);

        return $this->_productCollection;
    }

    /**
     * Pulls 'short_description' attribute data for Product
     *
     * @param \Magento\Catalog\Model\Product $product
     *
     * @return string
     */
    public function getShortDescription(
        Product $product
    ) {
        return $this->getProductAttributeData($product, 'short_description');
    }

    /**
     * If Sage300Pricing is installed it will add a plugin on this method to pull correct value from ICPRIC* tables
     *
     * @param \Magento\Catalog\Model\Product $product
     *
     * @return string
     */
    public function getUom(
        Product $product
    ) {
        $this->log('getUom()', ['sku' => $product->getSku()]);

        return '';
    }

    /**
     * Pull SHIPDATE from OESHDT table. Import process should have set this value as MAX(SHIPDATE)
     *
     * @param \Magento\Catalog\Model\Product $product
     *
     * @return string|null
     */
    public function getLastOrderedDate(
        Product $product
    ) {
        $this->log('getLastOrderedDate()', ['sku' => $product->getSku()]);

        $sku = $product->getSku();

        if ($customer = $this->getCurrentCustomer()) {
            if ($customerNumber = $this->getCustomerNumber($customer)) {
                $this->log('getLastOrderedDate()', [self::ATTRIBUTE_CUSTOMER_NUMBER => $customerNumber]);

                /** @var \ECInternet\Sage300Account\Model\ResourceModel\Oeshdt\Collection $collection */
                $collection = $this->getSalesHistoryDetails($customerNumber);
                $collection->addFieldToFilter(Oeshdt::COLUMN_ITEM, ['eq' => $sku]);
                if ($collection->getSize() === 1) {
                    $oeshdt = $collection->getFirstItem();
                    if ($oeshdt instanceof Oeshdt) {
                        return $oeshdt->getShipDate();
                    }
                }
            }
        }

        return null;
    }

    /**
     * Build price HTML for display
     *
     * @param \Magento\Catalog\Model\Product $product
     *
     * @return float|string
     */
    public function getPriceHtml(Product $product)
    {
        $price = $this->getPrice($product);

        return $this->_pricingHelper->currency($price, true, false);
    }

    /**
     * Pull product count from current Quote
     *
     * @param \Magento\Catalog\Model\Product $product
     *
     * @return float|null
     */
    public function getQuoteItemQty(
        Product $product
    ) {
        if ($quote = $this->getCurrentQuote()) {
            /** @var \Magento\Quote\Api\Data\CartItemInterface[] $items */
            if ($items = $quote->getItems()) {
                foreach ($items as $item) {
                    if ($product->getSku() == $item->getSku()) {
                        return $item->getQty();
                    }
                }
            }
        }

        return null;
    }

    /**
     * @param \Magento\Catalog\Model\Product $product
     *
     * @return float|int|null
     */
    public function getFewestValidCount(
        Product $product
    ) {
        $this->log('getFewestValidCount()', ['sku' => $product->getSku()]);

        $minAllowed = $this->getMinAllowed($product);
        $this->log('getFewestValidCount()', ['minAllowed' => $minAllowed]);

        // We need 'min_sale_qty'
        if ($minAllowed === null) {
            return null;
        }

        $qtyIncrements = $this->getQtyIncrements($product);
        $this->log('getFewestValidCount()', ['qtyIncrements' => $qtyIncrements]);

        // We need 'qty_increments'
        if ($qtyIncrements === null) {
            return null;
        }

        $this->log('getFewestValidCount()', ['minAllowed' => $minAllowed, 'qtyIncrements' => $qtyIncrements]);

        $modulo = $minAllowed % $qtyIncrements;
        $this->log('getFewestValidCount()', ['modulo' => $modulo]);

        if ($modulo === 0) {
            // minAllowed is a quantity increment
            return $minAllowed;
        } else {
            // TODO: Why didn't I use ceiling?
            // minAllowed is not a quantity increment.  Get the floor, add one, and multiply by qtyIncrements
            $floor = $this->getFloor($minAllowed, $qtyIncrements);
            if ($floor !== null) {
                return $qtyIncrements * ++$floor;
            }
        }

        return null;
    }

    /**
     * Lookup 'qty_increments' from StockRegistry
     *
     * @param \Magento\Catalog\Model\Product $product
     *
     * @return int|null
     */
    public function getQtyIncrements(Product $product)
    {
        $this->log('getQtyIncrements()', ['product' => $product->getSku()]);

        if ($stockItem = $this->stockRegistry->getStockItem($product->getId(), 1)) {
            $this->log('getQtyIncrements()', ['stockItem' => $stockItem->getData()]);

            // TODO: Use this until we can step through getQtyIncrements() logic
            $qtyIncrements = (int)$stockItem->getData('qty_increments');
            $this->log('getQtyIncrements()', ["getData('qty_increments')" => $qtyIncrements]);

            return $qtyIncrements;
        }

        return null;
    }

    /**
     * Lookup 'min_sale_qty' from StockRegistry
     *
     * @param \Magento\Catalog\Model\Product $product
     *
     * @return float|null
     */
    public function getMinAllowed(Product $product)
    {
        $this->log('getMinAllowed()', ['sku' => $product->getSku()]);

        if ($stockItem = $this->stockRegistry->getStockItem($product->getId(), 1)) {
            $this->log('getMinAllowed()', ['stockItem' => $stockItem->getData()]);

            return $stockItem->getMinSaleQty();
        }

        return null;
    }

    /**
     * @param \Magento\Catalog\Model\Product $product
     *
     * @return array
     */
    public function getAddToCartPostParams(
        Product $product
    ) {
        $url = $this->getAddToCartUrl($product, ['_escape' => false]);

        return [
            'action' => $url,
            'data'   => [
                'product' => (int)$product->getEntityId(),
                ActionInterface::PARAM_NAME_URL_ENCODED => $this->_urlHelper->getEncodedUrl($url)
            ]
        ];
    }

    /**
     * Get fresh product from repository, return 'price' value
     *
     * @param \Magento\Catalog\Model\Product $product
     *
     * @return float|null
     */
    public function getPrice(
        Product $product
    ) {
        $this->log('getPrice()', ['sku' => $product->getSku()]);

        // Initial value. Not sure why null
        $price = $product->getPrice();

        // Looking up from repo gets price
        /** @var \Magento\Catalog\Api\Data\ProductInterface $prod */
        if ($prod = $this->getProductFromRepo($product->getSku())) {
            $price = $prod->getPrice();
        }

        return $price;
    }

    /**
     * @param string $sku
     *
     * @return \Magento\Catalog\Api\Data\ProductInterface|null
     */
    private function getProductFromRepo(string $sku)
    {
        try {
            return $this->productRepository->get($sku);
        } catch (NoSuchEntityException $e) {
            $this->log('getProduct()', ['exception' => $e->getMessage()]);
        }

        return null;
    }

    private function getProductAttributeData(
        Product $product,
        string $attributeCode
    ) {
        try {
            return $this->_outputHelper->productAttribute($product, $product->getData($attributeCode), $attributeCode);
        } catch (LocalizedException $e) {
            $this->log('getProductAttributeData()', [
                'product'       => $product->getSku(),
                'attributeCode' => $attributeCode,
                'exception'     => $e->getMessage()
            ]);
        }

        return '';
    }

    private function getPreviouslyPurchasedSkus(string $customerNumber)
    {
        $this->log('getPreviouslyPurchasedSkus()', ['customerNumber' => $customerNumber]);

        $skus = [];

        $salesHistoryDetails = $this->getSalesHistoryDetails($customerNumber);
        foreach ($salesHistoryDetails as $item) {
            /** @var \ECInternet\Sage300Account\Model\Data\Oeshdt $item */
            $skus[] = $item->getItemNumber();
        }

        return $skus;
    }

    /**
     * @param \Magento\Customer\Api\Data\CustomerInterface $customer
     *
     * @return string|null
     */
    private function getCustomerNumber(CustomerInterface $customer)
    {
        if ($customerNumberAttribute = $customer->getCustomAttribute(self::ATTRIBUTE_CUSTOMER_NUMBER)) {
            if ($customerNumberValue = $customerNumberAttribute->getValue()) {
                return (string)$customerNumberValue;
            } else {
                $this->log("getCustomerNumber() - Customer does not have '".self::ATTRIBUTE_CUSTOMER_NUMBER."' attribute set.");
            }
        } else {
            $this->log("getCustomerNumber() - Customer does not have '".self::ATTRIBUTE_CUSTOMER_NUMBER."' attribute.");
        }

        return null;
    }

    /**
     * Lazyload pattern for grabbing OESHDT records for CUSTOMER value
     *
     * @param string $customerNumber
     *
     * @return \ECInternet\Sage300Account\Model\ResourceModel\Oeshdt\Collection
     */
    private function getSalesHistoryDetails(string $customerNumber)
    {
        $this->log('getSalesHistoryDetails()', ['customerNumber' => $customerNumber]);

        if ($this->_oeshdtCollection === null) {
            $this->_oeshdtCollection = $this->_oeshdtCollectionFactory->create()
                ->addFieldToFilter(Oeshdt::COLUMN_CUSTOMER, $customerNumber)
                ->addFieldToFilter(Oeshdt::COLUMN_IS_ACTIVE, 1);
        }

        return $this->_oeshdtCollection;
    }

    /**
     * @param float $minAllowed
     * @param int   $qtyIncrements
     *
     * @return int|null
     */
    private function getFloor(float $minAllowed, int $qtyIncrements)
    {
        $this->log('getFloor()', ['minAllowed' => $minAllowed, 'qtyIncrements' => $qtyIncrements]);

        if ($minAllowed > $qtyIncrements) {
            return (int)floor($minAllowed / $qtyIncrements);
        } elseif ($minAllowed < $qtyIncrements) {
            return (int)floor($qtyIncrements / $minAllowed);
        }

        return null;
    }

    /**
     * Get checkout quote instance by current session
     *
     * @return \Magento\Quote\Api\Data\CartInterface|\Magento\Quote\Model\Quote|null
     */
    private function getCurrentQuote()
    {
        try {
            return $this->_checkoutSession->getQuote();
        } catch (Exception $e) {
            $this->log('getCurrentQuote()', ['exception' => $e->getMessage()]);
        }

        return null;
    }

    /**
     * Get customer by Customer ID
     *
     * @return \Magento\Customer\Api\Data\CustomerInterface|null
     */
    private function getCurrentCustomer()
    {
        if ($customerId = $this->_customerSessionFactory->create()->getCustomerId()) {
            try {
                return $this->_customerRepository->getById($customerId);
            } catch (Exception $e) {
                $this->log('getCurrentCustomer()', ['exception' => $e->getMessage()]);
            }
        }

        return null;
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
        $this->logger->info('Block/Reorder/ReorderList - ' . $message, $extra);
    }
}
