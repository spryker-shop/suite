<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Pyz\Zed\ZedNavigation;

use Spryker\Zed\Acl\Communication\Plugin\Navigation\AclNavigationItemCollectionFilterPlugin;
use Spryker\Zed\MultiFactorAuth\Communication\Plugin\Navigation\AgentMerchantPortalNavigationItemCollectionFilterPlugin;
use Spryker\Zed\MultiFactorAuth\Communication\Plugin\Navigation\MerchantPortalNavigationItemCollectionFilterPlugin;
use Spryker\Zed\ZedNavigation\Communication\Plugin\BackofficeNavigationItemCollectionFilterPlugin;
use Spryker\Zed\ZedNavigation\ZedNavigationDependencyProvider as SprykerZedNavigationDependencyProvider;

class ZedNavigationDependencyProvider extends SprykerZedNavigationDependencyProvider
{
    /**
     * @return array<\Spryker\Zed\ZedNavigationExtension\Dependency\Plugin\NavigationItemCollectionFilterPluginInterface>
     */
    protected function getNavigationItemCollectionFilterPlugins(): array
    {
        return [
            new AclNavigationItemCollectionFilterPlugin(),
            new BackofficeNavigationItemCollectionFilterPlugin(),
            new MerchantPortalNavigationItemCollectionFilterPlugin(),
            new AgentMerchantPortalNavigationItemCollectionFilterPlugin(),
        ];
    }
}
