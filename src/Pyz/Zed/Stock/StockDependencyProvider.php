<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Stock;

use Spryker\Zed\Availability\Communication\Plugin\AvailabilityHandlerPlugin;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\ProductBundle\Communication\Plugin\Stock\ProductBundleAvailabilityHandlerPlugin;
use Spryker\Zed\ProductPackagingUnit\Communication\Plugin\Stock\LeadProductStockUpdateHandlerPlugin;
use Spryker\Zed\Stock\StockDependencyProvider as SprykerStockDependencyProvider;
use Spryker\Zed\StockAddress\Communication\Plugin\Stock\StockAddressStockCollectionExpanderPlugin;
use Spryker\Zed\StockAddress\Communication\Plugin\Stock\StockAddressStockPostCreatePlugin;
use Spryker\Zed\StockAddress\Communication\Plugin\Stock\StockAddressStockPostUpdatePlugin;

class StockDependencyProvider extends SprykerStockDependencyProvider
{
    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\StockExtension\Dependency\Plugin\StockUpdateHandlerPluginInterface[]
     */
    protected function getStockUpdateHandlerPlugins(Container $container): array
    {
        return [
            new AvailabilityHandlerPlugin(),
            new ProductBundleAvailabilityHandlerPlugin(),
            new LeadProductStockUpdateHandlerPlugin(),
        ];
    }

    /**
     * @return \Spryker\Zed\StockExtension\Dependency\Plugin\StockCollectionExpanderPluginInterface[]
     */
    protected function getStockCollectionExpanderPlugins(): array
    {
        return [
            new StockAddressStockCollectionExpanderPlugin(),
        ];
    }

    /**
     * @return \Spryker\Zed\StockExtension\Dependency\Plugin\StockPostCreatePluginInterface[]
     */
    protected function getStockPostCreatePlugins(): array
    {
        return [
            new StockAddressStockPostCreatePlugin(),
        ];
    }

    /**
     * @return \Spryker\Zed\StockExtension\Dependency\Plugin\StockPostUpdatePluginInterface[]
     */
    protected function getStockPostUpdatePlugins(): array
    {
        return [
            new StockAddressStockPostUpdatePlugin(),
        ];
    }
}
