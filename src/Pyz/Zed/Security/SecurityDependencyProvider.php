<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Pyz\Zed\Security;

use Spryker\Zed\AgentSecurityMerchantPortalGui\Communication\Plugin\MultiFactorAuth\MultiFactorAuthenticationAgentMerchantUserSecurityPlugin;
use Spryker\Zed\AgentSecurityMerchantPortalGui\Communication\Plugin\Security\ZedAgentMerchantUserSecurityPlugin;
use Spryker\Zed\Security\SecurityDependencyProvider as SprykerSecurityDependencyProvider;
use Spryker\Zed\SecurityGui\Communication\Plugin\Security\ZedUserSecurityPlugin;
use Spryker\Zed\SecurityMerchantPortalGui\Communication\Plugin\MultiFactorAuth\MultiFactorAuthenticationMerchantUserSecurityPlugin;
use Spryker\Zed\SecurityMerchantPortalGui\Communication\Plugin\Security\ZedMerchantUserSecurityPlugin;
use Spryker\Zed\SecurityOauthUser\Communication\Plugin\Security\ZedOauthUserSecurityPlugin;
use Spryker\Zed\SecuritySystemUser\Communication\Plugin\Security\ZedSystemUserSecurityPlugin;
use Spryker\Zed\SessionUserValidation\Communication\Plugin\Security\SaveSessionUserSecurityPlugin;
use Spryker\Zed\SessionUserValidation\Communication\Plugin\Security\ZedValidateSessionUserSecurityPlugin;
use Spryker\Zed\User\Communication\Plugin\Security\ZedUserSessionHandlerSecurityPlugin;

class SecurityDependencyProvider extends SprykerSecurityDependencyProvider
{
    /**
     * @return array<\Spryker\Shared\SecurityExtension\Dependency\Plugin\SecurityPluginInterface>
     */
    protected function getSecurityPlugins(): array
    {
        return [
            new ZedUserSessionHandlerSecurityPlugin(),
            new ZedSystemUserSecurityPlugin(),
            new ZedAgentMerchantUserSecurityPlugin(),
            new ZedMerchantUserSecurityPlugin(),
            new MultiFactorAuthenticationMerchantUserSecurityPlugin(),
            new MultiFactorAuthenticationAgentMerchantUserSecurityPlugin(),
            new ZedUserSecurityPlugin(),
            new ZedOauthUserSecurityPlugin(),
            new ZedValidateSessionUserSecurityPlugin(),
            new SaveSessionUserSecurityPlugin(),
        ];
    }
}
