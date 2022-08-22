<?php

declare(strict_types=1);

namespace Amasty\MaxModule\Controller\Index;

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

    public function __construct(
        ResultFactory $resultFactory,
        RequestInterface $request,
        RedirectInterface $redirect,
        Session $session,
        ProductRepositoryInterface $productRepository,
        MessageManagerInterface $messageManager,
        QuoteRepository $quoteRepository,
        EventManagerInterface $eventManager
    ) {
        $this->resultFactory = $resultFactory;
        $this->request = $request;
        $this->redirect = $redirect;
        $this->session = $session;
        $this->productRepository = $productRepository;
        $this->messageManager = $messageManager;
        $this->quoteRepository = $quoteRepository;
        $this->eventManager = $eventManager;
    }

    public function execute()
    {
        $quote = $this->session->getQuote();

        if (!$quote->getId()) {
            $this->quoteRepository->save($quote);
        }

        try {
            $product = $this->productRepository->get($this->request->getParam(self::SKU_PARAM));
            $this->checkIsSimple($product);
            $quote->addProduct($product, $this->request->getParam(self::QTY_PARAM));
            $this->quoteRepository->save($quote);

            $this->messageManager->addSuccessMessage(__('Product added to cart.'));

            $this->eventManager->dispatch(
                self::EVENT_NAME,
                [
                    'sku' => $this->request->getParam(self::SKU_PARAM)
                ]
            );
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        }

        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $resultRedirect->setUrl($this->redirect->getRefererUrl());

        return $resultRedirect;
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