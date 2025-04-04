<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Pyz\Zed\ProductCategoryFilterGui;

use Spryker\Zed\ProductCategoryFilterGui\ProductCategoryFilterGuiDependencyProvider as SprykerProductCategoryFilterGuiDependencyProvider;
use Spryker\Zed\StoreGui\Communication\Plugin\ProductCategoryFilterGui\StoreProductCategoryListActionViewDataExpanderPlugin;

class ProductCategoryFilterGuiDependencyProvider extends SprykerProductCategoryFilterGuiDependencyProvider
{
    /**
     * @return array<\Spryker\Zed\ProductCategoryFilterGuiExtension\Dependency\Plugin\ProductCategoryListActionViewDataExpanderPluginInterface>
     */
    protected function getProductCategoryListActionViewDataExpanderPlugins(): array
    {
        return [
            new StoreProductCategoryListActionViewDataExpanderPlugin(),
        ];
    }
}
