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
 * Open Invoice controller
 */
class Open extends Invoice implements HttpGetActionInterface
{
    /**
     * Execute 'Open' action based on request and return result
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        if (!$this->isAllowed()) {
            return $this->getLoginRedirect();
        }

        /** @var \Magento\Framework\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->prepend(__('My Open Invoices'));

        /** @var \Magento\Theme\Block\Html\Breadcrumbs $breadcrumbs */
        if ($breadcrumbs = $resultPage->getLayout()->getBlock('breadcrumbs')) {
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
            $breadcrumbs->addCrumb('my_open_invoices', [
                'label' => __('My Open Invoices'),
                'title' => __('My Open Invoices')
            ]);
        }

        return $resultPage;
    }
}
