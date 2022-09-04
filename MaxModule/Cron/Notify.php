<?php

declare(strict_types=1);

namespace Amasty\MaxModule\Cron;

use Amasty\MaxModule\Model\BlacklistRepository;
use Amasty\MaxModule\Model\ConfigProvider;
use Amasty\MaxModule\Model\ResourceModel\Blacklist\CollectionFactory as BlacklistCollectionFactory;
use Magento\Framework\App\Area;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Store\Model\StoreManagerInterface;

class Notify
{
    public const SENDER_NAME = 'Admin';

    public const SENDER_EMAIL = 'adminName@gmail.com';

    /**
     * @var TransportBuilder
     */
    private $transportBuilder;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var ConfigProvider
     */
    private $configProvider;

    /**
     * @var BlacklistCollectionFactory
     */
    private $blacklistCollectionFactory;

    /**
     * @var BlacklistRepository
     */
    private $blacklistRepository;

    public function __construct(
        TransportBuilder $transportBuilder,
        StoreManagerInterface $storeManager,
        ConfigProvider $configProvider,
        BlacklistCollectionFactory $blacklistCollectionFactory,
        BlacklistRepository $blacklistRepository
    ) {
        $this->transportBuilder = $transportBuilder;
        $this->storeManager = $storeManager;
        $this->configProvider = $configProvider;
        $this->blacklistCollectionFactory = $blacklistCollectionFactory;
        $this->blacklistRepository = $blacklistRepository;
    }

    public function execute(): void
    {
        /** @var \Amasty\MaxModule\Model\ResourceModel\Blacklist\Collection $blacklistCollection */
        $blacklistCollection = $this->blacklistCollectionFactory->create();

        if ($blacklistCollection->getSize() === 0) {
            return;
        }

        $blacklistItem = $blacklistCollection->getFirstItem();

        $storeId = (int)$this->storeManager->getStore()->getId();

        $vars = [
            'sku' => $blacklistItem->getSku(),
            'qty' => $blacklistItem->getQty(),
        ];

        $sender = [
            'name' => self::SENDER_NAME,
            'email'=> self::SENDER_EMAIL
        ];

        $this->transportBuilder->setTemplateIdentifier(
            $this->configProvider->getEmailTemplate($storeId)
        )->setTemplateOptions(
            [
                'area' => Area::AREA_FRONTEND,
                'store' => $storeId
            ]
        )->setTemplateVars(
            $vars
        )->setFromByScope(
            $sender
        )->addTo(
            $this->configProvider->getEmail($storeId)
        );

        $transport = $this->transportBuilder->getTransport();
        $transport->sendMessage();

        $blacklistItem->setEmailBody($transport->getMessage()->getBodyText());

        $this->blacklistRepository->save($blacklistItem);
    }
}