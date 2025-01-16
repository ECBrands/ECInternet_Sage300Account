<?php
/**
 * Copyright (C) EC Brands Corporation - All Rights Reserved
 * Contact Licensing@ECInternet.com for use guidelines
 */
declare(strict_types=1);

namespace ECInternet\Sage300Account\Controller\Order;

use Magento\Framework\App\Action\HttpGetActionInterface;
use ECInternet\Sage300Account\Controller\Order;

/**
 * Order History controller
 */
class History extends Order implements HttpGetActionInterface
{
    /**
     * Execute 'History' action based on request and return result
     *
     * @return \Magento\Framework\Controller\ResultInterface|\Magento\Framework\App\ResponseInterface
     */
    public function execute()
    {
        if (!$this->isAllowed()) {
            return $this->getLoginRedirect();
        }

        /** @var \Magento\Framework\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->prepend(__('My Order History'));

        /** @var \Magento\Theme\Block\Html\Breadcrumbs $breadcrumbs */
        $breadcrumbs = $resultPage->getLayout()->getBlock('breadcrumbs');
        if ($breadcrumbs) {
            $breadcrumbs->addCrumb('home', [
                'label' => __('Home'),
                'title' => __('Home'),
                'link'  => $this->url->getUrl('')
            ]);
            $breadcrumbs->addCrumb('my_account', [
                'label' => __('My Account'),
                'title' => __('My Account'),
                'link'  => '/customer/account/'
            ]);
            $breadcrumbs->addCrumb('my_order_history', [
                'label' => __('My Order History'),
                'title' => __('My Order History')
            ]);
        }

        return $resultPage;
    }
}
