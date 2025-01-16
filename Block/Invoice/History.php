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
use Magento\Theme\Block\Html\Pager;
use ECInternet\Sage300Account\Model\Config;
use ECInternet\Sage300Account\Model\Data\Oeinvh;
use ECInternet\Sage300Account\Model\ResourceModel\Oeinvh\CollectionFactory as OeinvhCollectionFactory;

/**
 * Invoice History Block
 *
 * @SuppressWarnings(PHPMD.LongVariable)
 */
class History extends Template
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
     * @var \ECInternet\Sage300Account\Model\Config
     */
    private $config;

    /**
     * @var \ECInternet\Sage300Account\Model\ResourceModel\Oeinvh\CollectionFactory
     */
    private $oeinvhCollectionFactory;

    /**
     * History constructor.
     *
     * @param \Magento\Framework\View\Element\Template\Context                        $context
     * @param \Magento\Customer\Model\Session                                         $customerSession
     * @param \Magento\Framework\Pricing\PriceCurrencyInterface                       $priceCurrency
     * @param \ECInternet\Sage300Account\Model\Config                                 $config
     * @param \ECInternet\Sage300Account\Model\ResourceModel\Oeinvh\CollectionFactory $oeinvhCollectionFactory
     * @param array                                                                   $data
     */
    public function __construct(
        Context $context,
        CustomerSession $customerSession,
        PriceCurrencyInterface $priceCurrency,
        Config $config,
        OeinvhCollectionFactory $oeinvhCollectionFactory,
        array $data = []
    ) {
        $this->customerSession         = $customerSession;
        $this->priceCurrency           = $priceCurrency;
        $this->config                  = $config;
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

        if ($this->getInvoices()) {
            /** @var \Magento\Theme\Block\Html\Pager $pager */
            $pager = $this->getLayout()->createBlock(
                Pager::class,
                'accounting.invoice.history.pager'
            );

            $pager
                ->setAvailableLimit([10 => 10, 20 => 20])
                ->setShowPerPage(true)
                ->setCollection($this->getInvoices());

            $this->setChild('pager', $pager);
            $this->getInvoices()->load();
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
     * Get Oeinvh Collection for current Customer
     *
     * @return \ECInternet\Sage300Account\Model\ResourceModel\Oeinvh\Collection|bool
     */
    public function getInvoices()
    {
        if ($this->config->isModuleEnabled()) {
            if ($this->customerSession->isLoggedIn()) {
                /** @var \Magento\Customer\Model\Customer $customer */
                $customer = $this->customerSession->getCustomer();

                if ($customerNumber = $customer->getData('customer_number')) {
                    // Get value of current page
                    $page = $this->getRequest()->getParam('p')
                        ? $this->getRequest()->getParam('p')
                        : 1;

                    // Get value of current limit
                    $pageSize = $this->getRequest()->getParam('limit')
                        ? $this->getRequest()->getParam('limit')
                        : 10;

                    return $this->oeinvhCollectionFactory->create()
                        ->addFieldToFilter(Oeinvh::COLUMN_CUSTOMER, ['eq' => $customerNumber])
                        ->setOrder(Oeinvh::COLUMN_ORDDATE, 'DESC')
                        ->setPageSize($pageSize)
                        ->setCurPage($page);
                }
            }
        }

        return false;
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
     * Get the formatted InvoiceTotalWithTax
     *
     * @param \ECInternet\Sage300Account\Model\Data\Oeinvh $invoice
     *
     * @return string
     */
    public function getInvoiceTotalWithTaxHtml(
        Oeinvh $invoice
    ) {
        return $this->format($invoice->getInvoiceTotalWithTax());
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
        return $this->priceCurrency->convertAndFormat($value);
    }
}
