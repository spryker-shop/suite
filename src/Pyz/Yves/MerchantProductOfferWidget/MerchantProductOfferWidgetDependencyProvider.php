<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\MerchantProductOfferWidget;

use SprykerShop\Yves\MerchantProductOfferWidget\MerchantProductOfferWidgetDependencyProvider as SprykerMerchantProductOfferWidgetDependencyProvider;
use SprykerShop\Yves\MerchantProductWidget\Plugin\MerchantProductOfferWidget\MerchantProductMerchantProductOfferCollectionExpanderPlugin;

class MerchantProductOfferWidgetDependencyProvider extends SprykerMerchantProductOfferWidgetDependencyProvider
{
    /**
     * @return array<\SprykerShop\Yves\MerchantProductOfferWidgetExtension\Dependency\Plugin\MerchantProductOfferCollectionExpanderPluginInterface>
     */
    protected function getMerchantProductOfferCollectionExpanderPlugins(): array
    {
        return [
            new MerchantProductMerchantProductOfferCollectionExpanderPlugin(),
        ];
    }
}
