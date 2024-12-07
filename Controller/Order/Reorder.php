<?php
/**
 * Copyright (C) EC Brands Corporation - All Rights Reserved
 * Contact Licensing@ECInternet.com for use guidelines
 */
declare(strict_types=1);

namespace ECInternet\Sage300Account\Controller\Order;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Result\PageFactory;
use Magento\Quote\Api\CartManagementInterface;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Store\Model\StoreManagerInterface;
use ECInternet\Sage300Account\Api\OeordhRepositoryInterface;
use ECInternet\Sage300Account\Controller\Order;
use ECInternet\Sage300Account\Helper\Data as Helper;
use ECInternet\Sage300Account\Logger\Logger;
use ECInternet\Sage300Account\Model\Config;
use ECInternet\Sage300Account\Model\ResourceModel\Oeordh\CollectionFactory as OeordhCollectionFactory;
use Exception;

/**
 * Order Reorder controller
 *
 * @SuppressWarnings(PHPMD.ShortVariable)
 */
class Reorder extends Order implements HttpGetActionInterface
{
    /**
     * @var \Magento\Customer\Api\CustomerRepositoryInterface
     */
    private $customerRepository;

    /**
     * @var \Magento\Quote\Api\CartManagementInterface
     */
    private $cartManagement;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private $storeManager;

    /**
     * Reorder constructor.
     *
     * @param \Magento\Catalog\Api\ProductRepositoryInterface                         $productRepository
     * @param \Magento\Customer\Model\Session                                         $customerSession
     * @param \Magento\Framework\App\RequestInterface                                 $request
     * @param \Magento\Framework\Controller\Result\RedirectFactory                    $redirect
     * @param \Magento\Framework\Message\ManagerInterface                             $messageManager
     * @param \Magento\Framework\UrlInterface                                         $url
     * @param \Magento\Framework\View\Result\PageFactory                              $resultPageFactory
     * @param \Magento\Quote\Api\CartRepositoryInterface                              $cartRepository
     * @param \ECInternet\Sage300Account\Api\OeordhRepositoryInterface                $oeordhRepository
     * @param \ECInternet\Sage300Account\Helper\Data                                  $helper
     * @param \ECInternet\Sage300Account\Logger\Logger                                $logger
     * @param \ECInternet\Sage300Account\Model\Config                                 $config
     * @param \ECInternet\Sage300Account\Model\ResourceModel\Oeordh\CollectionFactory $oeordhCollectionFactory
     * @param \Magento\Customer\Api\CustomerRepositoryInterface                       $customerRepository
     * @param \Magento\Quote\Api\CartManagementInterface                              $cartManagement
     * @param \Magento\Store\Model\StoreManagerInterface                              $storeManager
     */
    public function __construct(
        ProductRepositoryInterface $productRepository,
        CustomerSession $customerSession,
        RequestInterface $request,
        RedirectFactory $redirect,
        ManagerInterface $messageManager,
        UrlInterface $url,
        PageFactory $resultPageFactory,
        CartRepositoryInterface $cartRepository,
        OeordhRepositoryInterface $oeordhRepository,
        Helper $helper,
        Logger $logger,
        Config $config,
        OeordhCollectionFactory $oeordhCollectionFactory,
        CustomerRepositoryInterface $customerRepository,
        CartManagementInterface $cartManagement,
        StoreManagerInterface $storeManager
    ) {
        parent::__construct(
            $productRepository,
            $customerSession,
            $request,
            $redirect,
            $messageManager,
            $url,
            $resultPageFactory,
            $cartRepository,
            $oeordhRepository,
            $logger,
            $config,
            $oeordhCollectionFactory
        );

        $this->customerRepository = $customerRepository;
        $this->cartManagement     = $cartManagement;
        $this->storeManager       = $storeManager;
    }

