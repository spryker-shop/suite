<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Glue\Console;

use Spryker\Glue\Console\ConsoleDependencyProvider as SprykerConsoleDependencyProvider;
use Spryker\Glue\Kernel\Container;
use SprykerSdk\Glue\Benchmark\Plugin\Console\BenchmarkRunConsole;

class ConsoleDependencyProvider extends SprykerConsoleDependencyProvider
{
    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Symfony\Component\Console\Command\Command[]
     */
    protected function getConsoleCommands(Container $container): array
    {
        $consoleCommands = [];

        if (class_exists(BenchmarkRunConsole::class)) {
            $consoleCommands[] = new BenchmarkRunConsole();
        }

        return $consoleCommands;
    }
}
