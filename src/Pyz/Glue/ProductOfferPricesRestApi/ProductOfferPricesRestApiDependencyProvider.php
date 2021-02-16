<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Glue\ProductOfferPricesRestApi;

use Spryker\Glue\PriceProductOfferVolumesRestApi\Plugin\RestProductOfferPricesAttributesMapperPlugin;
use Spryker\Glue\ProductOfferPricesRestApi\ProductOfferPricesRestApiDependencyProvider as SprykerProductPricesRestApiDependencyProvider;

class ProductOfferPricesRestApiDependencyProvider extends SprykerProductPricesRestApiDependencyProvider
{
    /**
     * @return \Spryker\Glue\ProductOfferPricesRestApiExtension\Dependency\Plugin\RestProductOfferPricesAttributesMapperPluginInterface[]
     */
    protected function getRestProductOfferPricesAttributesMapperPlugins(): array
    {
        return [
            new RestProductOfferPricesAttributesMapperPlugin(),
        ];
    }
}
