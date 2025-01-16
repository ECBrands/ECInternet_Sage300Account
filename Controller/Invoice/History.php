<?php
/**
 * Copyright (C) EC Brands Corporation - All Rights Reserved
 * Contact Licensing@ECInternet.com for use guidelines
 */
declare(strict_types=1);

namespace ECInternet\Sage300Account\Controller\Invoice;

use Magento\Framework\App\Action\HttpGetActionInterface;
use ECInternet\Sage300Account\Controller\Invoice;

/**
 * Invoice History controller
 */
class History extends Invoice implements HttpGetActionInterface
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
        $resultPage = $this->_resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->prepend(__('My Invoice History'));

        /** @var \Magento\Theme\Block\Html\Breadcrumbs $breadcrumbs */
        $breadcrumbs = $resultPage->getLayout()->getBlock('breadcrumbs');
        if ($breadcrumbs) {
            $breadcrumbs->addCrumb('home', [
                'label' => __('Home'),
                'title' => __('Home'),
                'link'  => $this->_url->getUrl('')
            ]);
            $breadcrumbs->addCrumb('my_account', [
                'label' => __('My Account'),
                'title' => __('My Account'),
                'link'  => '/customer/account/'
            ]);
            $breadcrumbs->addCrumb('my_invoice_history', [
                'label' => __('My Invoice History'),
                'title' => __('My Invoice History')
            ]);
        }

        return $resultPage;
    }
}
