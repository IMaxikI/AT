<?php

declare(strict_types=1);

namespace Amasty\SecondMaxModule\Plugin\MaxModule\Block\Form;

use Amasty\MaxModule\Block\Form;

class ChangeFormAction
{
    public const FORM_ACTION = 'checkout/cart/add';

    public function afterGetFormAction(Form $subject, string $result): string
    {
        return $subject->getUrl(self::FORM_ACTION);
    }
}