<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ZedNavigation;

use Spryker\Zed\Acl\Communication\Plugin\Navigation\AclNavigationItemFilterPlugin;
use Spryker\Zed\ZedNavigation\ZedNavigationDependencyProvider as SprykerZedNavigationDependencyProvider;

class ZedNavigationDependencyProvider extends SprykerZedNavigationDependencyProvider
{
    /**
     * @return \Spryker\Zed\NavigationExtension\Dependency\Plugin\NavigationItemFilterPluginInterface[]
     */
    protected function getNavigationItemFilterPlugins(): array
    {
        return [
            new AclNavigationItemFilterPlugin(),
        ];
    }
}
