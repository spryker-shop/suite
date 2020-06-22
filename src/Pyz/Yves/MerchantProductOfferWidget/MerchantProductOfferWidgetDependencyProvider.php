<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\MerchantProductOfferWidget;

use SprykerShop\Yves\MerchantProductOfferWidget\MerchantProductOfferWidgetDependencyProvider as SprykerShopMerchantProductOfferWidgetDependencyProvider;
use SprykerShop\Yves\MerchantProductWidget\Plugin\MerchantProductOfferWidget\MerchantProductViewCollectionExpanderPlugin;

class MerchantProductOfferWidgetDependencyProvider extends SprykerShopMerchantProductOfferWidgetDependencyProvider
{
    /**
     * @return \SprykerShop\Yves\MerchantProductOfferWidgetExtension\Dependency\Plugin\MerchantProductViewCollectionExpanderPluginInterface[]
     */
    protected function getMerchantProductViewCollectionExpanderPlugins(): array
    {
        return [
            new MerchantProductViewCollectionExpanderPlugin(),
        ];
    }
}
