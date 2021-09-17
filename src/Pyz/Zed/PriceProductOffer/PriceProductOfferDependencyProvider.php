<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\PriceProductOffer;

use Spryker\Zed\PriceProductOffer\PriceProductOfferDependencyProvider as SprykerPriceProductOfferDependencyProvider;
use Spryker\Zed\PriceProductOfferVolume\Communication\Plugin\PriceProductOffer\PriceProductOfferVolumeExpanderPlugin;
use Spryker\Zed\PriceProductOfferVolume\Communication\Plugin\PriceProductOffer\PriceProductOfferVolumeExtractorPlugin;
use Spryker\Zed\PriceProductOfferVolume\Communication\Plugin\PriceProductOffer\PriceProductOfferVolumeValidatorPlugin;

class PriceProductOfferDependencyProvider extends SprykerPriceProductOfferDependencyProvider
{
    /**
     * @return array<\Spryker\Zed\PriceProductOfferExtension\Dependency\Plugin\PriceProductOfferExtractorPluginInterface>
     */
    protected function getPriceProductOfferExtractorPlugins(): array
    {
        return [
            new PriceProductOfferVolumeExtractorPlugin(),
        ];
    }

    /**
     * @return array<\Spryker\Zed\PriceProductOfferExtension\Dependency\Plugin\PriceProductOfferExpanderPluginInterface>
     */
    protected function getPriceProductOfferExpanderPlugins(): array
    {
        return [
            new PriceProductOfferVolumeExpanderPlugin(),
        ];
    }

    /**
     * @return array<\Spryker\Zed\PriceProductOfferExtension\Dependency\Plugin\PriceProductOfferValidatorPluginInterface>
     */
    protected function getPriceProductOfferValidatorPlugins(): array
    {
        return [
            new PriceProductOfferVolumeValidatorPlugin(),
        ];
    }
}
