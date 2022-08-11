<?php

declare(strict_types=1);

namespace Amasty\MaxModule\Controller\Index;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Checkout\Model\Session;
use Magento\Framework\App\ActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\Response\RedirectInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Message\ManagerInterface;
use Magento\Quote\Model\QuoteRepository;

class Submit implements ActionInterface
{
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
     * @var ManagerInterface
     */
    private $messageManager;

    /**
     * @var QuoteRepository
     */
    private $quoteRepository;

    public function __construct(
        ResultFactory $resultFactory,
        RequestInterface $request,
        RedirectInterface $redirect,
        Session $session,
        ProductRepositoryInterface $productRepository,
        ManagerInterface $messageManager,
        QuoteRepository $quoteRepository
    ) {
        $this->resultFactory = $resultFactory;
        $this->request = $request;
        $this->redirect = $redirect;
        $this->session = $session;
        $this->productRepository = $productRepository;
        $this->messageManager = $messageManager;
        $this->quoteRepository = $quoteRepository;
    }

    public function execute()
    {
        $quote = $this->session->getQuote();

        if(!$quote->getId()) {
            $this->quoteRepository->save($quote);
        }

        try {
            $product = $this->productRepository->get($this->request->getParam('sku'));
            $this->isSimple($product);
            $quote->addProduct($product, $this->request->getParam('qty'));
            $this->quoteRepository->save($quote);

            $this->messageManager->addSuccessMessage(__('Product added to cart.'));
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        }

        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $resultRedirect->setUrl($this->redirect->getRefererUrl());
        return $resultRedirect;
    }

    private function isSimple($product){
        if($product->getTypeId() !== 'simple') {
            throw new \Exception(__('This product is not simple.'));
        }
        return 1;
    }
}