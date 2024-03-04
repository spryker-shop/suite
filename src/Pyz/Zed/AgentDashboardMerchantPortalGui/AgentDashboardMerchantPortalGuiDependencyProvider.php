<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\AgentDashboardMerchantPortalGui;

use Spryker\Zed\AclMerchantPortal\Communication\Plugin\AgentDashboardMerchantPortalGui\BackofficeAllowedAclGroupMerchantUserTableDataExpanderPlugin;
use Spryker\Zed\AgentDashboardMerchantPortalGui\AgentDashboardMerchantPortalGuiDependencyProvider as SprykerAgentDashboardMerchantPortalGuiDependencyProvider;

class AgentDashboardMerchantPortalGuiDependencyProvider extends SprykerAgentDashboardMerchantPortalGuiDependencyProvider
{
    /**
     * @return array<\Spryker\Zed\AgentDashboardMerchantPortalGuiExtension\Dependency\Plugin\MerchantUserTableDataExpanderPluginInterface>
     */
    protected function getMerchantUserTableDataExpanderPlugins(): array
    {
        return [
            new BackofficeAllowedAclGroupMerchantUserTableDataExpanderPlugin(),
        ];
    }
}
