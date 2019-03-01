<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductList;

use Spryker\Zed\ProductBundleProductListConnector\Communication\Plugin\ProductBundleProductListPreSavePlugin;
use Spryker\Zed\ProductList\ProductListDependencyProvider as SprykerProductListDependencyProvider;

class ProductListDependencyProvider extends SprykerProductListDependencyProvider
{
    /**
     * @return \Spryker\Zed\ProductListExtension\Dependency\Plugin\ProductListPreSaveInterface[]
     */
    protected function getProductListPreSavePlugins(): array
    {
        return [
            new ProductBundleProductListPreSavePlugin(),
        ];
    }
}
