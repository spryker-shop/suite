<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\MultiCartPage;

use SprykerShop\Yves\MultiCartPage\MultiCartPageDependencyProvider as SprykerMultiCartPageDependencyProvider;
use SprykerShop\Yves\ProductBundleWidget\Plugin\MultiCartPage\ProductBundleItemCounterWidgetPlugin;
use SprykerShop\Yves\SharedCartWidget\Plugin\MultiCartPage\CartListPermissionGroupWidgetPlugin;

class MultiCartPageDependencyProvider extends SprykerMultiCartPageDependencyProvider
{
    /**
     * @return array
     */
    protected function getMultiCartListWidgetPlugins(): array
    {
        return [
            ProductBundleItemCounterWidgetPlugin::class, #ProductBundleFeature
            CartListPermissionGroupWidgetPlugin::class, #SharedCartWidget
        ];
    }
}
