<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Glue\HealthCheckRestApi;

use Spryker\Glue\HealthCheckRestApi\HealthCheckRestApiConfig as SprykerHealthCheckRestApiConfig;
use Spryker\Shared\Search\SearchConfig;
use Spryker\Shared\Storage\StorageConfig;
use Spryker\Shared\ZedRequest\ZedRequestConfig;

class HealthCheckRestApiConfig extends SprykerHealthCheckRestApiConfig
{
    /**
     * @return string[]
     */
    public function getAvailableServiceNames(): array
    {
        return [
            StorageConfig::STORAGE_SERVICE_NAME,
            SearchConfig::SEARCH_SERVICE_NAME,
            ZedRequestConfig::ZED_REQUEST_SERVICE_NAME,
        ];
    }
}
