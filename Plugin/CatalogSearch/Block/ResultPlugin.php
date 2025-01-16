<?php
/**
 * Copyright (C) EC Brands Corporation - All Rights Reserved
 * Contact Licensing@ECInternet.com for use guidelines
 */
declare(strict_types=1);

namespace ECInternet\Sage300Account\Plugin\CatalogSearch\Block;

use Magento\CatalogSearch\Block\Result as SearchResult;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\App\Response\Http as HttpResponse;
use Magento\Framework\UrlInterface;
use ECInternet\Sage300Account\Logger\Logger;
use ECInternet\Sage300Account\Model\Config;
use ECInternet\Sage300Account\Model\Data\Oeshdt;
use ECInternet\Sage300Account\Model\ResourceModel\Oeshdt\CollectionFactory as OeshdtCollection;

/**
 * Plugin for Magento\CatalogSearch\Block\Result
 */
class ResultPlugin
{
    /**
     * @var \Magento\Customer\Model\Session
     */
    private $customerSession;

    /**
     * @var \Magento\Framework\App\Response\Http
     */
    private $response;

    /**
     * @var \Magento\Framework\UrlInterface
     */
    private $url;

    /**
     * @var \ECInternet\Sage300Account\Logger\Logger
     */
    private $logger;

    /**
     * @var \ECInternet\Sage300Account\Model\Config
     */
    private $config;

    /**
     * @var \ECInternet\Sage300Account\Model\ResourceModel\Oeshdt\CollectionFactory
     */
    private $oeshdtCollection;

    /**
     * ResultPlugin constructor.
     *
     * @param \Magento\Customer\Model\Session                                         $customerSession
     * @param \Magento\Framework\App\Response\Http                                    $response
     * @param \Magento\Framework\UrlInterface                                         $url
     * @param \ECInternet\Sage300Account\Logger\Logger                                $logger
     * @param \ECInternet\Sage300Account\Model\Config                                 $config
     * @param \ECInternet\Sage300Account\Model\ResourceModel\Oeshdt\CollectionFactory $oeshdtCollection
     */
    public function __construct(
        CustomerSession $customerSession,
        HttpResponse $response,
        UrlInterface $url,
        Logger $logger,
        Config $config,
        OeshdtCollection $oeshdtCollection
    ) {
        $this->customerSession  = $customerSession;
        $this->response         = $response;
        $this->url              = $url;
        $this->logger           = $logger;
        $this->config           = $config;
        $this->oeshdtCollection = $oeshdtCollection;
    }

    public function afterGetResultCount(
        SearchResult $subject,
        int $result
    ) {
        // Only attempt if search doesn't find exact result
        if ($result !== 1) {
            if ($this->config->isReorderEnabled()) {
                if ($this->shouldRedirectToReorder($subject)) {
                    $this->response->setRedirect($this->url->getUrl('accounting/reorder/index'));
                }
            }
        }

        return $result;
    }

    private function shouldRedirectToReorder(
        SearchResult $subject
    ) {
        $this->log('shouldRedirectToReorder()');

        if ($this->customerSession->isLoggedIn()) {
            if ($customer = $this->customerSession->getCustomer()) {
                if ($customerNumber = $customer->getData('customer_number')) {
                    if ($searchTerm = $this->getSearchTerm($subject)) {
                        return $this->doesCustomerNumberHaveOrderHistoryForSearchTerm(
                            (string)$customerNumber,
                            (string)$searchTerm
                        );
                    } else {
                        $this->log('shouldRedirectToReorder() - Could not get search term.');
                    }
                } else {
                    $this->log('shouldRedirectToReorder() - Could not get customer_number.');
                }
            } else {
                $this->log('shouldRedirectToReorder() - Could not get Customer.');
            }
        } else {
            $this->log('shouldRedirectToReorder() - Customer not logged in.');
        }

        return false;
    }

    /**
     * OESHDT record lookup by customer number and sku
     *
     * @param string $customerNumber
     * @param string $sku
     *
     * @return bool
     */
    private function doesCustomerNumberHaveOrderHistoryForSearchTerm(string $customerNumber, string $sku)
    {
        return $this->oeshdtCollection->create()
            ->addFieldToFilter(Oeshdt::COLUMN_CUSTOMER, $customerNumber)
            ->addFieldToFilter(Oeshdt::COLUMN_ITEM, $sku)
            ->addFieldToFilter(Oeshdt::COLUMN_IS_ACTIVE, 1)
            ->getSize() > 0;
    }

    private function getSearchTerm(
        SearchResult $subject
    ) {
        return $subject->getRequest()->getParam('q');
    }

    /**
     * Write to extension log
     *
     * @param string $message
     *
     * @return void
     */
    private function log(string $message)
    {
        $this->logger->info('Plugin/CatalogSearch/Block/ResultPlugin - ' . $message);
    }
}
