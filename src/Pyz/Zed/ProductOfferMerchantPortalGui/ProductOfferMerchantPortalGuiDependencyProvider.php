<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductOfferMerchantPortalGui;

use Spryker\Zed\ProductMerchantPortalGui\Communication\Plugin\ProductOfferMerchantPortalGui\ProductApprovalStatusProductTableExpanderPlugin;
use Spryker\Zed\ProductOfferMerchantPortalGui\ProductOfferMerchantPortalGuiDependencyProvider as SprykerProductOfferMerchantPortalGuiDependencyProvider;
use Spryker\Zed\ProductOfferServicePointMerchantPortalGui\Communication\Plugin\ProductOfferMerchantPortalGui\ServiceProductOfferFormExpanderPlugin;
use Spryker\Zed\ProductOfferServicePointMerchantPortalGui\Communication\Plugin\ProductOfferMerchantPortalGui\ServiceProductOfferFormViewExpanderPlugin;
use Spryker\Zed\ProductOfferShipmentTypeMerchantPortalGui\Communication\Plugin\ProductOfferMerchantPortalGui\ShipmentTypeProductOfferFormExpanderPlugin;
use Spryker\Zed\ProductOfferShipmentTypeMerchantPortalGui\Communication\Plugin\ProductOfferMerchantPortalGui\ShipmentTypeProductOfferFormViewExpanderPlugin;

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

    /**
     * @return list<\Spryker\Zed\ProductOfferMerchantPortalGuiExtension\Dependency\Plugin\ProductOfferFormExpanderPluginInterface>
     */
    protected function getProductOfferFormExpanderPlugins(): array
    {
        return [
            new ShipmentTypeProductOfferFormExpanderPlugin(),
            new ServiceProductOfferFormExpanderPlugin(),
        ];
    }

    /**
     * @return list<\Spryker\Zed\ProductOfferMerchantPortalGuiExtension\Dependency\Plugin\ProductOfferFormViewExpanderPluginInterface>
     */
    protected function getProductOfferFormViewExpanderPlugins(): array
    {
        return [
            new ServiceProductOfferFormViewExpanderPlugin(),
            new ShipmentTypeProductOfferFormViewExpanderPlugin(),
        ];
    }
}
