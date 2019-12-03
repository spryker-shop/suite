<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\HealthCheck;

use Spryker\Shared\HealthCheck\HealthCheckConfig;
use Spryker\Zed\HealthCheck\HealthCheckDependencyProvider as SprykerHealthCheckDependencyProvider;
use Spryker\Zed\Propel\Communication\Plugin\HealthCheck\DatabaseHealthCheckPlugin;
use Spryker\Zed\Search\Communication\Plugin\HealthCheck\SearchHealthCheckPlugin;
use Spryker\Zed\Session\Communication\Plugin\HealthCheck\SessionHealthCheckPlugin;
use Spryker\Zed\Storage\Communication\Plugin\HealthCheck\KeyValueStoreHealthCheckPlugin;

class HealthCheckDependencyProvider extends SprykerHealthCheckDependencyProvider
{
    /**
     * @return \Spryker\Shared\HealthCheckExtension\Dependency\Plugin\HealthCheckPluginInterface[]
     */
    protected function getHealthCheckPlugins(): array
    {
        return [
            HealthCheckConfig::SESSION_SERVICE_NAME => new SessionHealthCheckPlugin(),
            HealthCheckConfig::STORAGE_SERVICE_NAME => new KeyValueStoreHealthCheckPlugin(),
            HealthCheckConfig::SEARCH_SERVICE_NAME => new SearchHealthCheckPlugin(),
            HealthCheckConfig::DATABASE_SERVICE_NAME => new DatabaseHealthCheckPlugin(),
        ];
    }
}
