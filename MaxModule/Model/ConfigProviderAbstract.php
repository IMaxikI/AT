<?php

declare(strict_types=1);

namespace Amasty\MaxModule\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

abstract class ConfigProviderAbstract
{
    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var string
     */
    protected $pathPrefix;

    public function __construct(ScopeConfigInterface $scopeConfig)
    {
        $this->scopeConfig = $scopeConfig;
    }

    protected function getValue(
        string $path,
        int $storeId,
        string $scope = ScopeInterface::SCOPE_STORE
    ): string
    {
        return $this->scopeConfig->getValue($this->pathPrefix . $path, $scope, $storeId);
    }
}