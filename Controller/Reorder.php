<?php
/**
 * Copyright (C) EC Brands Corporation - All Rights Reserved
 * Contact Licensing@ECInternet.com for use guidelines
 */
declare(strict_types=1);

namespace ECInternet\Sage300Account\Controller;

use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Result\PageFactory;

/**
 * @SuppressWarnings(PHPMD.CookieAndSessionMisuse)
 * @SuppressWarnings(PHPMD.LongVariable)
 */
abstract class Reorder
{
    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $customerSession;

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
     * Reorder constructor.
     *
     * @param \Magento\Customer\Model\Session                      $customerSession
     * @param \Magento\Framework\Controller\Result\RedirectFactory $redirect
     * @param \Magento\Framework\Message\ManagerInterface          $messageManager
     * @param \Magento\Framework\UrlInterface                      $url
     * @param \Magento\Framework\View\Result\PageFactory           $resultPageFactory
     */
    public function __construct(
        CustomerSession $customerSession,
        RedirectFactory $redirect,
        ManagerInterface $messageManager,
        UrlInterface $url,
        PageFactory $resultPageFactory
    ) {
        $this->customerSession       = $customerSession;
        $this->resultRedirectFactory = $redirect;
        $this->messageManager        = $messageManager;
        $this->url                   = $url;
        $this->resultPageFactory     = $resultPageFactory;
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
