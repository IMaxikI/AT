<?php

declare(strict_types=1);

namespace Amasty\MaxModule\Controller\Index;

use Magento\Framework\App\ActionInterface;

class Index implements ActionInterface
{
    public function execute()
    {
        die('Привет Magento. Привет Amasty. Я готов тебя покорить!');
    }
}