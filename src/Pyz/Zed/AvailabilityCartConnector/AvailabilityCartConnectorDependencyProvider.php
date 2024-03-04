<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\AvailabilityCartConnector;

use Spryker\Zed\AvailabilityCartConnector\AvailabilityCartConnectorDependencyProvider as SprykerAbstractBundleDependencyProvider;
use Spryker\Zed\ProductConfigurationCart\Communication\Plugin\AvailabilityCartConnector\ProductConfigurationCartItemQuantityCounterStrategyPlugin;
use Spryker\Zed\ProductOffer\Communication\Plugin\Cart\ProductOfferCartItemQuantityCounterStrategyPlugin;

class AvailabilityCartConnectorDependencyProvider extends SprykerAbstractBundleDependencyProvider
{
    /**
     * @return array<\Spryker\Zed\AvailabilityCartConnectorExtension\Dependency\Plugin\CartItemQuantityCounterStrategyPluginInterface>
     */
    public function getCartItemQuantityCounterStrategyPlugins(): array
    {
        return [
            new ProductConfigurationCartItemQuantityCounterStrategyPlugin(),
            new ProductOfferCartItemQuantityCounterStrategyPlugin(),
        ];
    }
}
