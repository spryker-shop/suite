<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\MerchantUser;

use Spryker\Zed\AclMerchantPortal\Communication\Plugin\MerchantUser\AclMerchantPortalMerchantUserRoleFilterPreConditionPlugin;
use Spryker\Zed\AclMerchantPortal\Communication\Plugin\MerchantUser\MerchantUserAclEntitiesMerchantUserPostCreatePlugin;
use Spryker\Zed\MerchantUser\MerchantUserDependencyProvider as SprykerMerchantUserDependencyProvider;

class MerchantUserDependencyProvider extends SprykerMerchantUserDependencyProvider
{
    /**
     * @return array<\Spryker\Zed\MerchantUserExtension\Dependency\Plugin\MerchantUserPostCreatePluginInterface>
     */
    protected function getMerchantUserPostCreatePlugins(): array
    {
        return [
            new MerchantUserAclEntitiesMerchantUserPostCreatePlugin(),
        ];
    }

    /**
     * @return array<\Spryker\Zed\MerchantUserExtension\Dependency\Plugin\MerchantUserRoleFilterPreConditionPluginInterface>
     */
    protected function getMerchantUserRoleFilterPreConditionPlugins(): array
    {
        return [
            new AclMerchantPortalMerchantUserRoleFilterPreConditionPlugin(),
        ];
    }
}
