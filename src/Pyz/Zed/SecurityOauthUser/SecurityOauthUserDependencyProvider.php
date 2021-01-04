<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\SecurityOauthUser;

use Spryker\Zed\MerchantUser\Communication\Plugin\SecurityOauthUser\MerchantUserOauthUserRestrictionPlugin;
use Spryker\Zed\SecurityOauthUser\SecurityOauthUserDependencyProvider as SprykerSecurityOauthUserDependencyProvider;

class SecurityOauthUserDependencyProvider extends SprykerSecurityOauthUserDependencyProvider
{
    /**
     * @return \Spryker\Zed\SecurityOauthUserExtension\Dependency\Plugin\OauthUserRestrictionPluginInterface[]
     */
    protected function getOauthUserRestrictionPlugins(): array
    {
        return [
            new MerchantUserOauthUserRestrictionPlugin(),
        ];
    }
}
