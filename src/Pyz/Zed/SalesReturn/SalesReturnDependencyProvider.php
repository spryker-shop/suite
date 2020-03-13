<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\SalesReturn;

use Spryker\Zed\SalesReturn\Communication\Plugin\SalesReturn\OutdatedOrderItemReturnPolicyPlugin;
use Spryker\Zed\SalesReturn\SalesReturnDependencyProvider as SprykerSalesReturnDependencyProvider;

class SalesReturnDependencyProvider extends SprykerSalesReturnDependencyProvider
{
    /**
     * @return \Spryker\Zed\SalesReturnExtension\Dependency\Plugin\ReturnPolicyPluginInterface[]
     */
    protected function getReturnPolicyPlugins(): array
    {
        return [
            new OutdatedOrderItemReturnPolicyPlugin(),
        ];
    }
}
