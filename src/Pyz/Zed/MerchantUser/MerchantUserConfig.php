<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\MerchantUser;

use Generated\Shared\Transfer\RuleTransfer;
use Spryker\Zed\MerchantUser\MerchantUserConfig as SprykerMerchantUserConfig;

class MerchantUserConfig extends SprykerMerchantUserConfig
{
    /**
     * @return \Generated\Shared\Transfer\RuleTransfer[]
     */
    protected function getMerchantAdminAclRules(): array
    {
        return $this->getAllowedBundlesAclRules();
    }

    /**
     * @return \Generated\Shared\Transfer\RuleTransfer[]
     */
    protected function getAllowedBundlesAclRules(): array
    {
        $bundleNames = [
            'dashboard-merchant-portal-gui',
            'merchant-profile-merchant-portal-gui',
            'product-offer-merchant-portal-gui',
            'authentication-merchant-portal-gui',
            'sales-merchant-portal-gui',
        ];

        $ruleTransfers = [];

        foreach ($bundleNames as $bundleName) {
            $ruleTransfers[] = (new RuleTransfer())
                ->setBundle($bundleName)
                ->setController(static::RULE_VALIDATOR_WILDCARD)
                ->setAction(static::RULE_VALIDATOR_WILDCARD)
                ->setType(static::RULE_TYPE_ALLOW);
        }

        return $ruleTransfers;
    }
}
