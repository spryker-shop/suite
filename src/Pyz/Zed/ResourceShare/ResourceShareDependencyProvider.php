<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ResourceShare;

use Spryker\Zed\PersistentCartShare\Communication\Plugin\CartPreviewActivatorStrategyPlugin;
use Spryker\Zed\PersistentCartShare\Communication\Plugin\PersistentCartShareResourceDataExpanderStrategyPlugin;
use Spryker\Zed\ResourceShare\ResourceShareDependencyProvider as SprykerResourceShareDependencyProvider;
use Spryker\Zed\SharedCart\Communication\Plugin\ResourceShare\SharedCartActivatorStrategyPlugin;
use Spryker\Zed\SharedCart\Communication\Plugin\ResourceShare\SharedCartDataExpanderStrategyPlugin;

class ResourceShareDependencyProvider extends SprykerResourceShareDependencyProvider
{
    /**
     * @return \Spryker\Zed\ResourceShareExtension\Dependency\Plugin\ResourceShareActivatorStrategyPluginInterface[]
     */
    protected function getResourceShareActivatorStrategyPlugins(): array
    {
        return [
            new CartPreviewActivatorStrategyPlugin(),
            new SharedCartActivatorStrategyPlugin(),
        ];
    }

    /**
     * @return \Spryker\Zed\ResourceShareExtension\Dependency\Plugin\ResourceShareResourceDataExpanderStrategyPluginInterface[]
     */
    protected function getResourceShareResourceDataExpanderStrategyPlugins(): array
    {
        return [
            new PersistentCartShareResourceDataExpanderStrategyPlugin(),
            new SharedCartDataExpanderStrategyPlugin(),
        ];
    }
}