    /**
     * Execute 'Reorder' action based on request and return result
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $this->log('execute()');

        if (!$this->isAllowed()) {
            return $this->getLoginRedirect();
        }

        /** @var \Magento\Framework\Controller\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();

        /** @var \ECInternet\Sage300Account\Api\Data\OeorddInterface[] $orderDetails */
        $orderDetails = $this->getOrderDetails();
        if (count($orderDetails) > 0) {
            $products = [];
            $valid    = true;

            // Attempt to add each item
            //TODO: Refactor this into method which returns Product[] or null (indicating failure)
            foreach ($orderDetails as $orderDetail) {
                /** @var \ECInternet\Sage300Account\Model\Data\Oeordd $orderDetail */

                // Cache sku
                $sku = $orderDetail->getItem();

                if (!$this->shouldSkipSku($sku)) {
                    if ($product = $this->getProduct($sku)) {
                        // Add product to array
                        $products[] = $product;
                    } else {
                        $this->messageManager->addErrorMessage(
                            __("Unable to re-order. Could not find product [$sku] in Magento.")
                        );
                        $valid = false;

                        break;
                    }
                }
            }

            // All products appear to exist in Magento.  Let's add them to the cart.
            if ($valid) {
                /** @var \Magento\Quote\Api\Data\CartInterface|\Magento\Quote\Model\Quote $cart */
                if ($cart = $this->getCart()) {
                    // Iterate over products attempting to add them.
                    // If we are unable to add a product, we're marking the order as invalid.
                    foreach ($products as $product) {
                        try {
                            $this->log("execute() - Adding sku [{$product->getSku()}.");
                            $cart->addProduct($product);
                            $this->log('execute() - Sku added.');
                        } catch (LocalizedException $e) {
                            // Not able to add product to cart - How should we handle?
                            $this->log('execute()', ['exception' => $e->getMessage()]);

                            // Mark as invalid
                            $valid = false;
                        }
                    }

                    // If we're still good, send Customer to checkout page
                    if ($valid) {
                        // Save cart
                        $this->cartRepository->save($cart);

                        // Add message
                        $this->messageManager->addSuccessMessage(
                            __('Products from original order added to cart.')
                        );

                        // Redirect to cart
                        return $resultRedirect->setPath('checkout/cart');
                    }
                }
            }
        }

        // Invalid request, redirect to order history
        return $resultRedirect->setPath('accounting/order/history');
    }

    /**
     * Get order details
     *
     * @return \ECInternet\Sage300Account\Api\Data\OeorddInterface[]
     */
    private function getOrderDetails()
    {
        // Get order_id from query param
        if ($orderId = $this->getRequest()->getParam('id')) {
            if (is_numeric($orderId)) {
                if ($order = $this->getOrder((int)$orderId)) {
                    return $order->getOrderDetails();
                }
            }
        }

        return [];
    }

    /**
     * Get Oeordh from repository
     *
     * @param int $orderId
     *
     * @return \ECInternet\Sage300Account\Api\Data\OeordhInterface|null
     */
    private function getOrder(int $orderId)
    {
        try {
            return $this->oeordhRepository->getById($orderId);
        } catch (Exception $e) {
            $this->log('getOrder()', ['exception' => $e->getMessage()]);
        }

        return null;
    }

    /**
     * Get Product via ProductRepository
     *
     * @param string $sku
     *
     * @return \Magento\Catalog\Api\Data\ProductInterface|null
     */
    private function getProduct(string $sku)
    {
        try {
            return $this->productRepository->get($sku);
        } catch (NoSuchEntityException $e) {
            $this->log('getProduct()', ['exception' => $e->getMessage()]);
        }

        return null;
    }

    /**
     * Get Cart for current Customer
     *
     * @return \Magento\Quote\Api\Data\CartInterface|null
     */
    private function getCart()
    {
        if ($this->customerSession->isLoggedIn()) {
            if ($customerId = $this->customerSession->getCustomerId()) {
                if (is_numeric($customerId)) {
                    return $this->getCartForCustomerId((int)$customerId);
                }
            }
        }

        return null;
    }

    /**
     * Attempt to lookup the active Cart of the Customer. If that fails, attempt to build a new Cart for the Customer.
     *
     * @param int $customerId
     *
     * @return \Magento\Quote\Api\Data\CartInterface|null
     */
    private function getCartForCustomerId(int $customerId)
    {
        try {
            return $this->cartRepository->getActiveForCustomer($customerId);
        } catch (NoSuchEntityException $e) {
            try {
                $this->log("Unable to 'getActiveForCustomer()' - {$e->getMessage()}.");

                // Try to return a new cart
                return $this->buildFreshCart();
            } catch (Exception $inner) {
                $this->log("Unable to 'buildFreshCart()' - {$inner->getMessage()}.");
            }
        }

        return null;
    }

    /**
     * Should we skip this SKU?
     *
     * @param string $sku
     *
     * @return bool
     */
    private function shouldSkipSku(string $sku)
    {
        if ($skippableSkuString = $this->config->getSkippableSkus()) {
            $skippableSkuArray = explode(',', $skippableSkuString);

            return in_array($sku, $skippableSkuArray);
        }

        return false;
    }

    /**
     * Clear current Cart and build new one
     *
     * @return \Magento\Quote\Api\Data\CartInterface
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function buildFreshCart()
    {
        $this->log('buildFreshCart()');

        $emptyCartId = $this->cartManagement->createEmptyCart();
        $this->log('buildFreshCart()', ['emptyCartId' => $emptyCartId]);

        $quote = $this->cartRepository->get($emptyCartId);
        $quote->setStoreId($this->storeManager->getStore()->getId());
        $quote->setCurrency();
        $quote->assignCustomer($this->customerRepository->getById($this->customerSession->getCustomerId()));

        return $quote;
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
        $this->logger->info('Controller/Order/Reorder - ' . $message, $extra);
    }
}
