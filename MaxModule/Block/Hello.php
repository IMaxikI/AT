<?php

declare(strict_types=1);

namespace Amasty\MaxModule\Block;

use Amasty\MaxModule\Ui\ConfigProvider;
use Magento\Framework\View\Element\Template;
use Magento\Store\Model\StoreManagerInterface;

class Hello extends Template
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
    )
    {
        parent::__construct($context, $data);
        $this->configProvider = $configProvider;
        $this->storeManager = $storeManager;
    }

    public function getWelcomeText()
    {
        return $this->configProvider->getWelcomeText($this->getStoreId());
    }

    private function getStoreId()
    {
        return $this->storeManager->getStore()->getId();
    }
}