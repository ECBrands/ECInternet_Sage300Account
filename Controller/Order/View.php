<?php
/**
 * Copyright (C) EC Brands Corporation - All Rights Reserved
 * Contact Licensing@ECInternet.com for use guidelines
 */
declare(strict_types=1);

namespace ECInternet\Sage300Account\Controller\Order;

use Magento\Framework\App\Action\HttpGetActionInterface;
use ECInternet\Sage300Account\Controller\Order;
use ECInternet\Sage300Account\Model\Data\Oeordh;

/**
 * Order View controller
 */
class View extends Order implements HttpGetActionInterface
{
    /**
     * Execute 'View' action based on request and return result
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

        /** @var \Magento\Customer\Model\Customer $customer */
        $customer = $this->customerSession->getCustomer();
        if (!$customer) {
            $this->messageManager->addErrorMessage(
                __('You must be logged in to view this page')
            );

            return $resultRedirect->setPath('customer/account/login');
        }

        // TODO: $customerNumber should be validated against value against Order
        $customerNumber = $customer->getData('customer_number');
        if (empty($customerNumber)) {
            // Redirect to Customer Account page and show error message
            $this->messageManager->addErrorMessage(
                __("Customer does not have 'customer_number' attribute set.")
            );

            return $resultRedirect->setPath('accounting/order/history');
        }

        $orderId = $this->getRequest()->getParam('id');
        if (!$orderId) {
            // Redirect to Order History page and show error message
            $this->messageManager->addErrorMessage(
                __('Invalid URL. Could not determine Order')
            );

            return $resultRedirect->setPath('accounting/order/history');
        }

        /** @var \ECInternet\Sage300Account\Model\ResourceModel\Oeordh\Collection $orderCollection */
        $orderCollection = $this->oeordhCollectionFactory->create()
            ->addFieldToFilter(Oeordh::COLUMN_ID, ['eq' => $orderId]);

        if (!$orderCollection->getSize()) {
            // Redirect to Order History page and show error message
            $this->messageManager->addErrorMessage(
                __('No orders found with this ID')
            );

            return $resultRedirect->setPath('accounting/order/history');
        }

        // Get first order.
        /** @var \ECInternet\Sage300Account\Model\Data\Oeordh $order */
        $order       = $orderCollection->getFirstItem();
        $orderNumber = $order->getOrderNumber();

        /** @var \Magento\Framework\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->prepend(__("Order #$orderNumber"));

        /** @var \ECInternet\Sage300Account\Block\Order\View $viewBlock */
        if ($viewBlock = $resultPage->getLayout()->getBlock('sage300account-accounting-order-view')) {
            $viewBlock->setData('order', $order);

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
                $breadcrumbs->addCrumb('my_orders', [
                    'label' => __('My Orders'),
                    'title' => __('My Orders'),
                    'link'  => '/accounting/order/history/'
                ]);
                $breadcrumbs->addCrumb('order', [
                    'label' => __("Order #$orderNumber"),
                    'title' => __("Order #$orderNumber")
                ]);
            }
        }

        return $resultPage;
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
        $this->logger->info('Controller/Order/View - ' . $message, $extra);
    }
}
