<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\ResourceShare;

use Spryker\Client\ResourceShare\ResourceShareDependencyProvider as SprykerResourceShareDependencyProvider;
use Spryker\Client\SharedCart\Plugin\ResourceShare\InternalShareIsLoginRequiredResourceShareActivatorStrategyPlugin;
use Spryker\Client\SharedCart\Plugin\ResourceShare\SwitchDefaultCartResourceShareActivatorStrategyPlugin;

class ResourceShareDependencyProvider extends SprykerResourceShareDependencyProvider
{
    /**
     * @return \Spryker\Client\ResourceShareExtension\Dependency\Plugin\ResourceShareActivatorStrategyPluginInterface[]
     */
    protected function getBeforeZedResourceShareActivatorStrategyPlugins(): array
    {
        return [
            new InternalShareIsLoginRequiredResourceShareActivatorStrategyPlugin(),
        ];
    }

    /**
     * @return \Spryker\Client\ResourceShareExtension\Dependency\Plugin\ResourceShareActivatorStrategyPluginInterface[]
     */
    protected function getAfterZedResourceShareActivatorStrategyPlugins(): array
    {
        return [
            new SwitchDefaultCartResourceShareActivatorStrategyPlugin(),
        ];
    }
}
