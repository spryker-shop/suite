<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Service\DataExport;

use Spryker\Service\DataExport\DataExportDependencyProvider as SprykerDataExportDependencyProvider;
use Spryker\Service\DataExport\Plugin\DataExport\OutputStreamDataExportConnectionPlugin;

class DataExportDependencyProvider extends SprykerDataExportDependencyProvider
{
    /**
     * @return list<\Spryker\Service\DataExportExtension\Dependency\Plugin\DataExportConnectionPluginInterface>
     */
    protected function getDataExportConnectionPlugins(): array
    {
        return [
            new OutputStreamDataExportConnectionPlugin(),
        ];
    }
}
