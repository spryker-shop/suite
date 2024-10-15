<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\SalesMerchantCommission;

use Spryker\Zed\MerchantSalesOrderSalesMerchantCommission\Communication\Plugin\SalesMerchantCommission\UpdateMerchantCommissionTotalsPostRefundMerchantCommissionPlugin;
use Spryker\Zed\SalesMerchantCommission\SalesMerchantCommissionDependencyProvider as SprykerSalesMerchantCommissionDependencyProvider;

class SalesMerchantCommissionDependencyProvider extends SprykerSalesMerchantCommissionDependencyProvider
{
    /**
     * @return list<\Spryker\Zed\SalesMerchantCommissionExtension\Dependency\Plugin\PostRefundMerchantCommissionPluginInterface>
     */
    protected function getPostRefundMerchantCommissionPlugins(): array
    {
        return [
            new UpdateMerchantCommissionTotalsPostRefundMerchantCommissionPlugin(),
        ];
    }
}
