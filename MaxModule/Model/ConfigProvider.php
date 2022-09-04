<?php

declare(strict_types=1);

namespace Amasty\MaxModule\Model;

class ConfigProvider extends ConfigProviderAbstract
{
    public const MODULE_ENABLED = 'general/module_enabled';

    public const WELCOME_TEXT = 'general/welcome_text';

    public const QTY_FIELD_ENABLED = 'general/qty_field_enabled';

    public const QTY_DEFAULT_VALUE = 'general/qty_default_value';

    public const EMAIL = 'email_config/email';

    public const EMAIL_TEMPLATE = 'email_config/email_template';

    /**
     * @var string
     */
    protected $pathPrefix = 'maxmodule_config/';

    public function getIsEnabled(int $storeId): string
    {
        return $this->getValue(self::MODULE_ENABLED, $storeId);
    }

    public function getWelcomeText(int $storeId): string
    {
        return $this->getValue(self::WELCOME_TEXT, $storeId);
    }

    public function getIsShowQtyField(int $storeId): string
    {
        return $this->getValue(self::QTY_FIELD_ENABLED, $storeId);
    }

    public function getQtyDefaultValue(int $storeId): string
    {
        return $this->getValue(self::QTY_DEFAULT_VALUE, $storeId);
    }

    public function getEmail(int $storeId): string
    {
        return $this->getValue(self::EMAIL, $storeId);
    }

    public function getEmailTemplate(int $storeId): string
    {
        return $this->getValue(self::EMAIL_TEMPLATE, $storeId);
    }
}