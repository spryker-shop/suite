<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\ProductOfferServicePointAvailability;

use Spryker\Client\ProductOfferServicePointAvailability\ProductOfferServicePointAvailabilityDependencyProvider as SprykerProductOfferServicePointAvailabilityDependencyProvider;
use Spryker\Client\ProductOfferShipmentTypeAvailability\Plugin\ProductOfferServicePointAvailability\ShipmentTypeProductOfferServicePointAvailabilityFilterPlugin;

class ProductOfferServicePointAvailabilityDependencyProvider extends SprykerProductOfferServicePointAvailabilityDependencyProvider
{
    /**
     * @return list<\Spryker\Client\ProductOfferServicePointAvailabilityExtension\Dependency\Plugin\ProductOfferServicePointAvailabilityFilterPluginInterface>
     */
    protected function getProductOfferServicePointAvailabilityFilterPlugins(): array
    {
        return [
            new ShipmentTypeProductOfferServicePointAvailabilityFilterPlugin(),
        ];
    }
}
