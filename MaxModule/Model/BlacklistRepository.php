<?php

declare(strict_types=1);

namespace Amasty\MaxModule\Model;

use Amasty\MaxModule\Model\BlacklistFactory;
use Amasty\MaxModule\Model\ResourceModel\Blacklist as BlacklistResource;

class BlacklistRepository
{
    public const FIELD_SKU = 'sku';

    /**
     * @var BlacklistResource
     */
    private $blacklistResource;

    /**
     * @var BlacklistFactory
     */
    private $blacklistFactory;

    public function __construct(
        BlacklistResource $blacklistResource,
        BlacklistFactory $blacklistFactory
    ) {
        $this->blacklistResource = $blacklistResource;
        $this->blacklistFactory = $blacklistFactory;
    }

    public function getById(int $blacklistId): Blacklist
    {
        $blacklist = $this->blacklistFactory->create();

        $this->blacklistResource->load($blacklist, $blacklistId);

        return $blacklist;
    }

    public function get(string $blacklistSku): Blacklist
    {
        $blacklist = $this->blacklistFactory->create();

        $this->blacklistResource->load($blacklist, $blacklistSku, self::FIELD_SKU);

        return $blacklist;
    }

    public function save(Blacklist $blacklist): Blacklist
    {
        $this->blacklistResource->save($blacklist);

        return $blacklist;
    }

    public function deleteById(int $blacklistId): void
    {
        $blacklist = $this->blacklistFactory->create();

        $this->blacklistResource->load($blacklist, $blacklistId);

        $this->blacklistResource->delete($blacklist);
    }
}