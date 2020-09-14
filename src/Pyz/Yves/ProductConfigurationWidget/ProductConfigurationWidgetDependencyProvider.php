<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\ProductConfigurationWidget;

use Pyz\Yves\ProductConfigurationWidget\Plugin\ProductConfigurationWidget\ProductConfigurationPlainRenderStrategyPlugin;
use SprykerShop\Yves\ProductConfigurationWidget\ProductConfigurationWidgetDependencyProvider as SprykerProductConfigurationWidgetDependencyProvider;

class ProductConfigurationWidgetDependencyProvider extends SprykerProductConfigurationWidgetDependencyProvider
{
    /**
     * @return \SprykerShop\Yves\ProductConfigurationWidgetExtension\Dependency\Plugin\ProductConfigurationRenderStrategyPluginInterface[]
     */
    protected function getProductConfigurationRenderStrategyPlugins(): array
    {
        return [
            new ProductConfigurationPlainRenderStrategyPlugin(),
        ];
    }
}
