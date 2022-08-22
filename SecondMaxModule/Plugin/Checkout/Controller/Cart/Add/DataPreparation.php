<?php

declare(strict_types=1);

namespace Amasty\SecondMaxModule\Plugin\Checkout\Controller\Cart\Add;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Checkout\Controller\Cart\Add;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Message\ManagerInterface;

class DataPreparation
{
    public const SKU_PARAM = 'sku';

    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * @var ManagerInterface
     */
    private $messageManager;

    public function __construct(
        ProductRepositoryInterface $productRepository,
        ManagerInterface $messageManager
    ) {
        $this->productRepository = $productRepository;
        $this->messageManager = $messageManager;
    }

    public function beforeExecute(Add $subject): void
    {
        try {
            $product = $this->productRepository->get(
                $subject->getRequest()->getParam(self::SKU_PARAM)
            );

            $subject->getRequest()->setParams(['product' => $product->getId()]);
        } catch (NoSuchEntityException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        }
    }
}