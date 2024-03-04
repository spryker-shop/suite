<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\CheckoutRestApi;

use Spryker\Zed\CheckoutRestApi\CheckoutRestApiDependencyProvider as SprykerCheckoutRestApiDependencyProvider;
use Spryker\Zed\ClickAndCollectExample\Communication\Plugin\CheckoutRestApi\ClickAndCollectExampleReplaceCheckoutDataValidatorPlugin;
use Spryker\Zed\ClickAndCollectExample\Communication\Plugin\CheckoutRestApi\ClickAndCollectExampleReplaceReadCheckoutDataValidatorPlugin;
use Spryker\Zed\CompanyBusinessUnitAddressesRestApi\Communication\Plugin\CheckoutRestApi\CompanyBusinessUnitAddressCheckoutDataExpanderPlugin;
use Spryker\Zed\CompanyBusinessUnitAddressesRestApi\Communication\Plugin\CheckoutRestApi\CompanyBusinessUnitAddressCheckoutDataValidatorPlugin;
use Spryker\Zed\CompanyBusinessUnitAddressesRestApi\Communication\Plugin\CheckoutRestApi\CompanyBusinessUnitAddressQuoteMapperPlugin;
use Spryker\Zed\CompanyUsersRestApi\Communication\Plugin\CheckoutRestApi\CompanyUserQuoteMapperPlugin;
use Spryker\Zed\Country\Communication\Plugin\CheckoutRestApi\CountriesCheckoutDataValidatorPlugin;
use Spryker\Zed\CustomersRestApi\Communication\Plugin\CheckoutRestApi\AddressQuoteMapperPlugin;
use Spryker\Zed\CustomersRestApi\Communication\Plugin\CheckoutRestApi\CustomerAddressCheckoutDataValidatorPlugin;
use Spryker\Zed\CustomersRestApi\Communication\Plugin\CheckoutRestApi\CustomerQuoteMapperPlugin;
use Spryker\Zed\PaymentsRestApi\Communication\Plugin\CheckoutRestApi\PaymentsQuoteMapperPlugin;
use Spryker\Zed\SalesOrderThresholdsRestApi\Communication\Plugin\CheckoutRestApi\SalesOrderThresholdReadCheckoutDataValidatorPlugin;
use Spryker\Zed\ServicePointCartsRestApi\Communication\Plugin\CheckoutRestApi\ReplaceServicePointQuoteItemsQuoteMapperPlugin;
use Spryker\Zed\ServicePointsRestApi\Communication\Plugin\CheckoutRestApi\ServicePointCheckoutDataExpanderPlugin;
use Spryker\Zed\ServicePointsRestApi\Communication\Plugin\CheckoutRestApi\ServicePointQuoteMapperPlugin;
use Spryker\Zed\ShipmentsRestApi\Communication\Plugin\CheckoutRestApi\ItemsCheckoutDataValidatorPlugin;
use Spryker\Zed\ShipmentsRestApi\Communication\Plugin\CheckoutRestApi\ItemsReadCheckoutDataValidatorPlugin;
use Spryker\Zed\ShipmentsRestApi\Communication\Plugin\CheckoutRestApi\ShipmentCheckoutDataExpanderPlugin;
use Spryker\Zed\ShipmentsRestApi\Communication\Plugin\CheckoutRestApi\ShipmentMethodCheckoutDataValidatorPlugin;
use Spryker\Zed\ShipmentsRestApi\Communication\Plugin\CheckoutRestApi\ShipmentQuoteMapperPlugin;
use Spryker\Zed\ShipmentsRestApi\Communication\Plugin\CheckoutRestApi\ShipmentsQuoteMapperPlugin;
use Spryker\Zed\ShipmentTypeServicePointsRestApi\Communication\Plugin\CheckoutRestApi\ShipmentTypeServicePointQuoteMapperPlugin;
use Spryker\Zed\ShipmentTypesRestApi\Communication\Plugin\CheckoutRestApi\ShipmentTypeCheckoutDataValidatorPlugin;
use Spryker\Zed\ShipmentTypesRestApi\Communication\Plugin\CheckoutRestApi\ShipmentTypeReadCheckoutDataValidatorPlugin;

class CheckoutRestApiDependencyProvider extends SprykerCheckoutRestApiDependencyProvider
{
    /**
     * @return array<\Spryker\Zed\CheckoutRestApiExtension\Dependency\Plugin\QuoteMapperPluginInterface>
     */
    protected function getQuoteMapperPlugins(): array
    {
        return [
            new CustomerQuoteMapperPlugin(),
            new CompanyUserQuoteMapperPlugin(),
            new AddressQuoteMapperPlugin(),
            new ShipmentQuoteMapperPlugin(),
            new CompanyBusinessUnitAddressQuoteMapperPlugin(),
            new ShipmentsQuoteMapperPlugin(),
            new ServicePointQuoteMapperPlugin(),
            new ShipmentTypeServicePointQuoteMapperPlugin(),
            new ReplaceServicePointQuoteItemsQuoteMapperPlugin(),
            new PaymentsQuoteMapperPlugin(),
        ];
    }

    /**
     * @return array<\Spryker\Zed\CheckoutRestApiExtension\Dependency\Plugin\CheckoutDataValidatorPluginInterface>
     */
    protected function getCheckoutDataValidatorPlugins(): array
    {
        return [
            new CountriesCheckoutDataValidatorPlugin(),
            new ShipmentMethodCheckoutDataValidatorPlugin(),
            new ItemsCheckoutDataValidatorPlugin(),
            new CustomerAddressCheckoutDataValidatorPlugin(),
            new CompanyBusinessUnitAddressCheckoutDataValidatorPlugin(),
            new ShipmentTypeCheckoutDataValidatorPlugin(),
            new ClickAndCollectExampleReplaceCheckoutDataValidatorPlugin(),
        ];
    }

    /**
     * @return array<\Spryker\Zed\CheckoutRestApiExtension\Dependency\Plugin\ReadCheckoutDataValidatorPluginInterface>
     */
    protected function getReadCheckoutDataValidatorPlugins(): array
    {
        return [
            new ItemsReadCheckoutDataValidatorPlugin(),
            new SalesOrderThresholdReadCheckoutDataValidatorPlugin(),
            new ShipmentTypeReadCheckoutDataValidatorPlugin(),
            new ClickAndCollectExampleReplaceReadCheckoutDataValidatorPlugin(),
        ];
    }

    /**
     * @return array<\Spryker\Zed\CheckoutRestApiExtension\Dependency\Plugin\CheckoutDataExpanderPluginInterface>
     */
    protected function getCheckoutDataExpanderPlugins(): array
    {
        return [
            new CompanyBusinessUnitAddressCheckoutDataExpanderPlugin(),
            new ShipmentCheckoutDataExpanderPlugin(),
            new ServicePointCheckoutDataExpanderPlugin(),
        ];
    }
}
