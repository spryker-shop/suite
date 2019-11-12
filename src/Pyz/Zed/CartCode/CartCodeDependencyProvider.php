<?php

namespace Pyz\Zed\CartCode;

use Spryker\Zed\CartCode\CartCodeDependencyProvider as SprykerCartCodeDependencyProvider;
use Spryker\Zed\Discount\Communication\Plugin\CartCode\VoucherCartCodePlugin;

class CartCodeDependencyProvider extends SprykerCartCodeDependencyProvider
{
    protected function getCartCodePlugins(): array
    {
        return [
            new VoucherCartCodePlugin(),
        ];
    }
}
