<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Glue\OrdersRestApi;

use Spryker\Glue\OrdersRestApi\OrdersRestApiDependencyProvider as SprykerOrdersRestApiDependencyProvider;
use Spryker\Glue\ProductOptionsRestApi\Plugin\OrdersRestApi\ItemOrdersToRestOrderItemsAttributesOptionMapperPlugin;

class OrdersRestApiDependencyProvider extends SprykerOrdersRestApiDependencyProvider
{
    /**
     * @return \Spryker\Glue\OrdersRestApiExtension\Dependency\Plugin\ItemToRestOrderItemsAttributesMapperPluginInterface[]
     */
    protected function getItemToRestOrderItemsAttributesMapperPlugins(): array
    {
        return [
            new ItemOrdersToRestOrderItemsAttributesOptionMapperPlugin(),
        ];
    }
}
