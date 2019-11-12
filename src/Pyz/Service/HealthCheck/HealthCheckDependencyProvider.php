<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Service\HealthCheck;

use Spryker\Service\HealthCheck\HealthCheckDependencyProvider as SprykerHealthCheckDependencyProvider;
use Spryker\Service\Propel\Plugin\HealthCheck\DatabaseHealthCheckPlugin;
use Spryker\Service\Search\Plugin\HealthCheck\SearchHealthCheckPlugin;
use Spryker\Service\Session\Plugin\HealthCheck\ZedSessionHealthCheckPlugin;
use Spryker\Service\Storage\Plugin\HealthCheck\KeyValueStoreHealthCheckPlugin;
use Spryker\Service\ZedRequest\Plugin\HealthCheck\ZedRequestHealthCheckPlugin;

class HealthCheckDependencyProvider extends SprykerHealthCheckDependencyProvider
{
    /**
     * @return \Spryker\Service\HealthCheckExtension\Dependency\Plugin\HealthCheckPluginInterface[]
     */
    protected function getYvesHealthCheckPlugins(): array
    {
        return [
            new KeyValueStoreHealthCheckPlugin(),
            new SearchHealthCheckPlugin(),
        ];
    }

    /**
     * @return \Spryker\Service\HealthCheckExtension\Dependency\Plugin\HealthCheckPluginInterface[]
     */
    protected function getZedHealthCheckPlugins(): array
    {
        return [
            new DatabaseHealthCheckPlugin(),
            new KeyValueStoreHealthCheckPlugin(),
            new SearchHealthCheckPlugin(),
            new ZedSessionHealthCheckPlugin(),
        ];
    }

    /**
     * @return \Spryker\Service\HealthCheckExtension\Dependency\Plugin\HealthCheckPluginInterface[]
     */
    protected function getGlueHealthCheckPlugins(): array
    {
        return [
            new KeyValueStoreHealthCheckPlugin(),
            new SearchHealthCheckPlugin(),
            new ZedRequestHealthCheckPlugin(),
        ];
    }
}
