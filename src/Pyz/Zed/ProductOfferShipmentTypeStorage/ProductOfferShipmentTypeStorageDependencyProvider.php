<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductOfferShipmentTypeStorage;

use Spryker\Zed\MerchantProductOfferStorage\Communication\Plugin\ProductOfferShipmentTypeStorage\MerchantProductOfferShipmentTypeStorageFilterPlugin;
use Spryker\Zed\ProductOfferShipmentTypeStorage\ProductOfferShipmentTypeStorageDependencyProvider as SprykerProductOfferShipmentTypeStorageDependencyProvider;

class ProductOfferShipmentTypeStorageDependencyProvider extends SprykerProductOfferShipmentTypeStorageDependencyProvider
{
    /**
     * @return list<\Spryker\Zed\ProductOfferShipmentTypeStorageExtension\Dependency\Plugin\ProductOfferShipmentTypeStorageFilterPluginInterface>
     */
    protected function getProductOfferShipmentTypeStorageFilterPlugins(): array
    {
        return [
            new MerchantProductOfferShipmentTypeStorageFilterPlugin(),
        ];
    }
}
