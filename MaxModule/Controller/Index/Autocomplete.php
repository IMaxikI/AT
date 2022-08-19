<?php

declare(strict_types=1);

namespace Amasty\MaxModule\Controller\Index;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Framework\App\ActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\ResultFactory;

class Autocomplete implements ActionInterface
{
    const SEARCH_PARAM = 'searchValue';

    /**
     * @var ResultFactory
     */
    private $resultFactory;

    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * @var CollectionFactory
     */
    private $collectionFactory;

    public function __construct(
        ResultFactory $resultFactory,
        RequestInterface $request,
        CollectionFactory $collectionFactory
    ) {
        $this->resultFactory = $resultFactory;
        $this->request = $request;
        $this->collectionFactory = $collectionFactory;
    }

    public function execute()
    {
        $productCollection = $this->collectionFactory->create();
        $productCollection->addAttributeToFilter(ProductInterface::SKU, ['like' => '%' . $this->request->getParam(self::SEARCH_PARAM) . '%']);
        $productCollection->addAttributeToSelect(ProductInterface::NAME, true);

        $resultJson = $this->resultFactory->create(ResultFactory::TYPE_JSON);
        $resultJson->setData($productCollection->setPageSize(15)->setCurPage(1)->getData());

        return $resultJson;
    }
}