<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductOfferMerchantPortalGui;

use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\GuiTableDataRequest\DateRangeFilterValueNormalizerPlugin;
use Spryker\Zed\ProductOfferMerchantPortalGui\ProductOfferMerchantPortalGuiDependencyProvider as SprykerProductOfferMerchantPortalGuiDependencyProvider;

class ProductOfferMerchantPortalGuiDependencyProvider extends SprykerProductOfferMerchantPortalGuiDependencyProvider
{
    /**
     * @return \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\GuiTableDataRequest\FilterValueNormalizerPluginInterface[]
     */
    protected function getFilterValueNormalizerPlugins(): array
    {
        return [
            new DateRangeFilterValueNormalizerPlugin(),
        ];
    }
}
