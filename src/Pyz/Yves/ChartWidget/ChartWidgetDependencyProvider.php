<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\ChartWidget;

use Pyz\Yves\ExampleChart\Plugin\ExampleChartPlugin;
use SprykerShop\Yves\ChartWidget\Plugin\Twig\Chart\ChartTwigPlugin;
use SprykerShop\Yves\ChartWidget\Plugin\Twig\Chart\BarChartTwigPlugin;
use SprykerShop\Yves\ChartWidget\Plugin\Twig\Chart\LineChartTwigPlugin;
use SprykerShop\Yves\ChartWidget\Plugin\Twig\Chart\PieChartTwigPlugin;
use SprykerShop\Yves\ChartWidget\ChartWidgetDependencyProvider as SprykerShopChartDependencyProvider;

class ChartWidgetDependencyProvider extends SprykerShopChartDependencyProvider
{
    /**
     * @return \Spryker\Shared\ChartExtension\Dependency\Plugin\ChartPluginInterface[]
     */
    protected function getChartTwigPlugins(): array
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
            new BarChartTwigPlugin(),
            new ChartTwigPlugin(),
            new PieChartTwigPlugin(),
            new LineChartTwigPlugin(),
        ];
    }
}
