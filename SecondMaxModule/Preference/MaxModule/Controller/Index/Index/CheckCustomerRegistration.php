<?php

declare(strict_types=1);

namespace Amasty\SecondMaxModule\Preference\MaxModule\Controller\Index\Index;

use Amasty\MaxModule\Controller\Index\Index;
use Amasty\MaxModule\Model\ConfigProvider;
use Magento\Customer\Model\Session;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Webapi\Exception;
use Magento\Store\Model\StoreManagerInterface;

class CheckCustomerRegistration extends Index
{
    /**
     * @var Session
     */
    private $session;

    /**
     * @var ResultFactory
     */
    private $resultFactory;

    public function __construct(
        ResultFactory $resultFactory,
        ConfigProvider $configProvider,
        StoreManagerInterface $storeManager,
        Session $session
    ) {
        parent::__construct($resultFactory, $configProvider, $storeManager);
        $this->session = $session;
        $this->resultFactory = $resultFactory;
    }

    public function execute()
    {
        if($this->session->isLoggedIn()) {
            return parent::execute();
        } else {
            $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_FORWARD);
            $resultRedirect->setHttpResponseCode(Exception::HTTP_NOT_FOUND);

            return $resultRedirect;
        }
    }
}