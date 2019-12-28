<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\AvailabilityStorage;

use Spryker\Client\AvailabilityStorage\AvailabilityStorageDependencyProvider as SprykerAvailabilityStorageDependencyProvider;
use Spryker\Client\ProductOfferAvailabilityStorage\Communication\Plugin\AvailabilityStorage\ProductOfferAvailabilityStorageProviderPlugin;

class AvailabilityStorageDependencyProvider extends SprykerAvailabilityStorageDependencyProvider
{
    /**
     * @return \Spryker\Client\AvailabilityStorageExtension\Dependency\Plugin\AvailabilityProviderStoragePluginInterface[]
     */
    public function getAvailabilityStorageStrategyPlugins(): array
    {
        return [
            new ProductOfferAvailabilityStorageProviderPlugin(),
        ];
    }
}
