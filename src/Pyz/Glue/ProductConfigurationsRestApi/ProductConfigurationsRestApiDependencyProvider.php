<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Glue\ProductConfigurationsRestApi;

use Spryker\Glue\ProductConfigurationsPriceProductVolumesRestApi\Plugin\ProductConfigurationsRestApi\ProductConfigurationVolumePriceCartItemProductConfigurationMapperPlugin;
use Spryker\Glue\ProductConfigurationsPriceProductVolumesRestApi\Plugin\ProductConfigurationsRestApi\ProductConfigurationVolumePriceRestCartItemProductConfigurationMapperPlugin;
use Spryker\Glue\ProductConfigurationsRestApi\ProductConfigurationsRestApiDependencyProvider as SprykerProductConfigurationsRestApiDependencyProvider;

class ProductConfigurationsRestApiDependencyProvider extends SprykerProductConfigurationsRestApiDependencyProvider
{
    /**
     * @return \Spryker\Glue\ProductConfigurationsRestApiExtension\Dependency\Plugin\CartItemProductConfigurationMapperPluginInterface[]
     */
    protected function getCartItemProductConfigurationMapperPlugins(): array
    {
        return [
            new ProductConfigurationVolumePriceCartItemProductConfigurationMapperPlugin(),
        ];
    }

    /**
     * @return \Spryker\Glue\ProductConfigurationsRestApiExtension\Dependency\Plugin\RestCartItemProductConfigurationMapperPluginInterface[]
     */
    protected function getRestCartItemProductConfigurationMapperPlugins(): array
    {
        return [
            new ProductConfigurationVolumePriceRestCartItemProductConfigurationMapperPlugin(),
        ];
    }
}
