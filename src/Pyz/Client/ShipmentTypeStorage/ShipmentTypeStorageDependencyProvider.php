<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\ShipmentTypeStorage;

use Spryker\Client\ProductOfferShipmentTypeStorage\Plugin\ShipmentTypeStorage\ShipmentTypeProductOfferAvailableShipmentTypeFilterPlugin;
use Spryker\Client\ShipmentTypeStorage\ShipmentTypeStorageDependencyProvider as SprykerShipmentTypeStorageDependencyProvider;

class ShipmentTypeStorageDependencyProvider extends SprykerShipmentTypeStorageDependencyProvider
{
    /**
     * @return array<\Spryker\Client\ShipmentTypeStorageExtension\Dependency\Plugin\AvailableShipmentTypeFilterPluginInterface>
     */
    protected function getAvailableShipmentTypeFilterPlugins(): array
    {
        return [
            new ShipmentTypeProductOfferAvailableShipmentTypeFilterPlugin(),
        ];
    }
}
