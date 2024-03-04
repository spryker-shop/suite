<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Glue\PickingListsBackendApi;

use Spryker\Glue\PickingListsBackendApi\PickingListsBackendApiDependencyProvider as SprykerPickingListsBackendApiDependencyProvider;
use Spryker\Glue\ProductPackagingUnitsBackendApi\Plugin\PickingListsBackendApi\ProductPackagingUnitPickingListItemsBackendApiAttributesMapperPlugin;

class PickingListsBackendApiDependencyProvider extends SprykerPickingListsBackendApiDependencyProvider
{
    /**
     * @return list<\Spryker\Glue\PickingListsBackendApiExtension\Dependency\Plugin\PickingListItemsBackendApiAttributesMapperPluginInterface>
     */
    protected function getPickingListItemsBackendApiAttributesMapperPlugins(): array
    {
        return [
            new ProductPackagingUnitPickingListItemsBackendApiAttributesMapperPlugin(),
        ];
    }
}
