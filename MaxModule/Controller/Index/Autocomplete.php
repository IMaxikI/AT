<?php

declare(strict_types=1);

namespace Amasty\MaxModule\Controller\Index;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory as ProductCollectionFactory;
use Magento\Framework\App\ActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\ResultFactory;

class Autocomplete implements ActionInterface
{
    public const SEARCH_PARAM = 'searchValue';

    public const PAGE_SIZE = 15;

    /**
     * @var ResultFactory
     */
    private $resultFactory;

    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * @var ProductCollectionFactory
     */
    private $productCollectionFactory;

    public function __construct(
        ResultFactory $resultFactory,
        RequestInterface $request,
        ProductCollectionFactory $productCollectionFactory
    ) {
        $this->resultFactory = $resultFactory;
        $this->request = $request;
        $this->productCollectionFactory = $productCollectionFactory;
    }

    public function execute()
    {
        $productCollection = $this->productCollectionFactory->create();
        $productCollection->addAttributeToFilter(
            ProductInterface::SKU,
            ['like' => '%' . $this->request->getParam(self::SEARCH_PARAM) . '%']
        );
        $productCollection->addAttributeToSelect(ProductInterface::NAME, true);

        $resultJson = $this->resultFactory->create(ResultFactory::TYPE_JSON);
        $resultJson->setData(
            $productCollection->setPageSize(self::PAGE_SIZE)->setCurPage(1)->getData()
        );

        return $resultJson;
    }
}