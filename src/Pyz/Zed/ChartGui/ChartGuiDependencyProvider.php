<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Pyz\Zed\ChartGui;

use Spryker\Zed\ChartGui\ChartGuiDependencyProvider as SprykerChartGuiDependencyProvider;
use Spryker\Zed\ChartGui\Communication\Plugin\Twig\Chart\BartChartTwigPlugin;
use Spryker\Zed\ChartGui\Communication\Plugin\Twig\Chart\ChartTwigPlugin;
use Spryker\Zed\ChartGui\Communication\Plugin\Twig\Chart\LineChartTwigPlugin;
use Spryker\Zed\ChartGui\Communication\Plugin\Twig\Chart\PieChartTwigPlugin;

class ChartGuiDependencyProvider extends SprykerChartGuiDependencyProvider
{
    /**
     * @return \Spryker\Shared\Chart\Dependency\Plugin\TwigChartFunctionPluginInterface[]
     */
    protected function getTwigChartFunctionPlugins(): array
    {
        return [
            new BartChartTwigPlugin(),
            new ChartTwigPlugin(),
            new LineChartTwigPlugin(),
            new PieChartTwigPlugin(),
        ];
    }
}
