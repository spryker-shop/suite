<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Dashboard;

use Spryker\Zed\Dashboard\DashboardDependencyProvider as SprykerDashboardDependencyProvider;
use Spryker\Zed\SalesStatistics\Communication\Plugin\Chart\CountOrderChartPlugin;
use Spryker\Zed\SalesStatistics\Communication\Plugin\Chart\StatusOrderChartPlugin;
use Spryker\Zed\SalesStatistics\Communication\Plugin\Chart\TopOrdersChartPlugin;

class DashboardDependencyProvider extends SprykerDashboardDependencyProvider
{
    /**
     * @return \Spryker\Shared\Dashboard\Dependency\Plugin\DashboardPluginInterface[]
     */
    protected function getDashboardPlugins(): array
    {
        return [
            new CountOrderChartPlugin(),
            new StatusOrderChartPlugin(),
            new TopOrdersChartPlugin(),
        ];
    }
}
