<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Glue\Console;

use Spryker\Glue\Console\ConsoleDependencyProvider as SprykerConsoleDependencyProvider;
use Spryker\Glue\GlueApplication\Plugin\Console\ControllerCacheCollectorConsole;
use Spryker\Glue\GlueApplication\Plugin\Console\RouterDebugGlueApplicationConsole;
use Spryker\Glue\GlueBackendApiApplication\Plugin\Console\RouterCacheWarmUpConsole as BackendRouterCacheWarmUpConsole;
use Spryker\Glue\GlueStorefrontApiApplication\Plugin\Console\RouterCacheWarmUpConsole as StorefrontRouterCacheWarmUpConsole;
use Spryker\Glue\Kernel\Container;

class ConsoleDependencyProvider extends SprykerConsoleDependencyProvider
{
    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return array<\Symfony\Component\Console\Command\Command>
     */
    protected function getConsoleCommands(Container $container): array
    {
        return [
            new StorefrontRouterCacheWarmUpConsole(),
            new BackendRouterCacheWarmUpConsole(),
            new ControllerCacheCollectorConsole(),
            new RouterDebugGlueApplicationConsole(),
        ];
    }
}
