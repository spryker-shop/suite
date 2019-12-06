<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\HealthCheckPage;

use Spryker\Shared\Search\SearchConfig;
use Spryker\Shared\Session\SessionConfig;
use Spryker\Shared\Storage\StorageConfig;
use Spryker\Shared\ZedRequest\ZedRequestConfig;
use SprykerShop\Yves\HealthCheckPage\HealthCheckPageConfig as SprykerShopHealthCheckPageConfig;

class HealthCheckPageConfig extends SprykerShopHealthCheckPageConfig
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
            ZedRequestConfig::ZED_REQUEST_SERVICE_NAME,
        ];
    }
}
