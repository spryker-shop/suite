<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ShipmentsRestApi;

use Spryker\Glue\ProductBundleCartsRestApi\Plugin\ShipmentsRestApi\CopyShipmentToProductBundleQuoteItemExpanderPlugin;
use Spryker\Zed\CompanyBusinessUnitAddressesRestApi\Communication\Plugin\ShipmentsRestApi\CompanyBusinessUnitAddressProviderStrategyPlugin;
use Spryker\Zed\CustomersRestApi\Communication\Plugin\ShipmentsRestApi\CustomerAddressProviderStrategyPlugin;
use Spryker\Zed\MerchantShipmentsRestApi\Communication\Plugin\ShipmentsRestApi\MerchantReferenceQuoteItemExpanderPlugin;
use Spryker\Zed\ShipmentsRestApi\ShipmentsRestApiDependencyProvider as SprykerShipmentsRestApiDependencyProvider;
use Spryker\Zed\ShipmentTypesRestApi\Communication\Plugin\ShipmentsRestApi\ShipmentTypeQuoteItemExpanderPlugin;

/**
 * @method \Spryker\Zed\ShipmentsRestApi\ShipmentsRestApiConfig getConfig()
 */
class ShipmentsRestApiDependencyProvider extends SprykerShipmentsRestApiDependencyProvider
{
    /**
     * @return array<\Spryker\Zed\ShipmentsRestApiExtension\Dependency\Plugin\AddressProviderStrategyPluginInterface>
     */
    protected function getAddressProviderStrategyPlugins(): array
    {
        return [
            new CompanyBusinessUnitAddressProviderStrategyPlugin(),
            new CustomerAddressProviderStrategyPlugin(),
        ];
    }

    /**
     * @return list<\Spryker\Zed\ShipmentsRestApiExtension\Dependency\Plugin\QuoteItemExpanderPluginInterface>
     */
    protected function getQuoteItemExpanderPlugins(): array
    {
        return [
            new ShipmentTypeQuoteItemExpanderPlugin(),
            new MerchantReferenceQuoteItemExpanderPlugin(),
            new CopyShipmentToProductBundleQuoteItemExpanderPlugin(),
        ];
    }
}
