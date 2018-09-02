<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\MinimumOrderValue;

use Spryker\Zed\MerchantRelationshipMinimumOrderValue\Communication\Plugin\MinimumOrderValue\MerchantRelationshipMinimumOrderValueDataSourceStrategyPlugin;
use Spryker\Zed\MinimumOrderValue\MinimumOrderValueDependencyProvider as SprykerMinimumOrderValueDependencyProvider;

class MinimumOrderValueDependencyProvider extends SprykerMinimumOrderValueDependencyProvider
{
    /**
     * @return \Spryker\Zed\MinimumOrderValueExtension\Dependency\Plugin\MinimumOrderValueDataSourceStrategyPluginInterface[]
     */
    protected function getMinimumOrderValueDataSourceStrategies(): array
    {
        return [
            new MerchantRelationshipMinimumOrderValueDataSourceStrategyPlugin(),
        ];
    }
}
