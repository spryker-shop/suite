<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\AvailabilityStorage;

use Spryker\Client\AvailabilityStorage\AvailabilityStorageDependencyProvider as SprykerAvailabilityStorageDependencyProvider;
use Spryker\Client\ProductConfigurationStorage\Plugin\AvailabilityStorage\ProductConfigurationAvailabilityStorageStrategyPlugin;
use Spryker\Client\ProductOfferAvailabilityStorage\Communication\Plugin\AvailabilityStorage\ProductOfferAvailabilityStorageStrategyPlugin;

class AvailabilityStorageDependencyProvider extends SprykerAvailabilityStorageDependencyProvider
{
    /**
     * @return \Spryker\Client\AvailabilityStorageExtension\Dependency\Plugin\AvailabilityStorageStrategyPluginInterface[]
     */
    public function getAvailabilityStorageStrategyPlugins(): array
    {
        return [
            new ProductConfigurationAvailabilityStorageStrategyPlugin(),
            new ProductOfferAvailabilityStorageStrategyPlugin(),
        ];
    }
}
