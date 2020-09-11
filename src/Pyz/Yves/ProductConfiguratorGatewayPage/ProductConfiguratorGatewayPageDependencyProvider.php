<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\ProductConfiguratorGatewayPage;

use SprykerShop\Yves\CartPage\Plugin\ProductConfiguratorGatewayPage\CartPageGatewayBackUrlResolverStrategyPlugin;
use SprykerShop\Yves\ProductConfiguratorGatewayPage\Plugin\ProductDetailPageGatewayBackUrlResolverStrategyPlugin;
use SprykerShop\Yves\ProductConfiguratorGatewayPage\ProductConfiguratorGatewayPageDependencyProvider as SprykerProductConfiguratorGatewayPageDependencyProvider;

class ProductConfiguratorGatewayPageDependencyProvider extends SprykerProductConfiguratorGatewayPageDependencyProvider
{
    /**
     * @return \SprykerShop\Yves\ProductConfiguratorGatewayPageExtension\Dependency\Plugin\ProductConfiguratorGatewayBackUrlResolverStrategyPluginInterface[]
     */
    protected function getProductConfiguratorGatewayBackUrlResolverStrategyPlugins(): array
    {
        return [
            new ProductDetailPageGatewayBackUrlResolverStrategyPlugin(),
            new CartPageGatewayBackUrlResolverStrategyPlugin(),
        ];
    }
}
