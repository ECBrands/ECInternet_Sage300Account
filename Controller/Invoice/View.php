<?php
/**
 * Copyright (C) EC Brands Corporation - All Rights Reserved
 * Contact Licensing@ECInternet.com for use guidelines
 */
declare(strict_types=1);

namespace ECInternet\Sage300Account\Controller\Invoice;

use Magento\Framework\App\Action\HttpGetActionInterface;
use ECInternet\Sage300Account\Controller\Invoice;
use ECInternet\Sage300Account\Model\Data\Oeinvh;

/**
 * Invoice View controller
 */
class View extends Invoice implements HttpGetActionInterface
{
    /**
     * Execute 'View' action based on request and return result
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        if (!$this->isAllowed()) {
            return $this->getLoginRedirect();
        }

        /** @var \Magento\Framework\Controller\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();

        /** @var \Magento\Customer\Model\Customer $customer */
        $customer = $this->customerSession->getCustomer();
        if (!$customer) {
            $this->messageManager->addErrorMessage(
                __('You must be logged in to view this page')
            );

            return $resultRedirect->setPath('customer/account/login');
        }

        // TODO: $customerNumber should be validated against value against Invoice
        $customerNumber = $customer->getData('customer_number');
        if (empty($customerNumber)) {
            // Redirect to Customer Account page and show error message
            $this->messageManager->addErrorMessage(
                __("Customer does not have 'customer_number' attribute set.")
            );

            return $resultRedirect->setPath('accounting/invoice/history');
        }

        $invoiceId = $this->getRequest()->getParam('id');
        if (!$invoiceId) {
            // Redirect to Invoice History page and show error message
            $this->messageManager->addErrorMessage(
                __('Invalid URL. Could not determine Invoice.')
            );

            return $resultRedirect->setPath('accounting/invoice/history');
        }

        /** @var \ECInternet\Sage300Account\Model\ResourceModel\Oeinvh\Collection $invoiceCollection */
        $invoiceCollection = $this->oeinvhCollectionFactory->create()
            ->addFieldToFilter(Oeinvh::COLUMN_ID, ['eq' => $invoiceId]);

        if (!$invoiceCollection->getSize()) {
            // Redirect to Open Invoices page and show error message
            $this->messageManager->addErrorMessage(
                __('No invoices found with this ID')
            );

            return $resultRedirect->setPath('accounting/invoice/history');
        }

        // Get first invoice.
        /** @var \ECInternet\Sage300Account\Model\Data\Oeinvh $invoice */
        $invoice       = $invoiceCollection->getFirstItem();
        $invoiceNumber = $invoice->getInvoiceNumber();

        /** @var \Magento\Framework\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->prepend(__("Invoice #$invoiceNumber"));

        /** @var \ECInternet\Sage300Account\Block\Invoice\View $viewBlock */
        if ($viewBlock = $resultPage->getLayout()->getBlock('sage300account-accounting-invoice-view')) {
            $viewBlock->setData('invoice', $invoice);

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

                if ($invoice->isFullyPaid()) {
                    $breadcrumbs->addCrumb('my_invoices', [
                        'label' => __('My Invoices'),
                        'title' => __('My Invoices'),
                        'link'  => '/accounting/invoice/history/'
                    ]);
                } else {
                    $breadcrumbs->addCrumb('my_open_invoices', [
                        'label' => __('Invoices'),
                        'title' => __('Invoices'),
                        'link'  => '/accounting/invoice/open/'
                    ]);
                }

                $breadcrumbs->addCrumb('invoice', [
                    'label' => __("Invoice #$invoiceNumber"),
                    'title' => __("Invoice #$invoiceNumber")
                ]);
            }
        }

        return $resultPage;
    }
}
