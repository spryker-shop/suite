<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\MerchantUser;

use Spryker\Zed\AclMerchantPortal\Communication\Plugin\MerchantUser\MerchantAclMerchantUserPostCreatePlugin;
use Spryker\Zed\MerchantUser\MerchantUserDependencyProvider as SprykerMerchantUserDependencyProvider;

class MerchantUserDependencyProvider extends SprykerMerchantUserDependencyProvider
{
    /**
     * @return \Spryker\Zed\MerchantUserExtension\Dependency\Plugin\MerchantUserPostCreatePluginInterface[]
     */
    protected function getMerchantUserPostCreatePlugins(): array
    {
        return [
            new MerchantAclMerchantUserPostCreatePlugin(),
        ];
    }
}
