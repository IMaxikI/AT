<?php

declare(strict_types=1);

namespace Amasty\MaxModule\Block;

use Amasty\MaxModule\Model\ConfigProvider;
use Magento\Framework\View\Element\Template;
use Magento\Store\Model\StoreManagerInterface;

class Form extends Template
{
    const CONTROLLER_PATH = 'maxmodule/index/submit';

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

    public function isShowQtyField(): string
    {
        return $this->configProvider->getIsShowQtyField($this->storeManager->getStore()->getId());
    }

    public function getQtyDefaultValue(): string
    {
        return $this->configProvider->getQtyDefaultValue($this->storeManager->getStore()->getId());
    }

    public function getFormAction(): string
    {
        return $this->getUrl(self::CONTROLLER_PATH, ['_secure' => true]);
    }
}