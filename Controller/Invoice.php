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
    protected $_productRepository;

    /**
     * @var \Magento\Checkout\Model\Session
     */
    protected $_checkoutSession;

    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $_customerSession;

    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    protected $_request;

    /**
     * @var \Magento\Framework\UrlInterface
     */
    protected $_url;

    /**
     * @var \Magento\Framework\Controller\Result\RedirectFactory
     */
    protected $_resultRedirectFactory;

    /**
     * @var \Magento\Framework\Message\ManagerInterface
     */
    protected $_messageManager;

    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $_resultPageFactory;

    /**
     * @var \Magento\Quote\Api\CartItemRepositoryInterface
     */
    protected $_cartItemRepository;

    /**
     * @var \Magento\Quote\Api\CartRepositoryInterface
     */
    protected $_cartRepository;

    /**
     * @var \ECInternet\Sage300Account\Helper\Data
     */
    protected $_helper;

    /**
     * @var \ECInternet\Sage300Account\Logger\Logger
     */
    protected $_logger;

    /**
     * @var \ECInternet\Sage300Account\Model\ResourceModel\Oeinvh\CollectionFactory
     */
    protected $_oeinvhCollectionFactory;

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
        OeinvhCollectionFactory $oeinvhCollectionFactory
    ) {
        $this->_productRepository       = $productRepository;
        $this->_checkoutSession         = $checkoutSession;
        $this->_customerSession         = $customerSession;
        $this->_request                 = $request;
        $this->_resultRedirectFactory   = $redirect;
        $this->_messageManager          = $messageManager;
        $this->_url                     = $url;
        $this->_resultPageFactory       = $resultPageFactory;
        $this->_cartItemRepository      = $cartItemRepository;
        $this->_cartRepository          = $cartRepository;
        $this->_helper                  = $helper;
        $this->_logger                  = $logger;
        $this->_oeinvhCollectionFactory = $oeinvhCollectionFactory;
    }

    /**
     * Get request
     *
     * @return \Magento\Framework\App\RequestInterface
     */
    protected function getRequest()
    {
        return $this->_request;
    }

    public function isAllowed()
    {
        return $this->_customerSession->isLoggedIn();
    }

    public function getLoginRedirect()
    {
        $resultRedirect = $this->_resultRedirectFactory->create();
        $resultRedirect->setPath('customer/account/login');

        return $resultRedirect;
    }
}
