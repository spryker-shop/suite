<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\HealthCheck;

use Pyz\Shared\HealthCheck\HealthCheckConfig;
use Spryker\Client\HealthCheck\HealthCheckDependencyProvider as SprykerHealthCheckDependencyProvider;
use Spryker\Client\Search\Plugin\HealthCheck\SearchHealthCheckPlugin;
use Spryker\Client\Session\Plugin\HealthCheck\SessionHealthCheckPlugin;
use Spryker\Client\Storage\Plugin\HealthCheck\KeyValueStoreHealthCheckPlugin;
use Spryker\Client\ZedRequest\Plugin\ZedRequestHealthCheckPlugin;

class HealthCheckDependencyProvider extends SprykerHealthCheckDependencyProvider
{
    /**
     * @return \Spryker\Shared\HealthCheckExtension\Dependency\Plugin\HealthCheckPluginInterface[]
     */
    protected function getHealthCheckPlugins(): array
    {
        return [
            HealthCheckConfig::STORAGE_SERVICE_NAME => new KeyValueStoreHealthCheckPlugin(),
            HealthCheckConfig::SEARCH_SERVICE_NAME => new SearchHealthCheckPlugin(),
            HealthCheckConfig::SESSION_SERVICE_NAME => new SessionHealthCheckPlugin(),
            HealthCheckConfig::ZED_REQUEST_SERVICE_NAME => new ZedRequestHealthCheckPlugin(),
        ];
    }
}
