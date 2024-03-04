<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Acl;

use Spryker\Zed\Acl\AclDependencyProvider as SprykerAclDependencyProvider;
use Spryker\Zed\AclEntity\Communication\Plugin\Acl\AclEntityAclRolePostSavePlugin;
use Spryker\Zed\AclEntity\Communication\Plugin\Acl\AclRulesAclRolesExpanderPlugin;
use Spryker\Zed\AclMerchantAgent\Communication\Plugin\Acl\MerchantAgentAclAccessCheckerStrategyPlugin;
use Spryker\Zed\AclMerchantPortal\Communication\Plugin\MerchantUser\ProductViewerForOfferCreationAclInstallerPlugin;

class AclDependencyProvider extends SprykerAclDependencyProvider
{
    /**
     * @return array<\Spryker\Zed\AclExtension\Dependency\Plugin\AclRolesExpanderPluginInterface>
     */
    protected function getAclRolesExpanderPlugins(): array
    {
        return [
            new AclRulesAclRolesExpanderPlugin(),
        ];
    }

    /**
     * @return array<\Spryker\Zed\AclExtension\Dependency\Plugin\AclRolePostSavePluginInterface>
     */
    protected function getAclRolePostSavePlugins(): array
    {
        return [
            new AclEntityAclRolePostSavePlugin(),
        ];
    }

    /**
     * @return array<\Spryker\Zed\AclExtension\Dependency\Plugin\AclInstallerPluginInterface>
     */
    protected function getAclInstallerPlugins(): array
    {
        return [
            new ProductViewerForOfferCreationAclInstallerPlugin(),
        ];
    }

    /**
     * @return array<\Spryker\Zed\AclExtension\Dependency\Plugin\AclAccessCheckerStrategyPluginInterface>
     */
    protected function getAclAccessCheckerStrategyPlugins(): array
    {
        return [
            new MerchantAgentAclAccessCheckerStrategyPlugin(),
        ];
    }
}
