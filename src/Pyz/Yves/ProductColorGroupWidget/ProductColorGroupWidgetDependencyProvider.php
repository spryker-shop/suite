<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Pyz\Yves\ProductColorGroupWidget;

use SprykerShop\Yves\CartPage\Plugin\ProductColorGroupWidget\AddToCartUrlProductViewExpanderPlugin;
use SprykerShop\Yves\ProductColorGroupWidget\ProductColorGroupWidgetDependencyProvider as SprykerShopProductColorGroupWidgetDependencyProvider;
use SprykerShop\Yves\ProductReviewWidget\Plugin\ProductColorGroupWidget\ProductReviewPageProductViewExpanderPlugin;

class ProductColorGroupWidgetDependencyProvider extends SprykerShopProductColorGroupWidgetDependencyProvider
{
    /**
     * @return \SprykerShop\Yves\ProductColorGroupWidgetExtension\Dependency\Plugin\ProductViewExpanderPluginInterface[]
     */
    protected function getProductViewExpanderPlugins(): array
    {
        return [
            new AddToCartUrlProductViewExpanderPlugin(),
            new ProductReviewPageProductViewExpanderPlugin(),
        ];
    }
}
