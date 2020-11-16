<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\AvailabilityCartConnector;

use Spryker\Zed\AvailabilityCartConnector\AvailabilityCartConnectorDependencyProvider as SprykerAbstractBundleDependencyProvider;
use Spryker\Zed\ProductConfiguration\Communication\Plugin\AvailabilityCartConnector\ProductConfigurationCartItemQuantityCounterStrategyPlugin;

class AvailabilityCartConnectorDependencyProvider extends SprykerAbstractBundleDependencyProvider
{
    /**
     * @return \Spryker\Zed\AvailabilityCartConnectorExtension\Dependency\Plugin\CartItemQuantityCounterStrategyPluginInterface[]
     */
    public function getCartItemQuantityCounterStrategyPlugins(): array
    {
        return [
            new ProductConfigurationCartItemQuantityCounterStrategyPlugin(),
        ];
    }
}
