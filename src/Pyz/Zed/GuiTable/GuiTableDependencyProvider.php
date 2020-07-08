<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\GuiTable;

use Spryker\Zed\GuiTable\Communication\Plugin\GuiTable\DateRangeRequestFilterValueNormalizerPlugin;
use Spryker\Zed\GuiTable\Communication\Plugin\GuiTable\DateResponseColumnValueFormatterPlugin;
use Spryker\Zed\GuiTable\GuiTableDependencyProvider as SprykerGuiTableDependencyProvider;

class GuiTableDependencyProvider extends SprykerGuiTableDependencyProvider
{
    /**
     * @return \Spryker\Zed\GuiTableExtension\Dependency\Plugin\RequestFilterValueNormalizerPluginInterface[]
     */
    protected function getRequestFilterValueNormalizerPlugins(): array
    {
        return [
            new DateRangeRequestFilterValueNormalizerPlugin(),
        ];
    }

    /**
     * @return \Spryker\Zed\GuiTableExtension\Dependency\Plugin\ResponseColumnValueFormatterPluginInterface[]
     */
    protected function getResponseColumnValueFormatterPlugins(): array
    {
        return [
            new DateResponseColumnValueFormatterPlugin(),
        ];
    }
}
