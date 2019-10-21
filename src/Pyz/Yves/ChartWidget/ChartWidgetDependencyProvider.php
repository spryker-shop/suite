<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\ChartWidget;

use Pyz\Zed\ExampleChart\Communication\Plugin\ExampleChartPlugin;
use SprykerShop\ChartWidget\src\SprykerShop\Yves\ChartWidget\Plugin\Twig\BarChartTwigPlugin;
use SprykerShop\ChartWidget\src\SprykerShop\Yves\ChartWidget\Plugin\Twig\LineChartTwigPlugin;
use SprykerShop\ChartWidget\src\SprykerShop\Yves\ChartWidget\Plugin\Twig\MainChartTwigPlugin;
use SprykerShop\ChartWidget\src\SprykerShop\Yves\ChartWidget\Plugin\Twig\PieChartTwigPlugin;
use SprykerShop\Yves\ChartWidget\ChartWidgetDependencyProvider as SprykerShopChartDependencyProvider;

class ChartWidgetDependencyProvider extends SprykerShopChartDependencyProvider
{
    /**
     * @return \Spryker\Shared\Chart\Dependency\Plugin\ChartPluginInterface[]
     */
    protected function getChartPlugins(): array
    {
        return [
            new ExampleChartPlugin(),
        ];
    }

    /**
     * @return \Spryker\Shared\ChartExtension\Dependency\Plugin\ChartTwigFunctionPluginInterface[]
     */
    protected function getChartTwigFunctionPlugins(): array
    {
        return [
            new PieChartTwigPlugin(),
            new BarChartTwigPlugin(),
            new LineChartTwigPlugin(),
            new MainChartTwigPlugin(),
        ];
    }
}
