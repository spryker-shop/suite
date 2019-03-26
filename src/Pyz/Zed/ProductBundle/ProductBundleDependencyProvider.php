<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductBundle;

use Spryker\Zed\ProductBundle\ProductBundleDependencyProvider as SprykerProductBundleDependencyProvider;
use Spryker\Zed\ProductDiscontinuedProductBundleConnector\Communication\Plugin\ProductBundle\DiscontinuedProductBundlePostSavePlugin;

class ProductBundleDependencyProvider extends SprykerProductBundleDependencyProvider
{
    /**
     * @return \Spryker\Zed\ProductBundleExtension\Dependency\Plugin\ProductBundlePostSavePluginInterface[]
     */
    protected function getProductBundlePostSavePlugins(): array
    {
        return [
            //new DiscontinuedProductBundlePostSavePlugin(),
        ];
    }
}
