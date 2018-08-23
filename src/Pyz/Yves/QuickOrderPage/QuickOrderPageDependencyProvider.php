<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\QuickOrderPage;

use Spryker\Client\ProductPackagingUnitStorage\Plugin\QuickOrderPage\QuickOrderItemTransferPackagingUnitExpanderPlugin;
use SprykerShop\Yves\MultiCartWidget\Plugin\QuickOrderPage\MultiCartListWidgetPlugin;
use SprykerShop\Yves\QuickOrderPage\QuickOrderPageDependencyProvider as SprykerQuickOrderPageDependencyProvider;

class QuickOrderPageDependencyProvider extends SprykerQuickOrderPageDependencyProvider
{
    /**
     * @return string[]
     */
    protected function getQuickOrderPageWidgetPlugins(): array
    {
        return [
            MultiCartListWidgetPlugin::class, #MultiCartFeature
        ];
    }

    /**
     * @return \Spryker\Zed\QuickOrderExtension\Dependency\Plugin\QuickOrderItemTransferExpanderPluginInterface[]
     */
    protected function getQuickOrderItemTransferExpanderPlugins(): array
    {
        return [
            new QuickOrderItemTransferPackagingUnitExpanderPlugin(),
        ];
    }
}
