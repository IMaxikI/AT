<?php

declare(strict_types=1);

namespace Amasty\MaxModule\Model;

class ConfigProvider extends ConfigProviderAbstract
{
    const MODULE_ENABLED = 'general/module_enabled';

    const WELCOME_TEXT = 'general/welcome_text';

    const QTY_FIELD_ENABLED = 'general/qty_field_enabled';

    const QTY_DEFAULT_VALUE = 'general/qty_default_value';

    /**
     * @var string
     */
    protected $pathPrefix = 'maxmodule_config/';

    public function getIsEnabled(string $storeId): string
    {
        return $this->getValue(self::MODULE_ENABLED, $storeId);
    }

    public function getWelcomeText(string $storeId): string
    {
        return $this->getValue(self::WELCOME_TEXT, $storeId);
    }

    public function getIsShowQtyField(string $storeId): string
    {
        return $this->getValue(self::QTY_FIELD_ENABLED, $storeId);
    }

    public function getQtyDefaultValue(string $storeId): string
    {
        return $this->getValue(self::QTY_DEFAULT_VALUE, $storeId);
    }
}