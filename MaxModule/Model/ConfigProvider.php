<?php

declare(strict_types=1);

namespace Amasty\MaxModule\Model;

class ConfigProvider extends ConfigProviderAbstract
{
    /**
     * @var string
     */
    protected $pathPrefix = 'maxmodule_config/';

    public function getIsEnabled($storeId)
    {
        return $this->getValue('general/module_enabled', $storeId);
    }

    public function getWelcomeText($storeId)
    {
        return $this->getValue('general/welcome_text', $storeId);
    }

    public function getIsShowQtyField($storeId)
    {
        return $this->getValue('general/qty_field_enabled', $storeId);
    }

    public function getQtyDefaultValue($storeId)
    {
        return $this->getValue('general/qty_default_value',$storeId);
    }
}