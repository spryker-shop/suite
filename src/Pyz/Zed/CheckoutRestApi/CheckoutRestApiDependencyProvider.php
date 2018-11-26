<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\CheckoutRestApi;

use Spryker\Zed\CheckoutRestApi\CheckoutRestApiDependencyProvider as SprykerCheckoutRestApiDependencyProvider;
use Spryker\Zed\CheckoutRestApi\Communication\Plugin\AddressesQuoteMapperPlugin;
use Spryker\Zed\CheckoutRestApi\Communication\Plugin\CustomerQuoteMapperPlugin;
use Spryker\Zed\PaymentsRestApi\Communication\Plugin\CheckoutRestApi\PaymentsQuoteMapperPlugin;
use Spryker\Zed\ShipmentsRestApi\Communication\Plugin\CheckoutRestApi\ShipmentQuoteMapperPlugin;

class CheckoutRestApiDependencyProvider extends SprykerCheckoutRestApiDependencyProvider
{
    /**
     * @return \Spryker\Zed\CheckoutRestApiExtension\Dependency\Plugin\QuoteMapperPluginInterface[]
     */
    protected function getQuoteMappingPlugins(): array
    {
        return [
            new ShipmentQuoteMapperPlugin(),
            new PaymentsQuoteMapperPlugin(),
            new AddressesQuoteMapperPlugin(),
            new CustomerQuoteMapperPlugin(),
        ];
    }
}
