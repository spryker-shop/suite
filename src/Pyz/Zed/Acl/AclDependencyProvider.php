<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Acl;

use Spryker\Zed\Acl\AclDependencyProvider as SprykerAclDependencyProvider;
use Spryker\Zed\MerchantUser\Communication\Plugin\Acl\MerchantUserAclInstallerPlugin;

class AclDependencyProvider extends SprykerAclDependencyProvider
{
    /**
     * @return \Spryker\Zed\AclExtension\Dependency\Plugin\AclInstallerPluginInterface[]
     */
    public function getAclInstallerPlugins(): array
    {
        return [
            new MerchantUserAclInstallerPlugin(),
        ];
    }
}
