<?php

declare(strict_types=1);

namespace Amasty\MaxModule\Plugin\SecondMaxModule\Observer\AddPromoProduct;

use Amasty\SecondMaxModule\Observer\AddPromoProduct;
use Magento\Framework\App\RequestInterface;

class CheckAjax
{
    /**
     * @var RequestInterface
     */
    private $request;

    public function __construct(RequestInterface $request)
    {
        $this->request = $request;
    }

    public function aroundExecute(AddPromoProduct $subject, callable $proceed, ...$args): void
    {
        if (!$this->request->isAjax()) {
            $proceed(...$args);
        }
    }
}