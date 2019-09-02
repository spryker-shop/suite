<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\ProductReplacementForWidget;

use SprykerShop\Yves\ProductReplacementForWidget\ProductReplacementForWidgetConfig as SprykerShopProductReplacementForWidgetConfig;

class ProductReplacementForWidgetConfig extends SprykerShopProductReplacementForWidgetConfig
{
    /**
     * @return bool
     */
    public function isShowReplacementForNotAvailableProductsOnly(): bool
    {
        return true;
    }
}
