<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\OauthUserConnector;

use Spryker\Zed\OauthMerchantUser\Communication\Plugin\OauthUserConnector\MerchantUserTypeOauthScopeAuthorizationCheckerPlugin;
use Spryker\Zed\OauthMerchantUser\Communication\Plugin\OauthUserConnector\MerchantUserTypeOauthScopeProviderPlugin;
use Spryker\Zed\OauthUserConnector\Communication\Plugin\OauthUserConnector\BackofficeUserOauthScopeAuthorizationCheckerPlugin;
use Spryker\Zed\OauthUserConnector\OauthUserConnectorDependencyProvider as SprykerOauthUserConnectorDependencyProvider;
use Spryker\Zed\OauthWarehouseUser\Communication\Plugin\OauthUserConnector\WarehouseUserTypeOauthScopeAuthorizationCheckerPlugin;
use Spryker\Zed\OauthWarehouseUser\Communication\Plugin\OauthUserConnector\WarehouseUserTypeOauthScopeProviderPlugin;

class OauthUserConnectorDependencyProvider extends SprykerOauthUserConnectorDependencyProvider
{
    /**
     * @return list<\Spryker\Zed\OauthUserConnectorExtension\Dependency\Plugin\UserTypeOauthScopeProviderPluginInterface>
     */
    protected function getUserTypeOauthScopeProviderPlugins(): array
    {
        return [
            new MerchantUserTypeOauthScopeProviderPlugin(),
            new WarehouseUserTypeOauthScopeProviderPlugin(),
        ];
    }

    /**
     * @return list<\Spryker\Zed\OauthUserConnectorExtension\Dependency\Plugin\UserTypeOauthScopeAuthorizationCheckerPluginInterface>
     */
    protected function getUserTypeOauthScopeAuthorizationCheckerPlugins(): array
    {
        return [
            new MerchantUserTypeOauthScopeAuthorizationCheckerPlugin(),
            new WarehouseUserTypeOauthScopeAuthorizationCheckerPlugin(),
            new BackofficeUserOauthScopeAuthorizationCheckerPlugin(),
        ];
    }
}
