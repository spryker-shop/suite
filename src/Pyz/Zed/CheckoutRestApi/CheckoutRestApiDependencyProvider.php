<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\CheckoutRestApi;

use Spryker\Zed\CheckoutRestApi\CheckoutRestApiDependencyProvider as SprykerCheckoutRestApiDependencyProvider;
use Spryker\Zed\CheckoutRestApi\Communication\Plugin\AddressesQuoteMappingPlugin;
use Spryker\Zed\CheckoutRestApi\Communication\Plugin\CustomerQuoteMappingPlugin;
use Spryker\Zed\CheckoutRestApi\Communication\Plugin\PaymentsQuoteMappingPlugin;
use Spryker\Zed\CheckoutRestApi\Communication\Plugin\ShipmentQuoteMappingPlugin;
use Spryker\Zed\Kernel\Container;

class CheckoutRestApiDependencyProvider extends SprykerCheckoutRestApiDependencyProvider
{
    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\CheckoutRestApiExtension\Dependency\Plugin\QuoteMappingPluginInterface[]
     */
    protected function getQuoteMappingPlugins(Container $container): array
    {
        return [
            new ShipmentQuoteMappingPlugin(),
            new PaymentsQuoteMappingPlugin(),
            new AddressesQuoteMappingPlugin(),
            new CustomerQuoteMappingPlugin(),
        ];
    }
}
