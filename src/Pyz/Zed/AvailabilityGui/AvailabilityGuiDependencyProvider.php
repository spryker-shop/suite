<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\AvailabilityGui;

use Spryker\Zed\AvailabilityGui\AvailabilityGuiDependencyProvider as SprykerAvailabilityGuiDependencyProvider;
use Spryker\Zed\MerchantGui\Communication\Plugin\AvailabilityGui\MerchantAvailabilityListActionViewDataExpanderPlugin;
use Spryker\Zed\MerchantProductGui\Communication\Plugin\AvailabilityGui\MerchantProductAvailabilityAbstractTableQueryCriteriaExpanderPlugin;
use Spryker\Zed\MerchantProductGui\Communication\Plugin\AvailabilityGui\MerchantProductAvailabilityViewActionViewDataExpanderPlugin;

class AvailabilityGuiDependencyProvider extends SprykerAvailabilityGuiDependencyProvider
{
    /**
     * @return array<\Spryker\Zed\AvailabilityGuiExtension\Dependency\Plugin\AvailabilityListActionViewDataExpanderPluginInterface>
     */
    protected function getAvailabilityListActionViewDataExpanderPlugins(): array
    {
        return [
            new MerchantAvailabilityListActionViewDataExpanderPlugin(),
        ];
    }

    /**
     * @return array<\Spryker\Zed\AvailabilityGuiExtension\Dependency\Plugin\AvailabilityViewActionViewDataExpanderPluginInterface>
     */
    protected function getAvailabilityViewActionViewDataExpanderPlugins(): array
    {
        return [
            new MerchantProductAvailabilityViewActionViewDataExpanderPlugin(),
        ];
    }

    /**
     * @return array<\Spryker\Zed\AvailabilityGuiExtension\Dependency\Plugin\AvailabilityAbstractTableQueryCriteriaExpanderPluginInterface>
     */
    protected function getAvailabilityAbstractTableQueryCriteriaExpanderPlugins(): array
    {
        return [
            new MerchantProductAvailabilityAbstractTableQueryCriteriaExpanderPlugin(),
        ];
    }
}
