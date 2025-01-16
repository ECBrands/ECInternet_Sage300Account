<?php
/**
 * Copyright (C) EC Brands Corporation - All Rights Reserved
 * Contact Licensing@ECInternet.com for use guidelines
 */
declare(strict_types=1);

namespace ECInternet\Sage300Account\Block\Invoice;

use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\View\Element\Template;
use Magento\Sales\Api\Data\OrderItemInterface;
use Magento\Sales\Model\ResourceModel\Order\CollectionFactory as OrderCollectionFactory;
use Magento\Theme\Block\Html\Pager;
use ECInternet\Sage300Account\Helper\Data;
use ECInternet\Sage300Account\Model\Data\Oeinvh;
use ECInternet\Sage300Account\Model\ResourceModel\Oeinvh\CollectionFactory as OeinvhCollectionFactory;

/**
 * Open Invoice Block
 *
 * @SuppressWarnings(PHPMD.LongVariable)
 */
class Open extends Template
{
    /**
     * @var \Magento\Customer\Model\Session
     */
    private $customerSession;

    /**
     * @var \Magento\Framework\Pricing\PriceCurrencyInterface
     */
    private $priceCurrency;

    /**
     * @var \Magento\Sales\Model\ResourceModel\Order\CollectionFactory
     */
    private $orderCollectionFactory;

    /**
     * @var \ECInternet\Sage300Account\Helper\Data
     */
    private $helper;

    /**
     * @var \ECInternet\Sage300Account\Model\ResourceModel\Oeinvh\CollectionFactory
     */
    private $oeinvhCollectionFactory;

    /**
     * Open constructor.
     *
     * @param \Magento\Framework\View\Element\Template\Context                        $context
     * @param \Magento\Customer\Model\Session                                         $customerSession
     * @param \Magento\Framework\Pricing\PriceCurrencyInterface                       $priceCurrency
     * @param \Magento\Sales\Model\ResourceModel\Order\CollectionFactory              $orderCollectionFactory
     * @param \ECInternet\Sage300Account\Helper\Data                                  $helper
     * @param \ECInternet\Sage300Account\Model\ResourceModel\Oeinvh\CollectionFactory $oeinvhCollectionFactory
     * @param array                                                                   $data
     */
    public function __construct(
        Context $context,
        CustomerSession $customerSession,
        PriceCurrencyInterface $priceCurrency,
        OrderCollectionFactory $orderCollectionFactory,
        Data $helper,
        OeinvhCollectionFactory $oeinvhCollectionFactory,
        array $data = []
    ) {
        $this->customerSession         = $customerSession;
        $this->priceCurrency           = $priceCurrency;
        $this->orderCollectionFactory  = $orderCollectionFactory;
        $this->helper                  = $helper;
        $this->oeinvhCollectionFactory = $oeinvhCollectionFactory;

        parent::__construct($context, $data);
    }

    /**
     * Prepare global layout
     *
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();

        if ($this->getOpenInvoices()) {
            /** @var \Magento\Theme\Block\Html\Pager $pager */
            $pager = $this->getLayout()->createBlock(
                Pager::class,
                'accounting.invoice.open.pager'
            );

            $pager
                ->setAvailableLimit([10 => 10, 20 => 20])
                ->setShowPerPage(true)
                ->setCollection($this->getOpenInvoices());

