<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\PriceProductOfferStorage;

use Spryker\Client\PriceProductOfferStorage\PriceProductOfferStorageDependencyProvider as SprykerPriceProductOfferStorageDependencyProvider;
use Spryker\Client\PriceProductOfferVolume\Plugin\PriceProductOfferStorage\PriceProductOfferVolumeExtractorPlugin;

class PriceProductOfferStorageDependencyProvider extends SprykerPriceProductOfferStorageDependencyProvider
{
    /**
     * @return array<\Spryker\Client\PriceProductOfferStorageExtension\Dependency\Plugin\PriceProductOfferStoragePriceExtractorPluginInterface>
     */
    protected function getPriceProductOfferStoragePriceExtractorPlugins(): array
    {
        return [
            new PriceProductOfferVolumeExtractorPlugin(),
        ];
    }
}
