<?php
/**
 * Copyright (C) EC Brands Corporation - All Rights Reserved
 * Contact Licensing@ECInternet.com for use guidelines
 */
declare(strict_types=1);

namespace ECInternet\Sage300Account\Controller\Reorder;

use Magento\Framework\App\Action\HttpGetActionInterface;
use ECInternet\Sage300Account\Controller\Reorder;

/**
 * Reorder ReorderList controller
 */
class Index extends Reorder implements HttpGetActionInterface
{
    /**
     * Execute 'Index' action based on request and return result
     *
     * @return \Magento\Framework\Controller\ResultInterface|\Magento\Framework\App\ResponseInterface
     */
    public function execute()
    {
        if (!$this->isAllowed()) {
            return $this->getLoginRedirect();
        }

        $customer = $this->customerSession->getCustomer();
        if (!$customer) {
            $this->messageManager->addErrorMessage(
                __('You must be logged in to view this page.')
            );

            return $this->resultRedirectFactory->create()->setPath('customer/account/login');
        }

        $customerNumber = $customer->getData('customer_number');
        if (empty($customerNumber)) {
            $this->messageManager->addErrorMessage(
                __("Customer does not have 'customer_number' attribute set.")
            );

            return $this->resultRedirectFactory->create()->setPath('customer/account/index');
        }

        /** @var \Magento\Framework\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->prepend(__('Reorder Custom Products'));

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
            $breadcrumbs->addCrumb('my_invoice_history', [
                'label' => __('My Invoice History'),
                'title' => __('My Invoice History')
            ]);
        }

        return $resultPage;
    }
}
