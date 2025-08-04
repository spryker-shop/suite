<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Pyz\Zed\MultiFactorAuthMerchantPortal;

use Spryker\Zed\AgentSecurityMerchantPortalGui\Communication\Plugin\MultiFactorAuth\PostAgentMerchantUserLoginMultiFactorAuthenticationPlugin;
use Spryker\Zed\MultiFactorAuth\Communication\Plugin\Factors\Email\UserEmailMultiFactorAuthPlugin;
use Spryker\Zed\MultiFactorAuthMerchantPortal\MultiFactorAuthMerchantPortalDependencyProvider as SprykerMultiFactorAuthMultiFactorAuthMerchantPortalDependencyProvider;
use Spryker\Zed\SecurityMerchantPortalGui\Communication\Plugin\MultiFactorAuth\PostMerchantUserLoginMultiFactorAuthenticationPlugin;

class MultiFactorAuthMerchantPortalDependencyProvider extends SprykerMultiFactorAuthMultiFactorAuthMerchantPortalDependencyProvider
{
    /**
     * @return array<\Spryker\Shared\MultiFactorAuthExtension\Dependency\Plugin\MultiFactorAuthPluginInterface>
     */
    protected function getUserMultiFactorAuthPlugins(): array
    {
        return [
            new UserEmailMultiFactorAuthPlugin(),
        ];
    }

    /**
     * @return array<\Spryker\Shared\MultiFactorAuthExtension\Dependency\Plugin\PostLoginMultiFactorAuthenticationPluginInterface>
     */
    protected function getPostLoginMultiFactorAuthenticationPlugins(): array
    {
        return [
            new PostMerchantUserLoginMultiFactorAuthenticationPlugin(),
            new PostAgentMerchantUserLoginMultiFactorAuthenticationPlugin(),
        ];
    }
}
