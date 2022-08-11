<?php

declare(strict_types=1);

namespace Amasty\MaxModule\Block;

use Amasty\MaxModule\Model\ConfigProvider;
use Magento\Framework\View\Element\Template;
use Magento\Store\Model\StoreManagerInterface;

class Form extends Template
{
    /**
     * @var ConfigProvider
     */
    private $configProvider;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    public function __construct(
        Template\Context $context,
        ConfigProvider $configProvider,
        StoreManagerInterface $storeManager,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->configProvider = $configProvider;
        $this->storeManager = $storeManager;
    }

    public function isShowQtyField(){
        return $this->configProvider->getIsShowQtyField($this->getStoreId());
    }

    public function getQtyDefaultValue(){
        return $this->configProvider->getQtyDefaultValue($this->getStoreId());
    }

    public function getFormAction()
    {
        return $this->getUrl('maxmodule/index/submit', ['_secure' => true]);
    }

    private function getStoreId()
    {
        return $this->storeManager->getStore()->getId();
    }
}