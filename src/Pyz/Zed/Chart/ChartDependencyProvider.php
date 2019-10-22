<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Chart;

use Pyz\Zed\ExampleChart\Communication\Plugin\ExampleChartPlugin;
use Spryker\Zed\Chart\ChartDependencyProvider as SprykerChartDependencyProvider;
use Spryker\Zed\Chart\Communication\Plugin\Twig\TwigBarChartPlugin;
use Spryker\Zed\Chart\Communication\Plugin\Twig\TwigChartPlugin;
use Spryker\Zed\Chart\Communication\Plugin\Twig\TwigLineChartPlugin;
use Spryker\Zed\Chart\Communication\Plugin\Twig\TwigPieChartPlugin;
use Spryker\Zed\SalesStatistics\Communication\Plugin\Chart\CountOrderChartPlugin;
use Spryker\Zed\SalesStatistics\Communication\Plugin\Chart\StatusOrderChartPlugin;
use Spryker\Zed\SalesStatistics\Communication\Plugin\Chart\TopOrdersChartPlugin;

class ChartDependencyProvider extends SprykerChartDependencyProvider
{
     /**
      * @return \Spryker\Shared\ChartExtension\Dependency\Plugin\ChartPluginInterface[]
      */
    protected function getChartTwigPlugins(): array
    {
        return [
            new CountOrderChartPlugin(),
            new StatusOrderChartPlugin(),
            new TopOrdersChartPlugin(),
            new ExampleChartPlugin(),
        ];
    }

    /**
     * @return \Spryker\Shared\ChartExtension\Dependency\Plugin\ChartTwigFunctionPluginInterface[]
     */
    protected function geChartTwigFunctionPlugins(): array
    {
        return [
            new TwigBarChartPlugin(),
            new TwigLineChartPlugin(),
            new TwigPieChartPlugin(),
            new TwigChartPlugin(),
        ];
    }
}
