<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Shared\HealthCheck;

use Spryker\Shared\HealthCheck\HealthCheckConfig as SprykerHealthCheckConfig;

class HealthCheckConfig extends SprykerHealthCheckConfig
{
    public const SESSION_SERVICE_NAME = 'session';
    public const STORAGE_SERVICE_NAME = 'storage';
    public const SEARCH_SERVICE_NAME = 'search';
    public const ZED_REQUEST_SERVICE_NAME = 'zed-request';
    public const DATABASE_SERVICE_NAME = 'database';
}
