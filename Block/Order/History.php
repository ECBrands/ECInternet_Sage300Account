<?php
/**
 * Copyright (C) EC Brands Corporation - All Rights Reserved
 * Contact Licensing@ECInternet.com for use guidelines
 */
declare(strict_types=1);

namespace ECInternet\Sage300Account\Block\Order;

use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\View\Element\Template;
use Magento\Theme\Block\Html\Pager;
use ECInternet\Sage300Account\Model\Config;
use ECInternet\Sage300Account\Model\Data\Oeordh;
use ECInternet\Sage300Account\Model\ResourceModel\Oeordh\CollectionFactory as OeordhCollectionFactory;

/**
 * Order History Block
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
     * @var \ECInternet\Sage300Account\Model\ResourceModel\Oeordh\CollectionFactory
     */
    private $oeordhCollectionFactory;

    /**
     * History constructor.
     *
     * @param \Magento\Framework\View\Element\Template\Context                        $context
     * @param \Magento\Customer\Model\Session                                         $customerSession
     * @param \Magento\Framework\Pricing\PriceCurrencyInterface                       $priceCurrency
     * @param \ECInternet\Sage300Account\Model\Config                                 $config
     * @param \ECInternet\Sage300Account\Model\ResourceModel\Oeordh\CollectionFactory $oeordhCollectionFactory
     * @param array                                                                   $data
     */
    public function __construct(
        Context $context,
        CustomerSession $customerSession,
        PriceCurrencyInterface $priceCurrency,
        Config $config,
        OeordhCollectionFactory $oeordhCollectionFactory,
        array $data = []
    ) {
        $this->customerSession         = $customerSession;
        $this->priceCurrency           = $priceCurrency;
        $this->config                  = $config;
        $this->oeordhCollectionFactory = $oeordhCollectionFactory;

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

        if ($this->getOrders()) {
            /** @var \Magento\Theme\Block\Html\Pager $pager */
            $pager = $this->getLayout()->createBlock(
                Pager::class,
                'accounting.order.history.pager'
            );

            $pager
                ->setAvailableLimit([10 => 10, 20 => 20])
                ->setShowPerPage(true)
                ->setCollection($this->getOrders());

            $this->setChild('pager', $pager);
            $this->getOrders()->load();
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
     * Get Oeordh Collection for current Customer
     *
     * @return \ECInternet\Sage300Account\Model\ResourceModel\Oeordh\Collection|bool
     */
    public function getOrders()
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

                    return $this->oeordhCollectionFactory->create()
                        ->addFieldToFilter(Oeordh::COLUMN_CUSTOMER, ['eq' => $customerNumber])
                        ->setOrder(Oeordh::COLUMN_ORDDATE, 'DESC')
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
     * @param \ECInternet\Sage300Account\Model\Data\Oeordh $order
     *
     * @return string
     */
    public function getViewUrl(
        Oeordh $order
    ) {
        return $this->getUrl('accounting/order/view', ['id' => $order->getId()]);
    }

    /**
     * Get URL for Reorder action
     *
     * @param \ECInternet\Sage300Account\Model\Data\Oeordh $order
     *
     * @return string
     */
    public function getReorderUrl(
        Oeordh $order
    ) {
        return $this->getUrl('accounting/order/reorder', ['id' => $order->getId()]);
    }

    /**
     * Get the formatted OrderDate
     *
     * @param \ECInternet\Sage300Account\Model\Data\Oeordh $order
     *
     * @return string
     */
    public function getOrderDateFormatted(
        Oeordh $order
    ) {
        return date('m/d/Y', strtotime($order->getOrderDate()));
    }

    /**
     * Get the formatted OrderTotal
     *
     * @param \ECInternet\Sage300Account\Model\Data\Oeordh $order
     *
     * @return string
     */
    public function getOrderTotalHtml(
        Oeordh $order
    ) {
        return $this->format($order->getOrderTotal());
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
