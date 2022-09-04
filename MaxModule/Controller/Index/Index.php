<?php

declare(strict_types=1);

namespace Amasty\MaxModule\Controller\Index;

use Amasty\MaxModule\Model\ConfigProvider;
use Magento\Framework\App\ActionInterface;
use Magento\Framework\App\Response\RedirectInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\UrlInterface;
use Magento\Framework\Webapi\Exception;
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

    /**
     * @var RedirectInterface
     */
    private $redirect;

    /**
     * @var UrlInterface
     */
    private $url;

    public function __construct(
        ResultFactory $resultFactory,
        ConfigProvider $configProvider,
        StoreManagerInterface $storeManager,
        RedirectInterface $redirect,
        UrlInterface $url
    ) {
        $this->resultFactory = $resultFactory;
        $this->configProvider = $configProvider;
        $this->storeManager = $storeManager;
        $this->redirect = $redirect;
        $this->url = $url;
    }

    public function execute()
    {
        if ($this->configProvider->getIsEnabled((int)$this->storeManager->getStore()->getId())) {
            return $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        } else {
            $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_FORWARD);
            $resultRedirect->setHttpResponseCode(Exception::HTTP_NOT_FOUND);

            return $resultRedirect;
        }
    }
}