<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\HealthCheck;

use Spryker\Zed\HealthCheck\HealthCheckConfig as SprykerHealthCheckConfig;

class HealthCheckConfig extends SprykerHealthCheckConfig
{
    /**
     * @uses \Pyz\Shared\HealthCheck\HealthCheckConfig::STORAGE_SERVICE_NAME
     */
    protected const STORAGE_SERVICE_NAME = 'storage';

    /**
     * @uses \Pyz\Shared\HealthCheck\HealthCheckConfig::SEARCH_SERVICE_NAME
     */
    public const SEARCH_SERVICE_NAME = 'search';

    /**
     * @uses \Pyz\Shared\HealthCheck\HealthCheckConfig::SESSION_SERVICE_NAME
     */
    public const SESSION_SERVICE_NAME = 'session';

    /**
     * @uses \Pyz\Shared\HealthCheck\HealthCheckConfig::DATABASE_SERVICE_NAME
     */
    public const DATABASE_SERVICE_NAME = 'database';

    /**
     * @return string[]
     */
    public function getAvailableHealthCheckServices(): array
    {
        return [
            static::STORAGE_SERVICE_NAME,
            static::SEARCH_SERVICE_NAME,
            static::SESSION_SERVICE_NAME,
            static::DATABASE_SERVICE_NAME,
        ];
    }
}
