<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\AvailabilityGui;

use Spryker\Zed\AvailabilityGui\AvailabilityGuiDependencyProvider as SprykerAvailabilityGuiDependencyProvider;
use Spryker\Zed\MerchantGui\Communication\Plugin\AvailabilityGui\MerchantAvailabilityListDataExpanderPlugin;
use Spryker\Zed\MerchantProductGui\Communication\Plugin\AvailabilityGui\MerchantProductAvailabilityAbstractQueryCriteriaExpanderPlugin;
use Spryker\Zed\MerchantProductGui\Communication\Plugin\AvailabilityGui\MerchantProductAvailabilityViewDataExpanderPlugin;

class AvailabilityGuiDependencyProvider extends SprykerAvailabilityGuiDependencyProvider
{
    /**
     * @return \Spryker\Zed\AvailabilityGuiExtension\Dependency\Plugin\AvailabilityListDataExpanderPluginInterface[]
     */
    protected function getAvailabilityListDataExpanderPlugins(): array
    {
        return [
            new MerchantAvailabilityListDataExpanderPlugin(),
        ];
    }

    /**
     * @return \Spryker\Zed\AvailabilityGuiExtension\Dependency\Plugin\AvailabilityViewDataExpanderPluginInterface[]
     */
    protected function getAvailabilityViewDataExpanderPlugins(): array
    {
        return [
            new MerchantProductAvailabilityViewDataExpanderPlugin(),
        ];
    }

    /**
     * @return \Spryker\Zed\AvailabilityGuiExtension\Dependency\Plugin\AvailabilityAbstractQueryCriteriaExpanderPluginInterface[]
     */
    protected function getAvailabilityAbstractQueryCriteriaExpanderPlugins(): array
    {
        return [
            new MerchantProductAvailabilityAbstractQueryCriteriaExpanderPlugin(),
        ];
    }
}
