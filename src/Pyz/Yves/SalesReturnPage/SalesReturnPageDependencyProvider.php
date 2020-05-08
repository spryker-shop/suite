<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\SalesReturnPage;

use SprykerShop\Yves\ProductBundleWidget\Plugin\SalesReturnPage\ProductBundleSalesReturnPageFormExpanderPlugin;
use SprykerShop\Yves\SalesReturnPage\SalesReturnPageDependencyProvider as SprykerSalesReturnPageDependencyProvider;

class SalesReturnPageDependencyProvider extends SprykerSalesReturnPageDependencyProvider
{
    /**
     * @return \SprykerShop\Yves\SalesReturnPageExtension\Dependency\Plugin\SalesReturnPageFormExpanderPluginInterface[]
     */
    protected function getSalesReturnPageFormPlugins(): array
    {
        return [
            new ProductBundleSalesReturnPageFormExpanderPlugin(),
        ];
    }
}
