<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\PriceProductOffer;

use Spryker\Zed\PriceProductOffer\PriceProductOfferDependencyProvider as SprykerPriceProductOfferDependencyProvider;
use Spryker\Zed\PriceProductOfferVolume\Communication\Plugin\PriceProductOffer\PriceProductOfferVolumeExtractorPlugin;

class PriceProductOfferDependencyProvider extends SprykerPriceProductOfferDependencyProvider
{
    /**
     * @return \Spryker\Zed\PriceProductOfferExtension\Dependency\Plugin\PriceProductOfferExtractorPluginInterface[]
     */
    protected function getPriceProductOfferExtractorPlugins(): array
    {
        return [
            new PriceProductOfferVolumeExtractorPlugin(),
        ];
    }
}
