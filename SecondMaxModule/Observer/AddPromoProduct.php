<?php

declare(strict_types=1);

namespace Amasty\SecondMaxModule\Observer;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Checkout\Model\Session;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Quote\Model\QuoteRepository;

class AddPromoProduct implements ObserverInterface
{
    public const FOR_SKU = 'secondmaxmodule_config/general/for_sku';

    public const PROMO_SKU = 'secondmaxmodule_config/general/promo_sku';

    public const QTY_PROMO_PRODUCT = 1;

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var Session
     */
    private $session;

    /**
     * @var QuoteRepository
     */
    private $quoteRepository;

    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    public function __construct(
        ScopeConfigInterface $scopeConfig,
        QuoteRepository $quoteRepository,
        Session $session,
        ProductRepositoryInterface $productRepository
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->quoteRepository = $quoteRepository;
        $this->productRepository = $productRepository;
        $this->session = $session;
    }

    public function execute(Observer $observer)
    {
        $forSku = $this->scopeConfig->getValue(self::FOR_SKU);

        if (strripos($forSku, $observer->getSku()) !== false) {
            $promoProduct = $this->productRepository->get(
                $this->scopeConfig->getValue(self::PROMO_SKU)
            );

            $quote = $this->session->getQuote();

            if (!$quote->getId()) {
                $this->quoteRepository->save($quote);
            }

            $quote->addProduct($promoProduct, self::QTY_PROMO_PRODUCT);
            $this->quoteRepository->save($quote);
        }
    }
}