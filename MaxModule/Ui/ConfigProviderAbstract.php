<?php

declare(strict_types=1);

namespace Amasty\MaxModule\Ui;

use Magento\Framework\App\Config\ScopeConfigInterface;

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

    protected function getValue($path, $storeId, $scope = 'store')
    {
        return $this->scopeConfig->getValue($this->pathPrefix . $path, $scope, $storeId);
    }
}