<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Refund;

use Spryker\Zed\MerchantSalesOrder\Communication\Plugin\Refund\MerchantOrderTotalsRefundPostSavePlugin;
use Spryker\Zed\Refund\RefundDependencyProvider as SprykerRefundDependencyProvider;
use Spryker\Zed\SalesMerchantCommission\Communication\Plugin\Refund\MerchantCommissionRefundPostSavePlugin;

class RefundDependencyProvider extends SprykerRefundDependencyProvider
{
    /**
     * @return array<\Spryker\Zed\RefundExtension\Dependency\Plugin\RefundPostSavePluginInterface>
     */
    protected function getRefundPostSavePlugins(): array
    {
        return [
            new MerchantOrderTotalsRefundPostSavePlugin(),
            new MerchantCommissionRefundPostSavePlugin(),
        ];
    }
}
