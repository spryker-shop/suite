<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\UserMerchantPortalGui;

use Spryker\Zed\SecurityMerchantPortalGui\Communication\Plugin\UserMerchantPortalGui\SecurityTokenUpdateMerchantUserPostChangePlugin;
use Spryker\Zed\UserMerchantPortalGui\UserMerchantPortalGuiDependencyProvider as SprykerUserMerchantPortalGuiDependencyProvider;

class UserMerchantPortalGuiDependencyProvider extends SprykerUserMerchantPortalGuiDependencyProvider
{
    /**
     * @return \Spryker\Zed\UserMerchantPortalGuiExtension\Dependency\Plugin\MerchantUserPostChangePluginInterface[]
     */
    public function getMerchantUserPostChangePlugins(): array
    {
        return [
            new SecurityTokenUpdateMerchantUserPostChangePlugin(),
        ];
    }
}
