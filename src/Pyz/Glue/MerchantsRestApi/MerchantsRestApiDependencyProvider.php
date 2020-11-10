<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Glue\MerchantsRestApi;

use Spryker\Glue\MerchantCategoriesRestApi\Plugin\MerchantsRestApi\MerchantCategoryMerchantRestAttributesMapperPlugin;
use Spryker\Glue\MerchantsRestApi\MerchantsRestApiDependencyProvider as SprykerMerchantsRestApiDependencyProvider;

class MerchantsRestApiDependencyProvider extends SprykerMerchantsRestApiDependencyProvider
{
    /**
     * @return \Spryker\Glue\MerchantsRestApiExtension\Dependency\Plugin\MerchantRestAttributesMapperPluginInterface[]
     */
    public function getMerchantRestAttributesMapperPlugins(): array
    {
        return [
            new MerchantCategoryMerchantRestAttributesMapperPlugin(),
        ];
    }
}
