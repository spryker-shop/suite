<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\QuickOrderPage;

use Spryker\Client\PriceProductStorage\Plugin\QuickOrderPage\QuickOrderProductPriceTransferExpanderPlugin;
use Spryker\Client\ProductMeasurementUnitStorage\Plugin\QuickOrderPage\QuickOrderProductAdditionalDataTransferMeasurementUnitExpanderPlugin;
use Spryker\Client\ProductPackagingUnitStorage\Plugin\QuickOrderPage\QuickOrderItemTransferPackagingUnitExpanderPlugin;
use Spryker\Yves\PriceProduct\Plugin\QuickOrderPage\QuickOrderPagePriceColumnProviderPlugin;
use Spryker\Yves\ProductMeasurementUnit\Plugin\QuickOrderPage\QuickOrderPageMeasurementUnitColumnProviderPlugin;
use SprykerShop\Yves\MultiCartWidget\Plugin\QuickOrderPage\MultiCartListWidgetPlugin;
use SprykerShop\Yves\ProductSearchWidget\Plugin\QuickOrderPage\QuickOrderPageProductSearchWidgetPlugin;
use SprykerShop\Yves\QuickOrderPage\QuickOrderPageDependencyProvider as SprykerQuickOrderPageDependencyProvider;
use SprykerShop\Yves\ShoppingListWidget\Plugin\QuickOrderPage\ShoppingListQuickOrderFormHandlerStrategyPlugin;
use SprykerShop\Yves\ShoppingListWidget\Plugin\QuickOrderPage\ShoppingListQuickOrderPageWidgetPlugin;

class QuickOrderPageDependencyProvider extends SprykerQuickOrderPageDependencyProvider
{
    /**
     * @return string[]
     */
    protected function getQuickOrderPageWidgetPlugins(): array
    {
        return [
            MultiCartListWidgetPlugin::class, #MultiCartFeature
            ShoppingListQuickOrderPageWidgetPlugin::class, #ShoppingListFeature,
            QuickOrderPageProductSearchWidgetPlugin::class,
        ];
    }

    /**
     * @return \Spryker\Client\QuickOrderExtension\Dependency\Plugin\QuickOrderItemTransferExpanderPluginInterface[]
     */
    protected function getQuickOrderItemTransferExpanderPlugins(): array
    {
        return [
            new QuickOrderItemTransferPackagingUnitExpanderPlugin(),
        ];
    }

    /**
     * @return \SprykerShop\Yves\QuickOrderPageExtension\Dependency\Plugin\QuickOrderFormHandlerStrategyPluginInterface[]
     */
    protected function getQuickOrderFormHandlerStrategyPlugins(): array
    {
        return [
            new ShoppingListQuickOrderFormHandlerStrategyPlugin(), #ShoppingListFeature
        ];
    }

    /**
     * @return \SprykerShop\Yves\QuickOrderPageExtension\Dependency\Plugin\QuickOrderFormAdditionalDataColumnProviderPluginInterface[]
     */
    protected function getQuickOrderFormAdditionalDataColumnProviderPlugins(): array
    {
        return [
            new QuickOrderPageMeasurementUnitColumnProviderPlugin(),
            new QuickOrderPagePriceColumnProviderPlugin(),
        ];
    }

    /**
     * @return \Spryker\Client\QuickOrderExtension\Dependency\Plugin\QuickOrderProductAdditionalDataTransferExpanderPluginInterface[]
     */
    protected function getQuickOrderProductAdditionalDataTransferExpanderPlugins(): array
    {
        return [
            new QuickOrderProductAdditionalDataTransferMeasurementUnitExpanderPlugin(),
        ];
    }

    /**
     * @return \Spryker\Zed\QuickOrderExtension\Dependency\Plugin\QuickOrderProductPriceTransferExpanderPluginInterface[]
     */
    protected function getQuickOrderProductPriceTransferExpanderPlugins(): array
    {
        return [
            new QuickOrderProductPriceTransferExpanderPlugin(),
        ];
    }
}
