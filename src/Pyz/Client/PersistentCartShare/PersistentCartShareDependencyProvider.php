<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\PersistentCartShare;

use Spryker\Client\PersistentCartShare\PersistentCartShareDependencyProvider as SprykerPersistentCartShareDependencyProvider;
use Spryker\Client\SharedCart\Plugin\FullAccessCartShareOptionPlugin;
use Spryker\Client\SharedCart\Plugin\PreviewCartShareOptionPlugin;
use Spryker\Client\SharedCart\Plugin\ReadOnlyCartShareOptionPlugin;

class PersistentCartShareDependencyProvider extends SprykerPersistentCartShareDependencyProvider
{
    /**
     * @return \Spryker\Client\PersistentCartShareExtension\Dependency\Plugin\CartShareOptionPluginInterface[]
     */
    protected function getCartShareOptionPlugins(): array
    {
        return [
            new PreviewCartShareOptionPlugin(), #PersistentCartShareFeature
            new ReadOnlyCartShareOptionPlugin(), #SharedCartFeature
            new FullAccessCartShareOptionPlugin(), #SharedCartFeature
        ];
    }
}
