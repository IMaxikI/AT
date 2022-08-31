<?php

declare(strict_types=1);

namespace Amasty\MaxModule\Controller\Index;

use Amasty\MaxModule\Model\BlacklistRepository;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\Product\Type;
use Magento\Checkout\Model\Session;
use Magento\Framework\App\ActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\Response\RedirectInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Event\ManagerInterface as EventManagerInterface;
use Magento\Framework\Message\ManagerInterface as MessageManagerInterface;
use Magento\Quote\Model\Quote;
use Magento\Quote\Model\QuoteRepository;

class Submit implements ActionInterface
{
    public const SKU_PARAM = 'sku';

    public const QTY_PARAM = 'qty';

    public const EVENT_NAME = 'amasty_product_added';

    /**
     * @var ResultFactory
     */
    private $resultFactory;

    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * @var RedirectInterface
     */
    private $redirect;

    /**
     * @var Session
     */
    private $session;

    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * @var MessageManagerInterface
     */
    private $messageManager;

    /**
     * @var QuoteRepository
     */
    private $quoteRepository;

    /**
     * @var EventManagerInterface
     */
    private $eventManager;

    /**
     * @var BlacklistRepository
     */
    private $blacklistRepository;

    public function __construct(
        ResultFactory $resultFactory,
        RequestInterface $request,
        RedirectInterface $redirect,
        Session $session,
        ProductRepositoryInterface $productRepository,
        MessageManagerInterface $messageManager,
        QuoteRepository $quoteRepository,
        EventManagerInterface $eventManager,
        BlacklistRepository $blacklistRepository
    ) {
        $this->resultFactory = $resultFactory;
        $this->request = $request;
        $this->redirect = $redirect;
        $this->session = $session;
        $this->productRepository = $productRepository;
        $this->messageManager = $messageManager;
        $this->quoteRepository = $quoteRepository;
        $this->eventManager = $eventManager;
        $this->blacklistRepository = $blacklistRepository;
    }

    public function execute()
    {
        $quote = $this->session->getQuote();

        if (!$quote->getId()) {
            $this->quoteRepository->save($quote);
        }

        try {
            $sku = $this->request->getParam(self::SKU_PARAM);

            $product = $this->productRepository->get($sku);

            $this->checkIsSimple($product);
            $this->addInQuote($quote, $product);

            $this->eventManager->dispatch(
                self::EVENT_NAME,
                [
                    'sku' => $sku
                ]
            );
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        }

        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $resultRedirect->setUrl($this->redirect->getRefererUrl());

        return $resultRedirect;
    }

    private function addInQuote(Quote $quote, ProductInterface $product): void
    {
        $blacklistProduct = $this->blacklistRepository->get($product->getSku());

        $requestQty = (int)$this->request->getParam(self::QTY_PARAM);

        if (!$blacklistProduct->isEmpty()) {
            $blacklistQty = $blacklistProduct->getQty();

            $quoteItems = $quote->getItems();
            $qtyInCart = 0;

            foreach ($quoteItems as $item) {
                if ($item->getSku() === $product->getSku()) {
                    $qtyInCart = $item->getQty();
                    break;
                }
            }

            $allowedQty = $blacklistQty - $qtyInCart;

            if($allowedQty <= 0) {
                $this->messageManager->addErrorMessage(
                    __('The product has not been added')
                );

                return;
            }

            if ($allowedQty >= $requestQty) {
                $this->successAddProduct($quote, $product, $requestQty);
            } else {
                $quote->addProduct($product, $allowedQty);

                $this->messageManager->addErrorMessage(
                    __('The Product added in quantity ' . $allowedQty . '.')
                );
            }

        } else {
            $this->successAddProduct($quote, $product, $requestQty);
        }

        $this->quoteRepository->save($quote);
    }

    private function successAddProduct(Quote $quote, ProductInterface $product, int $qty): void
    {
        $quote->addProduct($product, $qty);

        $this->messageManager->addSuccessMessage(
            __('Product ' . $product->getName() . ' added to cart.')
        );
    }

    /**
     * @throws \Exception
     */
    private function checkIsSimple(ProductInterface $product): void
    {
        if ($product->getTypeId() !== Type::TYPE_SIMPLE) {
            throw new \Exception(__('This product is not simple.'));
        }
    }
}