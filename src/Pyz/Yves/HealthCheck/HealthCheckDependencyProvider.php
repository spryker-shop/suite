<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Pyz\Yves\HealthCheck;

use Spryker\Yves\HealthCheck\HealthCheckDependencyProvider as SprykerHealthCheckDependencyProvider;
use Spryker\Yves\Search\Plugin\HealthCheck\SearchHealthCheckPlugin;
use Spryker\Yves\Session\Plugin\HealthCheck\SessionHealthCheckPlugin;
use Spryker\Yves\Storage\Plugin\HealthCheck\KeyValueStoreHealthCheckPlugin;
use Spryker\Yves\ZedRequest\Plugin\HealthCheck\ZedRequestHealthCheckPlugin;

class HealthCheckDependencyProvider extends SprykerHealthCheckDependencyProvider
{
    /**
     * @return \Spryker\Shared\HealthCheckExtension\Dependency\Plugin\HealthCheckPluginInterface[]
     */
    protected function getHealthCheckPlugins(): array
    {
        return [
            new SessionHealthCheckPlugin(),
            new SearchHealthCheckPlugin(),
            new KeyValueStoreHealthCheckPlugin(),
            new ZedRequestHealthCheckPlugin(),
        ];
    }
}
