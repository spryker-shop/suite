<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Pyz\Glue\HealthCheck;

use Spryker\Glue\HealthCheck\HealthCheckDependencyProvider as SprykerHealthCheckDependencyProvider;
use Spryker\Glue\Search\Plugin\HealthCheck\SearchHealthCheckPlugin;
use Spryker\Glue\Storage\Plugin\HealthCheck\KeyValueStoreHealthCheckPlugin;

class HealthCheckDependencyProvider extends SprykerHealthCheckDependencyProvider
{
    /**
     * @return \Spryker\Shared\HealthCheckExtension\Dependency\Plugin\HealthCheckPluginInterface[]
     */
    protected function getHealthCheckPlugins(): array
    {
        return [
            new SearchHealthCheckPlugin(),
            new KeyValueStoreHealthCheckPlugin(),
            new KeyValueStoreHealthCheckPlugin(),
        ];
    }
}
