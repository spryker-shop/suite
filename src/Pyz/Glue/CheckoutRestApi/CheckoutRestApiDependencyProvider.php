<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Glue\CheckoutRestApi;

use Spryker\Glue\CheckoutRestApi\CheckoutRestApiDependencyProvider as SprykerCheckoutRestApiDependencyProvider;
use Spryker\Glue\CheckoutRestApi\Plugin\SinglePaymentCheckoutRequestAttributesValidatorPlugin;
use Spryker\Glue\CompanyUsersRestApi\Plugin\CheckoutRestApi\CompanyUserCheckoutRequestExpanderPlugin;
use Spryker\Glue\PaymentsRestApi\Plugin\CheckoutRestApi\SelectedPaymentMethodCheckoutDataResponseMapperPlugin;
use Spryker\Glue\ServicePointsRestApi\Plugin\CheckoutRestApi\ServicePointCheckoutDataResponseMapperPlugin;
use Spryker\Glue\ServicePointsRestApi\Plugin\CheckoutRestApi\ServicePointCheckoutRequestAttributesValidatorPlugin;
use Spryker\Glue\ShipmentsRestApi\Plugin\CheckoutRestApi\AddressSourceCheckoutRequestValidatorPlugin;
use Spryker\Glue\ShipmentsRestApi\Plugin\CheckoutRestApi\SelectedShipmentMethodCheckoutDataResponseMapperPlugin;
use Spryker\Glue\ShipmentsRestApi\Plugin\CheckoutRestApi\ShipmentDataCheckoutRequestValidatorPlugin;
use Spryker\Glue\ShipmentTypeServicePointsRestApi\Plugin\CheckoutRestApi\ShipmentTypeServicePointCheckoutRequestAttributesValidatorPlugin;
use Spryker\Glue\ShipmentTypeServicePointsRestApi\Plugin\CheckoutRestApi\ShipmentTypeServicePointCheckoutRequestExpanderPlugin;
use Spryker\Glue\ShipmentTypesRestApi\Plugin\CheckoutRestApi\SelectedShipmentTypesCheckoutDataResponseMapperPlugin;

class CheckoutRestApiDependencyProvider extends SprykerCheckoutRestApiDependencyProvider
{
    /**
     * @return array<\Spryker\Glue\CheckoutRestApiExtension\Dependency\Plugin\CheckoutRequestAttributesValidatorPluginInterface>
     */
    protected function getCheckoutRequestAttributesValidatorPlugins(): array
    {
        return [
            new SinglePaymentCheckoutRequestAttributesValidatorPlugin(),
            new ServicePointCheckoutRequestAttributesValidatorPlugin(),
            new ShipmentTypeServicePointCheckoutRequestAttributesValidatorPlugin(),
        ];
    }

    /**
     * @return array<\Spryker\Glue\CheckoutRestApiExtension\Dependency\Plugin\CheckoutRequestValidatorPluginInterface>
     */
    protected function getCheckoutRequestValidatorPlugins(): array
    {
        return [
            new ShipmentDataCheckoutRequestValidatorPlugin(),
            new AddressSourceCheckoutRequestValidatorPlugin(),
        ];
    }

    /**
     * @return array<\Spryker\Glue\CheckoutRestApiExtension\Dependency\Plugin\CheckoutDataResponseMapperPluginInterface>
     */
    protected function getCheckoutDataResponseMapperPlugins(): array
    {
        return [
            new SelectedShipmentMethodCheckoutDataResponseMapperPlugin(),
            new SelectedPaymentMethodCheckoutDataResponseMapperPlugin(),
            new ServicePointCheckoutDataResponseMapperPlugin(),
            new SelectedShipmentTypesCheckoutDataResponseMapperPlugin(),
        ];
    }

    /**
     * @return array<\Spryker\Glue\CheckoutRestApiExtension\Dependency\Plugin\CheckoutRequestExpanderPluginInterface>
     */
    protected function getCheckoutRequestExpanderPlugins(): array
    {
        return [
            new CompanyUserCheckoutRequestExpanderPlugin(),
            new ShipmentTypeServicePointCheckoutRequestExpanderPlugin(),
        ];
    }
}
