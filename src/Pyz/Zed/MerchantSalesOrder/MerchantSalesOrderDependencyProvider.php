<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\MerchantSalesOrder;

use Spryker\Zed\MerchantOms\Communication\Plugin\MerchantSalesOrder\EventTriggerMerchantOrderPostCreatePlugin;
use Spryker\Zed\MerchantSalesOrder\MerchantSalesOrderDependencyProvider as SprykerMerchantSalesOrderDependencyProvider;

class MerchantSalesOrderDependencyProvider extends SprykerMerchantSalesOrderDependencyProvider
{
    /**
     * @return array
     */
    protected function getMerchantOrderPostCreatePlugins(): array
    {
        return [
            new EventTriggerMerchantOrderPostCreatePlugin(),
        ];
    }
}
