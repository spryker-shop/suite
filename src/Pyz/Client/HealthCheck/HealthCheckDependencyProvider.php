<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\HealthCheck;

use Spryker\Client\HealthCheck\HealthCheckDependencyProvider as SprykerHealthCheckDependencyProvider;
use Spryker\Client\HealthCheck\Plugin\ZedRequestHealthCheckPlugin;
use Spryker\Client\Search\Plugin\HealthCheck\SearchHealthCheckPlugin;
use Spryker\Client\Session\Plugin\HealthCheck\SessionHealthCheckPlugin;
use Spryker\Client\Storage\Plugin\HealthCheck\KeyValueStoreHealthCheckPlugin;

class HealthCheckDependencyProvider extends SprykerHealthCheckDependencyProvider
{
    /**
     * @return \Spryker\Shared\HealthCheckExtension\Dependency\Plugin\HealthCheckPluginInterface[]
     */
    protected function getHealthCheckPlugins(): array
    {
        return [
            new KeyValueStoreHealthCheckPlugin(),
            new SearchHealthCheckPlugin(),
            new SessionHealthCheckPlugin(),
            new ZedRequestHealthCheckPlugin(),
        ];
    }
}
