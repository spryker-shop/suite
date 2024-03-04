<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Glue\ShipmentsRestApi;

use Spryker\Glue\CompanyBusinessUnitAddressesRestApi\Plugin\ShipmentsRestApi\CompanyBusinessUnitAddressSourceCheckerPlugin;
use Spryker\Glue\CompanyBusinessUnitAddressesRestApi\Plugin\ShipmentsRestApi\CompanyBusinessUnitUuidRestAddressResponseMapperPlugin;
use Spryker\Glue\CustomersRestApi\Plugin\ShipmentsRestApi\CustomerAddressSourceCheckerPlugin;
use Spryker\Glue\ShipmentsRestApi\ShipmentsRestApiDependencyProvider as SprykerShipmentsRestApiDependencyProvider;
use Spryker\Glue\ShipmentTypeServicePointsRestApi\Plugin\ShipmentsRestApi\MultiShipmentTypeServicePointShippingAddressValidationStrategyPlugin;
use Spryker\Glue\ShipmentTypeServicePointsRestApi\Plugin\ShipmentsRestApi\SingleShipmentTypeServicePointShippingAddressValidationStrategyPlugin;

class ShipmentsRestApiDependencyProvider extends SprykerShipmentsRestApiDependencyProvider
{
    /**
     * @return array<\Spryker\Glue\ShipmentsRestApiExtension\Dependency\Plugin\AddressSourceCheckerPluginInterface>
     */
    protected function getAddressSourceCheckerPlugins(): array
    {
        return [
            new CompanyBusinessUnitAddressSourceCheckerPlugin(),
            new CustomerAddressSourceCheckerPlugin(),
        ];
    }

    /**
     * @return list<\Spryker\Glue\ShipmentsRestApiExtension\Dependency\Plugin\ShippingAddressValidationStrategyPluginInterface>
     */
    protected function getShippingAddressValidationStrategyPlugins(): array
    {
        return [
            new MultiShipmentTypeServicePointShippingAddressValidationStrategyPlugin(),
            new SingleShipmentTypeServicePointShippingAddressValidationStrategyPlugin(),
        ];
    }

    /**
     * @return list<\Spryker\Glue\ShipmentsRestApiExtension\Dependency\Plugin\RestAddressResponseMapperPluginInterface>
     */
    protected function getRestAddressResponseMapperPlugins(): array
    {
        return [
            new CompanyBusinessUnitUuidRestAddressResponseMapperPlugin(),
        ];
    }
}
