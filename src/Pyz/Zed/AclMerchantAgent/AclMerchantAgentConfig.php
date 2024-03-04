<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\AclMerchantAgent;

use Spryker\Zed\AclMerchantAgent\AclMerchantAgentConfig as SprykerAclMerchantAgentConfig;

class AclMerchantAgentConfig extends SprykerAclMerchantAgentConfig
{
    /**
     * @var list<string>
     */
    protected const MERCHANT_AGENT_ACL_BUNDLE_ALLOWED_LIST = [
        'agent-dashboard-merchant-portal-gui',
    ];
}
