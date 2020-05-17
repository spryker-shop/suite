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
        return [
            (new RuleTransfer())
                ->setBundle('dashboard-merchant-portal-gui')
                ->setController(static::RULE_VALIDATOR_WILDCARD)
                ->setAction(static::RULE_VALIDATOR_WILDCARD)
                ->setType(static::RULE_TYPE_ALLOW),
            (new RuleTransfer())
                ->setBundle('merchant-profile-merchant-portal-gui')
                ->setController(static::RULE_VALIDATOR_WILDCARD)
                ->setAction(static::RULE_VALIDATOR_WILDCARD)
                ->setType(static::RULE_TYPE_ALLOW),
            (new RuleTransfer())
                ->setBundle('product-offer-merchant-portal-gui')
                ->setController(static::RULE_VALIDATOR_WILDCARD)
                ->setAction(static::RULE_VALIDATOR_WILDCARD)
                ->setType(static::RULE_TYPE_ALLOW),
            (new RuleTransfer())
                ->setBundle('authentication-merchant-portal-gui')
                ->setController(static::RULE_VALIDATOR_WILDCARD)
                ->setAction(static::RULE_VALIDATOR_WILDCARD)
                ->setType(static::RULE_TYPE_ALLOW),
        ];
    }
}
