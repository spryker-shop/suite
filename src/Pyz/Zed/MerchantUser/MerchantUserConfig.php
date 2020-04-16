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
    protected const BUNDLE_MERCHANT_DASHBOARD_GUI_PAGE = 'merchant-dashboard-gui-page';
    protected const BUNDLE_PROFILE_DASHBOARD_GUI_PAGE = 'merchant-profile-gui-page';

    /**
     * @return \Generated\Shared\Transfer\RuleTransfer[]
     */
    protected function getMerchantAdminAclRules(): array
    {
        return [
            (new RuleTransfer())
                ->setBundle(static::BUNDLE_MERCHANT_DASHBOARD_GUI_PAGE)
                ->setController(static::RULE_VALIDATOR_WILDCARD)
                ->setAction(static::RULE_VALIDATOR_WILDCARD)
                ->setType(static::RULE_TYPE_ALLOW),
            (new RuleTransfer())
                ->setBundle(static::BUNDLE_PROFILE_DASHBOARD_GUI_PAGE)
                ->setController(static::RULE_VALIDATOR_WILDCARD)
                ->setAction(static::RULE_VALIDATOR_WILDCARD)
                ->setType(static::RULE_TYPE_ALLOW),
            (new RuleTransfer())
                ->setBundle('product-offer-merchant-portal-gui')
                ->setController(static::RULE_VALIDATOR_WILDCARD)
                ->setAction(static::RULE_VALIDATOR_WILDCARD)
                ->setType(static::RULE_TYPE_ALLOW),
        ];
    }
}
