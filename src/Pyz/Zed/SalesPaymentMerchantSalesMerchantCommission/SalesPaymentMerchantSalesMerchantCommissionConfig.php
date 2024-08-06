<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\SalesPaymentMerchantSalesMerchantCommission;

use Spryker\Zed\SalesPaymentMerchantSalesMerchantCommission\SalesPaymentMerchantSalesMerchantCommissionConfig as SprykerSalesPaymentMerchantSalesMerchantCommissionConfig;

class SalesPaymentMerchantSalesMerchantCommissionConfig extends SprykerSalesPaymentMerchantSalesMerchantCommissionConfig
{
    /**
     * @var array<string, array<string, bool>>
     */
    protected const TAX_DEDUCTION_ENABLED_FOR_STORE_AND_PRICE_MODE = [
        'DE' => [self::PRICE_MODE_GROSS => true, self::PRICE_MODE_NET => true],
        'AT' => [self::PRICE_MODE_GROSS => false, self::PRICE_MODE_NET => false],
        'US' => [self::PRICE_MODE_GROSS => true, self::PRICE_MODE_NET => true],
    ];
}
