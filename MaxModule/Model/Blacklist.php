<?php

declare(strict_types=1);

namespace Amasty\MaxModule\Model;

use Amasty\MaxModule\Model\ResourceModel\Blacklist as BlacklistResource;
use Magento\Framework\Model\AbstractModel;

class Blacklist extends AbstractModel
{
    protected function _construct()
    {
        $this->_init(BlacklistResource::class);
    }
}