<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\ResourceShare;

use Spryker\Client\ResourceShare\ResourceShareDependencyProvider as SprykerResourceShareDependencyProvider;

class ResourceShareDependencyProvider extends SprykerResourceShareDependencyProvider
{
    /**
     * @return \Spryker\Zed\ResourceShareExtension\Dependency\Plugin\ResourceShareActivatorStrategyPluginInterface[]
     */
    protected function getResourceShareResourceDataExpanderStrategyPlugins(): array
    {
        return [];
    }
}
