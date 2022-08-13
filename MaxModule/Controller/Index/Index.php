<?php

declare(strict_types=1);

namespace Amasty\MaxModule\Controller\Index;

use Amasty\MaxModule\Model\ConfigProvider;
use Magento\Framework\App\ActionInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Store\Model\StoreManagerInterface;

class Index implements ActionInterface
{
    /**
     * @var ResultFactory
     */
    private $resultFactory;

    /**
     * @var ConfigProvider
     */
    private $configProvider;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    public function __construct(
        ResultFactory $resultFactory,
        ConfigProvider $configProvider,
        StoreManagerInterface $storeManager
    ) {
        $this->resultFactory = $resultFactory;
        $this->configProvider = $configProvider;
        $this->storeManager = $storeManager;
    }

    public function execute()
    {
        if ($this->configProvider->getIsEnabled($this->storeManager->getStore()->getId())) {
            return $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        } else {
            die('Module is disabled');
        }
    }
}