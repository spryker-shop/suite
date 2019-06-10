<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\SetupFrontend;

use Spryker\Zed\SetupFrontend\SetupFrontendDependencyProvider as SprykerSetupFrontendDependencyProvider;
use Spryker\Zed\Twig\Communication\Plugin\SetupFrontend\ThemeYvesFrontendStoreConfigExpanderPlugin;

class SetupFrontendDependencyProvider extends SprykerSetupFrontendDependencyProvider
{
    /**
     * @return \Spryker\Zed\SetupFrontendExtension\Dependency\YvesFrontendStoreConfigExpanderPluginInterface[]
     */
    protected function getYvesFrontendStoreConfigExpanderPlugins(): array
    {
        return [
            new ThemeYvesFrontendStoreConfigExpanderPlugin(),
        ];
    }
}
