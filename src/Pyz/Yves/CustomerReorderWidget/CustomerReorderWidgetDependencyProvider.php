<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\CustomerReorderWidget;

use SprykerShop\Yves\CustomerReorderWidget\CustomerReorderWidgetDependencyProvider as SprykerCustomerReorderWidgetDependencyProvider;
use SprykerShop\Yves\ProductBundleWidget\Plugin\CustomerReorderWidget\ProductBundleReorderItemFetcherPlugin;
use SprykerShop\Yves\SalesConfigurableBundleWidget\Plugin\CustomerReorder\ConfiguredBundlePostReorderPlugin;
use SprykerShop\Yves\SalesProductConfigurationWidget\Plugin\CustomerReorderWidget\ProductConfigurationReorderItemExpanderPlugin;
use SprykerShop\Yves\SalesReturnPage\Plugin\CustomerReorderWidget\RemunerationAmountReorderItemSanitizerPlugin;
use SprykerShop\Yves\SalesServicePointWidget\Plugin\CustomerReorderWidget\ServicePointReorderItemSanitizerPlugin;
use SprykerShop\Yves\ShipmentPage\Plugin\CustomerReorderWidget\ShipmentReorderItemSanitizerPlugin;

class CustomerReorderWidgetDependencyProvider extends SprykerCustomerReorderWidgetDependencyProvider
{
    /**
     * @return array<\SprykerShop\Yves\CustomerReorderWidgetExtension\Dependency\Plugin\PostReorderPluginInterface>
     */
    protected function getPostReorderPlugins(): array
    {
        return [
            new ConfiguredBundlePostReorderPlugin(),
        ];
    }

    /**
     * @return array<\SprykerShop\Yves\CustomerReorderWidgetExtension\Dependency\Plugin\ReorderItemExpanderPluginInterface>
     */
    protected function getReorderItemExpanderPlugins(): array
    {
        return [
            new ProductConfigurationReorderItemExpanderPlugin(),
        ];
    }

    /**
     * @return array<\SprykerShop\Yves\CustomerReorderWidgetExtension\Dependency\Plugin\ReorderItemSanitizerPluginInterface>
     */
    protected function getReorderItemSanitizerPlugins(): array
    {
        return [
            new RemunerationAmountReorderItemSanitizerPlugin(),
            new ShipmentReorderItemSanitizerPlugin(),
            new ServicePointReorderItemSanitizerPlugin(),
        ];
    }

    /**
     * @return array<\SprykerShop\Yves\CustomerReorderWidgetExtension\Dependency\Plugin\ReorderItemFetcherPluginInterface>
     */
    protected function getReorderItemFetcherPlugins(): array
    {
        return [
            new ProductBundleReorderItemFetcherPlugin(),
        ];
    }
}
