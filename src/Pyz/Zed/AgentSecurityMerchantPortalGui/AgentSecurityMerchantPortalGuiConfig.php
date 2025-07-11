<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Pyz\Zed\AgentSecurityMerchantPortalGui;

use Spryker\Zed\AgentSecurityMerchantPortalGui\AgentSecurityMerchantPortalGuiConfig as SprykerAgentSecurityMerchantPortalGuiConfig;

class AgentSecurityMerchantPortalGuiConfig extends SprykerAgentSecurityMerchantPortalGuiConfig
{
    /**
     * @return string
     */
    public function getRoutePatternAgentMerchantPortal(): string
    {
        return '^/(agent(.+)-merchant-portal-gui|multi-factor-auth/(agent-merchant-user|user-management-agent-merchant-portal))(?!agent-security-merchant-portal-gui\/login$)/';
    }

    /**
     * @return string
     */
    public function getRoutePatternAgentMerchantPortalLogin(): string
    {
        return '^/(agent-security-merchant-portal-gui/login|multi-factor-auth/agent-merchant-user($|/))';
    }
}
