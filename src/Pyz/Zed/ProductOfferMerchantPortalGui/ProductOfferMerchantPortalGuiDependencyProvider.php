<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductOfferMerchantPortalGui;

use Spryker\Zed\ProductMerchantPortalGui\Communication\Plugin\ProductOfferMerchantPortalGui\ProductApprovalStatusProductTableExpanderPlugin;
use Spryker\Zed\ProductOfferMerchantPortalGui\ProductOfferMerchantPortalGuiDependencyProvider as SprykerProductOfferMerchantPortalGuiDependencyProvider;

class ProductOfferMerchantPortalGuiDependencyProvider extends SprykerProductOfferMerchantPortalGuiDependencyProvider
{
    /**
     * @return array<\Spryker\Zed\ProductOfferMerchantPortalGuiExtension\Dependency\Plugin\ProductTableExpanderPluginInterface>
     */
    protected function getProductTableExpanderPlugins(): array
    {
        return [
            new ProductApprovalStatusProductTableExpanderPlugin(),
        ];
    }
}
