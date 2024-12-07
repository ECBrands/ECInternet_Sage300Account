<?php
/**
 * Copyright (C) EC Brands Corporation - All Rights Reserved
 * Contact Licensing@ECInternet.com for use guidelines
 */
declare(strict_types=1);

namespace ECInternet\Sage300Account\Controller;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Result\PageFactory;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Api\CartItemRepositoryInterface;
use ECInternet\Sage300Account\Helper\Data as Helper;
use ECInternet\Sage300Account\Logger\Logger;
use ECInternet\Sage300Account\Model\Config;
use ECInternet\Sage300Account\Model\ResourceModel\Oeinvh\CollectionFactory as OeinvhCollectionFactory;

/**
 * @SuppressWarnings(PHPMD.CookieAndSessionMisuse)
 * @SuppressWarnings(PHPMD.ExcessiveParameterList)
 * @SuppressWarnings(PHPMD.LongVariable)
 */
abstract class Invoice
{
    /**
     * @var \Magento\Catalog\Api\ProductRepositoryInterface
     */
    protected $productRepository;

    /**
     * @var \Magento\Checkout\Model\Session
     */
    protected $checkoutSession;

    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $customerSession;

    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    protected $request;

    /**
     * @var \Magento\Framework\UrlInterface
     */
    protected $url;

    /**
     * @var \Magento\Framework\Controller\Result\RedirectFactory
     */
    protected $resultRedirectFactory;

    /**
     * @var \Magento\Framework\Message\ManagerInterface
     */
    protected $messageManager;

    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;

    /**
     * @var \Magento\Quote\Api\CartItemRepositoryInterface
     */
    protected $cartItemRepository;

    /**
     * @var \Magento\Quote\Api\CartRepositoryInterface
     */
    protected $cartRepository;

    /**
     * @var \ECInternet\Sage300Account\Helper\Data
     */
    protected $helper;

    /**
     * @var \ECInternet\Sage300Account\Logger\Logger
     */
    protected $logger;

    /**
     * @var \ECInternet\Sage300Account\Model\Config
     */
    protected $config;

    /**
     * @var \ECInternet\Sage300Account\Model\ResourceModel\Oeinvh\CollectionFactory
     */
    protected $oeinvhCollectionFactory;

    /**
     * Invoice constructor.
     *
     * @param \Magento\Catalog\Api\ProductRepositoryInterface                         $productRepository
     * @param \Magento\Checkout\Model\Session                                         $checkoutSession
     * @param \Magento\Customer\Model\Session                                         $customerSession
     * @param \Magento\Framework\App\RequestInterface                                 $request
     * @param \Magento\Framework\Controller\Result\RedirectFactory                    $redirect
     * @param \Magento\Framework\Message\ManagerInterface                             $messageManager
     * @param \Magento\Framework\UrlInterface                                         $url
     * @param \Magento\Framework\View\Result\PageFactory                              $resultPageFactory
     * @param \Magento\Quote\Api\CartItemRepositoryInterface                          $cartItemRepository
     * @param \Magento\Quote\Api\CartRepositoryInterface                              $cartRepository
     * @param \ECInternet\Sage300Account\Helper\Data                                  $helper
     * @param \ECInternet\Sage300Account\Logger\Logger                                $logger
     * @param \ECInternet\Sage300Account\Model\Config                                 $config
     * @param \ECInternet\Sage300Account\Model\ResourceModel\Oeinvh\CollectionFactory $oeinvhCollectionFactory
     */
    public function __construct(
        ProductRepositoryInterface $productRepository,
        CheckoutSession $checkoutSession,
        CustomerSession $customerSession,
        RequestInterface $request,
        RedirectFactory $redirect,
        ManagerInterface $messageManager,
        UrlInterface $url,
        PageFactory $resultPageFactory,
        CartItemRepositoryInterface $cartItemRepository,
        CartRepositoryInterface $cartRepository,
        Helper $helper,
        Logger $logger,
        Config $config,
        OeinvhCollectionFactory $oeinvhCollectionFactory
    ) {
        $this->productRepository       = $productRepository;
        $this->checkoutSession         = $checkoutSession;
        $this->customerSession         = $customerSession;
        $this->request                 = $request;
        $this->resultRedirectFactory   = $redirect;
        $this->messageManager          = $messageManager;
        $this->url                     = $url;
        $this->resultPageFactory       = $resultPageFactory;
        $this->cartItemRepository      = $cartItemRepository;
        $this->cartRepository          = $cartRepository;
        $this->helper                  = $helper;
        $this->logger                  = $logger;
        $this->config                  = $config;
        $this->oeinvhCollectionFactory = $oeinvhCollectionFactory;
    }

    /**
     * Get request
     *
     * @return \Magento\Framework\App\RequestInterface
     */
    protected function getRequest()
    {
        return $this->request;
    }

    public function isAllowed()
    {
        return $this->customerSession->isLoggedIn();
    }

    public function getLoginRedirect()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $resultRedirect->setPath('customer/account/login');

        return $resultRedirect;
    }
}
