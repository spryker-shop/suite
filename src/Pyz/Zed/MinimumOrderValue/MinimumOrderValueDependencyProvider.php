<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\MinimumOrderValue;

use Spryker\Zed\Kernel\Container;
use Spryker\Zed\MerchantRelationshipMinimumOrderValue\Communication\Plugin\MinimumOrderValue\MerchantRelationhipMinimumOrderValueDataSourceStrategyPlugin;
use Spryker\Zed\MinimumOrderValue\MinimumOrderValueDependencyProvider as SprykerMinimumOrderValueDependencyProvider;

class MinimumOrderValueDependencyProvider extends SprykerMinimumOrderValueDependencyProvider
{
    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\MinimumOrderValueExtension\Dependency\Plugin\MinimumOrderValueDataSourceStrategyPluginInterface[]
     */
    protected function getMinimumOrderValueDataSourceStrategies(Container $container): array
    {
        return [
            new MerchantRelationhipMinimumOrderValueDataSourceStrategyPlugin(),
        ];
    }
}
