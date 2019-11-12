<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\CartCode;

use Spryker\Zed\CartCode\CartCodeDependencyProvider as SprykerCartCodeDependencyProvider;
use Spryker\Zed\Discount\Communication\Plugin\CartCode\VoucherCartCodePlugin;

class CartCodeDependencyProvider extends SprykerCartCodeDependencyProvider
{
    /**
     * @return \Spryker\Shared\CartCodeExtension\Dependency\Plugin\CartCodePluginInterface[]
     */
    protected function getCartCodePlugins(): array
    {
        return [
            new VoucherCartCodePlugin(),
        ];
    }
}
