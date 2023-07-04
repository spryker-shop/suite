<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\ProductOfferStorage;

use Spryker\Client\MerchantProductStorage\Plugin\ProductOfferStorage\MerchantProductProductOfferReferenceStrategyPlugin;
use Spryker\Client\MerchantStorage\Plugin\ProductOfferStorage\MerchantProductOfferStorageExpanderPlugin;
use Spryker\Client\PriceProductOfferStorage\Plugin\ProductOfferStorage\LowestPriceProductOfferStorageCollectionSorterPlugin;
use Spryker\Client\PriceProductOfferStorage\Plugin\ProductOfferStorage\PriceProductOfferStorageExpanderPlugin;
use Spryker\Client\ProductOfferServicePointStorage\Plugin\ProductOfferStorage\ServiceProductOfferStorageExpanderPlugin;
use Spryker\Client\ProductOfferShipmentTypeStorage\Plugin\ProductOfferStorage\ShipmentTypeProductOfferStorageExpanderPlugin;
use Spryker\Client\ProductOfferStorage\Plugin\ProductOfferStorage\DefaultProductOfferReferenceStrategyPlugin;
use Spryker\Client\ProductOfferStorage\Plugin\ProductOfferStorage\ProductOfferReferenceStrategyPlugin;
use Spryker\Client\ProductOfferStorage\ProductOfferStorageDependencyProvider as SprykerProductOfferStorageDependencyProvider;
use Spryker\Client\ProductOfferStorageExtension\Dependency\Plugin\ProductOfferStorageCollectionSorterPluginInterface;

class ProductOfferStorageDependencyProvider extends SprykerProductOfferStorageDependencyProvider
{
    /**
     * @return array<\Spryker\Client\ProductOfferStorageExtension\Dependency\Plugin\ProductOfferReferenceStrategyPluginInterface>
     */
    protected function getProductOfferReferenceStrategyPlugins(): array
    {
        return [
            new ProductOfferReferenceStrategyPlugin(),
            new MerchantProductProductOfferReferenceStrategyPlugin(),
            new DefaultProductOfferReferenceStrategyPlugin(),
        ];
    }

    /**
     * @return array<\Spryker\Client\ProductOfferStorageExtension\Dependency\Plugin\ProductOfferStorageExpanderPluginInterface>
     */
    protected function getProductOfferStorageExpanderPlugins(): array
    {
        return [
            new PriceProductOfferStorageExpanderPlugin(),
            new MerchantProductOfferStorageExpanderPlugin(),
            new ServiceProductOfferStorageExpanderPlugin(),
            new ShipmentTypeProductOfferStorageExpanderPlugin(),
        ];
    }

    /**
     * @return \Spryker\Client\ProductOfferStorageExtension\Dependency\Plugin\ProductOfferStorageCollectionSorterPluginInterface
     */
    protected function createProductOfferStorageCollectionSorterPlugin(): ProductOfferStorageCollectionSorterPluginInterface
    {
        return new LowestPriceProductOfferStorageCollectionSorterPlugin();
    }
}
