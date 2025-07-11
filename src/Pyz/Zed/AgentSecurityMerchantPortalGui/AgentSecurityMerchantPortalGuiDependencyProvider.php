<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Pyz\Zed\AgentSecurityMerchantPortalGui;

use Spryker\Zed\AgentSecurityMerchantPortalGui\AgentSecurityMerchantPortalGuiDependencyProvider as SprykerAgentSecurityMerchantPortalGuiDependencyProvider;
use Spryker\Zed\MultiFactorAuth\Communication\Plugin\AuthenticationHandler\MerchantAgentUser\MerchantAgentUserMultiFactorAuthenticationHandlerPlugin;

class AgentSecurityMerchantPortalGuiDependencyProvider extends SprykerAgentSecurityMerchantPortalGuiDependencyProvider
{
    /**
     * @return array<\Spryker\Zed\SecurityMerchantPortalGuiExtension\Dependency\Plugin\AuthenticationHandlerPluginInterface>
     */
    protected function getMerchantAgentUserAuthenticationHandlerPlugins(): array
    {
        return [
            new MerchantAgentUserMultiFactorAuthenticationHandlerPlugin(),
        ];
    }
}
