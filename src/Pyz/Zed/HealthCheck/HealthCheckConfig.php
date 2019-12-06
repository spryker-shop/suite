<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\HealthCheck;

use Spryker\Shared\Propel\PropelConfig;
use Spryker\Shared\Search\SearchConfig;
use Spryker\Shared\Session\SessionConfig;
use Spryker\Shared\Storage\StorageConfig;
use Spryker\Zed\HealthCheck\HealthCheckConfig as SprykerHealthCheckConfig;

class HealthCheckConfig extends SprykerHealthCheckConfig
{
    /**
     * @return string[]
     */
    public function getAvailableHealthCheckServices(): array
    {
        return [
            StorageConfig::STORAGE_SERVICE_NAME,
            SearchConfig::SEARCH_SERVICE_NAME,
            SessionConfig::SESSION_SERVICE_NAME,
            PropelConfig::DATABASE_SERVICE_NAME,
        ];
    }
}
