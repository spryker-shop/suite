<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Glue\CartsRestApi;

use Spryker\Glue\CartsRestApi\CartsRestApiDependencyProvider as SprykerCartsRestApiDependencyProvider;
use Spryker\Glue\CompanyUsersRestApi\Plugin\CartsRestApi\CompanyUserQuoteCustomerExpanderPlugin;

class CartsRestApiDependencyProvider extends SprykerCartsRestApiDependencyProvider
{
    /**
     * @return \Spryker\Glue\CartsRestApiExtension\Dependency\Plugin\QuoteCustomerExpanderPluginInterface[]
     */
    protected function getQuoteCustomerExpanderPlugins(): array
    {
        return [
            new CompanyUserQuoteCustomerExpanderPlugin(),
        ];
    }
}
