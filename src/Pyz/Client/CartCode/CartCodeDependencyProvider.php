<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\CartCode;

use Spryker\Client\CartCode\CartCodeDependencyProvider as SprykerCartCodeDependencyProvider;
use Spryker\Client\Discount\Plugin\CartCode\VoucherCartCodeHandlerPlugin;
use Spryker\Client\GiftCard\Plugin\CartCode\GiftCardCartCodeHandlerPlugin;

class CartCodeDependencyProvider extends SprykerCartCodeDependencyProvider
{
    /**
     * @return \Spryker\Client\CartCodeExtension\Dependency\Plugin\CartCodeHandlerPluginInterface[]
     */
    protected function getCartCodeHandlerPluginCollection(): array
    {
        return [
            new VoucherCartCodeHandlerPlugin(),
            new GiftCardCartCodeHandlerPlugin(),
        ];
    }
}