            $this->setChild('pager', $pager);
            $this->getOpenInvoices()->load();
        }

        return $this;
    }

    /**
     * Render pagination HTML
     *
     * @return string
     */
    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }

    /**
     * Get unpaid Oeinvh Collection for current Customer
     *
     * @return \ECInternet\Sage300Account\Model\ResourceModel\Oeinvh\Collection|null
     */
    public function getOpenInvoices()
    {
        if ($this->helper->isModuleEnabled()) {
            if ($customer = $this->getCurrentCustomer()) {
                if ($customerNumber = $customer->getData('customer_number')) {
                    $collection = $this->oeinvhCollectionFactory->create();

                    // Filter to Open Invoices using JOIN on AROBL
                    $collection->getSelect()->join(
                        ['arobl' => $collection->getTable('ecinternet_sage300account_arobl')],
                        'main_table.INVNUMBER = arobl.IDINVC',
                        ['arobl.SWPAID']
                    )->where("(arobl.SWPAID = '0') AND main_table.CUSTOMER = '$customerNumber'");

                    // Get value of current page
                    $page = ($this->getRequest()->getParam('p'))
                        ? $this->getRequest()->getParam('p')
                        : 1;

                    // Get value of current limit
                    $pageSize = ($this->getRequest()->getParam('limit'))
                        ? $this->getRequest()->getParam('limit')
                        : 10;

                    return $collection
                        ->setOrder(Oeinvh::COLUMN_ORDDATE, 'DESC')
                        ->setPageSize($pageSize)
                        ->setCurPage($page);
                }
            }
        }

        return null;
    }

    /**
     * Get the Action of the Form
     *
     * @return string
     */
    public function getFormAction()
    {
        return '/accounting/invoice/openPost';
    }

    /**
     * Get URL for View action
     *
     * @param \ECInternet\Sage300Account\Model\Data\Oeinvh $invoice
     *
     * @return string
     */
    public function getViewUrl(
        Oeinvh $invoice
    ) {
        return $this->getUrl('accounting/invoice/view', ['id' => $invoice->getId()]);
    }
    /**
     * Get the formatted InvoiceDate
     *
     * @param \ECInternet\Sage300Account\Model\Data\Oeinvh $invoice
     *
     * @return string
     */
    public function getInvoiceDateFormatted(
        Oeinvh $invoice
    ) {
        if ($invoiceDate = $invoice->getInvoiceDate()) {
            return date('m/d/Y', strtotime((string)$invoiceDate));
        }

        return '';
    }

    /**
     * Get the formatted TotalDue
     *
     * @param \ECInternet\Sage300Account\Model\Data\Oeinvh $invoice
     *
     * @return string
     */
    public function getTotalDueHtml(
        Oeinvh $invoice
    ) {
        return $this->format($invoice->getTotalDue());
    }

    /**
     * Get the formatted Total
     *
     * @return string
     */
    public function getTotalHtml()
    {
        return $this->format($this->getTotal());
    }

    /**
     * Are InvoicePayments enabled in admin settings?
     *
     * @return bool
     */
    public function allowInvoicePayments()
    {
        return $this->helper->areInvoicePaymentsAllowed();
    }

    /**
     * Is payment restricted for this Invoice?
     *
     * @param string $invoiceNumber
     *
     * @return bool
     */
    public function isInvoicePaymentAllowed(string $invoiceNumber)
    {
        $this->_logger->info('isInvoicePaymentAllowed()', ['invoiceNumber' => $invoiceNumber]);

        // Cache customer email
        $customerEmail = $this->getCurrentCustomerEmail();

        // We can't confirm payment is allowed without being able to look up invoice orders
        if (empty($customerEmail)) {
            return false;
        }

        // Check to see if we have a non-completed invoice payment for this invoice.
        // We look at previous orders to see if we have a matching invoice payment product option.
        $invoiceOrders = $this->getInvoicePaymentOrdersByCustomerEmail($customerEmail);
        $this->_logger->info('isInvoicePaymentAllowed()', [
            'email'         => $customerEmail,
            'invoiceOrders' => $invoiceOrders->getSize()
        ]);

        /** @var \Magento\Sales\Model\Order $invoiceOrder */
        foreach ($invoiceOrders as $invoiceOrder) {
            /** @var \Magento\Sales\Api\Data\OrderItemInterface[] $orderItems */
            $orderItems = $invoiceOrder->getItems();
            foreach ($orderItems as $orderItem) {
                $invoicePaymentProductOption = $this->getInvoicePaymentProductOption($orderItem);
                if ($invoicePaymentProductOption !== null) {
                    if ($invoicePaymentProductOption == $invoiceNumber) {
                        return false;
                    }
                }
            }
        }

        return true;
    }

    /**
     * Get the total TotalDue sum
     *
     * @return float
     */
    private function getTotal()
    {
        $total = 0.0;

        /** @var \ECInternet\Sage300Account\Model\ResourceModel\Oeinvh\Collection $openInvoices */
        $openInvoices = $this->getOpenInvoices();
        if ($openInvoices !== null) {
            return $openInvoices->getTotal();
        }

        return $total;
    }

    /**
     * Get the 'email' value for the current Customer
     *
     * @return string|null
     */
    private function getCurrentCustomerEmail()
    {
        if ($customer = $this->getCurrentCustomer()) {
            return $customer->getEmail();
        }

        return null;
    }

    /**
     * Get the current Customer from CustomerSession
     *
     * @return \Magento\Customer\Model\Customer|null
     */
    private function getCurrentCustomer()
    {
        if ($this->customerSession->isLoggedIn()) {
            return $this->customerSession->getCustomer();
        }

        return null;
    }

    /**
     * @param string $customerEmail
     *
     * @return \Magento\Sales\Model\ResourceModel\Order\Collection
     */
    private function getInvoicePaymentOrdersByCustomerEmail(
        string $customerEmail
    ) {
        return $this->orderCollectionFactory->create()
            ->addAttributeToSelect('*')
            ->addFieldToFilter('status', Data::INVOICE_PAYMENT_ORDER_STATUS)
            ->addFieldToFilter('customer_email', $customerEmail);
    }

    /**
     * Get the 'invoice_payment' value from AdditionalOptions
     *
     * @param \Magento\Sales\Api\Data\OrderItemInterface $orderItem
     *
     * @return mixed|null
     */
    private function getInvoicePaymentProductOption(
        OrderItemInterface $orderItem
    ) {
        /** @var array $productOptions */
        $productOptions = $orderItem->getProductOptions();

        if (isset($productOptions['additional_options']['invoice_payment']['value'])) {
            return $productOptions['additional_options']['invoice_payment']['value'];
        }

        return null;
    }

    /**
     * Convert and format price value
     *
     * @param float $value
     *
     * @return string
     */
    private function format(float $value)
    {
        return $this->priceCurrency->convertAndFormat($value, false);
    }
}
