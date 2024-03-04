<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\PickingList;

use Spryker\Zed\PickingList\PickingListDependencyProvider as SprykerPickingListDependencyProvider;
use Spryker\Zed\PickingListMultiShipmentPickingStrategyExample\Communication\Plugin\PickingList\MultiShipmentPickingListGeneratorStrategyPlugin;
use Spryker\Zed\PickingListPushNotification\Communication\Plugin\PickingList\PushNotificationPickingListPostCreatePlugin;
use Spryker\Zed\PickingListPushNotification\Communication\Plugin\PickingList\PushNotificationPickingListPostUpdatePlugin;
use Spryker\Zed\ProductPackagingUnit\Communication\Plugin\PickingList\ProductPackagingUnitPickingListCollectionExpanderPlugin;

class PickingListDependencyProvider extends SprykerPickingListDependencyProvider
{
    /**
     * @return list<\Spryker\Zed\PickingListExtension\Dependency\Plugin\PickingListGeneratorStrategyPluginInterface>
     */
    protected function getPickingListGeneratorStrategyPlugins(): array
    {
        return [
            new MultiShipmentPickingListGeneratorStrategyPlugin(),
        ];
    }

    /**
     * @return list<\Spryker\Zed\PickingListExtension\Dependency\Plugin\PickingListCollectionExpanderPluginInterface>
     */
    protected function getPickingListCollectionExpanderPlugins(): array
    {
        return [
            new ProductPackagingUnitPickingListCollectionExpanderPlugin(),
        ];
    }

    /**
     * @return list<\Spryker\Zed\PickingListExtension\Dependency\Plugin\PickingListPostCreatePluginInterface>
     */
    protected function getPickingListPostCreatePlugins(): array
    {
        return [
            new PushNotificationPickingListPostCreatePlugin(),
        ];
    }

    /**
     * @return list<\Spryker\Zed\PickingListExtension\Dependency\Plugin\PickingListPostUpdatePluginInterface>
     */
    protected function getPickingListPostUpdatePlugins(): array
    {
        return [
            new PushNotificationPickingListPostUpdatePlugin(),
        ];
    }
}
