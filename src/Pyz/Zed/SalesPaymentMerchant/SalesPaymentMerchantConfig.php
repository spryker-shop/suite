<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\SalesPaymentMerchant;

use Spryker\Shared\Shipment\ShipmentConfig;
use Spryker\Zed\SalesPaymentMerchant\SalesPaymentMerchantConfig as SprykerSalesPaymentMerchantConfig;

class SalesPaymentMerchantConfig extends SprykerSalesPaymentMerchantConfig
{
    /**
     * @var bool
     */
    protected const ORDER_EXPENSE_INCLUDED_IN_PAYMENT_PROCESS = true;

    /**
     * @var array<string, list<string>>
     */
    protected const EXCLUDED_EXPENSE_TYPES_FOR_STORE = [
        'AT' => [ShipmentConfig::SHIPMENT_EXPENSE_TYPE],
    ];
}
