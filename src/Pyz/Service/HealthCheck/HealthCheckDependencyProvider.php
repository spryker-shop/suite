<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Pyz\Service\HealthCheck;

use Spryker\Service\HealthCheck\HealthCheckDependencyProvider as SprykerHealthCheckDependencyProvider;
use Spryker\Service\Propel\Plugin\HealthCheck\DatabaseHealthCheckPlugin;
use Spryker\Service\Search\Plugin\HealthCheck\SearchHealthCheckPlugin;
use Spryker\Service\Session\Plugin\HealthCheck\YvesSessionHealthCheckPlugin;
use Spryker\Service\Session\Plugin\HealthCheck\ZedSessionHealthCheckPlugin;
use Spryker\Service\Storage\Plugin\HealthCheck\KeyValueStoreHealthCheckPlugin;
use Spryker\Service\ZedRequest\Plugin\HealthCheck\ZedRequestHealthCheckPlugin;

class HealthCheckDependencyProvider extends SprykerHealthCheckDependencyProvider
{
    /**
     * @return \Spryker\Service\HealthCheckExtension\Dependency\Plugin\HealthCheckPluginInterface[]
     */
    protected function getHealthCheckPlugins(): array
    {
        return [
            'ZED' => [
                new DatabaseHealthCheckPlugin(),
                new KeyValueStoreHealthCheckPlugin(),
                new SearchHealthCheckPlugin(),
                new ZedSessionHealthCheckPlugin(),
            ],
            'YVES' => [
                new KeyValueStoreHealthCheckPlugin(),
                new SearchHealthCheckPlugin(),
                new ZedRequestHealthCheckPlugin(),
                new YvesSessionHealthCheckPlugin(),
            ],
            'GLUE' => [
                new KeyValueStoreHealthCheckPlugin(),
                new SearchHealthCheckPlugin(),
                new ZedRequestHealthCheckPlugin(),
            ],
        ];
    }
}
