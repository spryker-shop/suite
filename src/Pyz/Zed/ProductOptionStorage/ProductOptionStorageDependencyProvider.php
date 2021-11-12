<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductOptionStorage;

use Spryker\Zed\MerchantProductOptionStorage\Communication\Plugin\ProductOptionStorage\MerchantProductOptionCollectionFilterPlugin;
use Spryker\Zed\ProductOptionStorage\ProductOptionStorageDependencyProvider as SprykerProductOptionStorageDependencyProvider;

class ProductOptionStorageDependencyProvider extends SprykerProductOptionStorageDependencyProvider
{
    /**
     * @return array<\Spryker\Zed\ProductOptionStorageExtension\Dependency\Plugin\ProductOptionCollectionFilterPluginInterface>
     */
    protected function getProductOptionCollectionFilterPlugins(): array
    {
        return [
            new MerchantProductOptionCollectionFilterPlugin(),
        ];
    }
}
