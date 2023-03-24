<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\PickingList;

use Spryker\Zed\PickingList\PickingListDependencyProvider as SprykerPickingListDependencyProvider;
use Spryker\Zed\PickingListMultiShipmentPickingStrategyExample\Communication\Plugin\PickingList\MultiShipmentPickingListGeneratorStrategyPlugin;

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
}
