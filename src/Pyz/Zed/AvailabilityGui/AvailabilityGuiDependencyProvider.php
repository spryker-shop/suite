<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\AvailabilityGui;

use Spryker\Zed\AvailabilityGui\AvailabilityGuiDependencyProvider as SprykerAvailabilityGuiDependencyProvider;
use Spryker\Zed\MerchantProductGui\Communication\Plugin\Availability\MerchantProductAvailabilityAbstractQueryCriteriaExpanderPlugin;
use Spryker\Zed\MerchantProductGui\Communication\Plugin\Availability\MerchantProductAvailabilityListDataExpanderPlugin;
use Spryker\Zed\MerchantProductGui\Communication\Plugin\Availability\MerchantProductAvailabilityViewDataExpanderPlugin;

class AvailabilityGuiDependencyProvider extends SprykerAvailabilityGuiDependencyProvider
{
    /**
     * @return \Spryker\Zed\AvailabilityGuiExtension\Dependency\Plugin\AvailabilityListDataExpanderPluginInterface[]
     */
    protected function getAvailabilityListDataExpanderPlugins(): array
    {
        return [
            new MerchantProductAvailabilityListDataExpanderPlugin(),
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
