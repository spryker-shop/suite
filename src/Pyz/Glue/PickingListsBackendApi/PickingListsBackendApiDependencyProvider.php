<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Glue\PickingListsBackendApi;

use Spryker\Glue\PickingListsBackendApi\PickingListsBackendApiDependencyProvider as SprykerPickingListsBackendApiDependencyProvider;
use Spryker\Glue\ProductPackagingUnitsBackendApi\Plugin\PickingListsBackendApi\ProductPackagingUnitApiPickingListItemsAttributesMapperPlugin;

class PickingListsBackendApiDependencyProvider extends SprykerPickingListsBackendApiDependencyProvider
{
    /**
     * @return list<\Spryker\Glue\PickingListsBackendApiExtension\Dependency\Plugin\ApiPickingListItemsAttributesMapperPluginInterface>
     */
    protected function getApiPickingListItemsAttributesMapperPlugins(): array
    {
        return [
            new ProductPackagingUnitApiPickingListItemsAttributesMapperPlugin(),
        ];
    }
}
