<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\MerchantCommission;

use Spryker\Zed\MerchantCommission\MerchantCommissionConfig as SprykerMerchantCommissionConfig;

class MerchantCommissionConfig extends SprykerMerchantCommissionConfig
{
    /**
     * @var list<string>
     */
    protected const EXCLUDED_MERCHANTS_FROM_COMMISSION = [
        'MER000001',
    ];

    /**
     * @return bool
     */
    public function isMerchantCommissionPriceModeForStoreCalculationEnabled(): bool
    {
        return false;
    }
}
