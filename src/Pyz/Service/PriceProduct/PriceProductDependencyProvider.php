<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Service\PriceProduct;

use Spryker\Service\PriceProduct\PriceProductDependencyProvider as SprykerPriceProductDependencyProvider;
use Spryker\Service\PriceProductOfferStorage\Plugin\PriceProduct\ProductOfferPriceProductFilterPlugin;
use Spryker\Service\PriceProductOfferVolume\Plugin\PriceProductOffer\PriceProductOfferVolumeFilterPlugin;
use Spryker\Service\PriceProductVolume\Plugin\PriceProductExtension\PriceProductVolumeFilterPlugin;
use Spryker\Service\ProductConfigurationStorage\Plugin\PriceProduct\ProductConfigurationPriceProductFilterPlugin;
use Spryker\Service\ProductConfigurationStorage\Plugin\PriceProduct\ProductConfigurationPriceProductVolumeFilterPlugin;

class PriceProductDependencyProvider extends SprykerPriceProductDependencyProvider
{
    /**
     * {@inheritDoc}
     *
     * @return \Spryker\Service\PriceProductExtension\Dependency\Plugin\PriceProductFilterPluginInterface[]
     */
    protected function getPriceProductDecisionPlugins(): array
    {
        return array_merge([
            /**
             * ProductOfferPriceProductFilterPlugin should be the first, otherwise other plugins might filter out the prices actually belonging to the offer.
             */
            new ProductOfferPriceProductFilterPlugin(),

            new ProductConfigurationPriceProductFilterPlugin(),
            new ProductConfigurationPriceProductVolumeFilterPlugin(),
            new PriceProductOfferVolumeFilterPlugin(),
            new PriceProductVolumeFilterPlugin(),
        ], parent::getPriceProductDecisionPlugins());
    }
}
