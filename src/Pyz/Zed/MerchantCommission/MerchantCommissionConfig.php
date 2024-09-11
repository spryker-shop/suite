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
     * @uses \Spryker\Shared\Calculation\CalculationPriceMode::PRICE_MODE_NET
     *
     * @var string
     */
    protected const PRICE_MODE_NET = 'NET_MODE';

    /**
     * @uses \Spryker\Shared\Calculation\CalculationPriceMode::PRICE_MODE_GROSS
     *
     * @var string
     */
    protected const PRICE_MODE_GROSS = 'GROSS_MODE';

    /**
     * @var array<string, string>
     */
    protected const MERCHANT_COMMISSION_PRICE_MODE_PER_STORE = [
        'DE' => self::PRICE_MODE_GROSS,
        'AT' => self::PRICE_MODE_NET,
        'US' => self::PRICE_MODE_GROSS,
    ];

    /**
     * @var list<string>
     */
    protected const EXCLUDED_MERCHANTS_FROM_COMMISSION = [
        'MER000001',
    ];
}
