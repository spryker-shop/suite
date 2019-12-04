<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\HealthCheckPage;

use SprykerShop\Yves\HealthCheckPage\HealthCheckPageConfig as SprykerShopHealthCheckPageConfig;

class HealthCheckPageConfig extends SprykerShopHealthCheckPageConfig
{
    /**
     * @uses \Pyz\Shared\HealthCheck\HealthCheckConfig::STORAGE_SERVICE_NAME
     */
    public const STORAGE_SERVICE_NAME = 'storage';

    /**
     * @uses \Pyz\Shared\HealthCheck\HealthCheckConfig::SEARCH_SERVICE_NAME
     */
    public const SEARCH_SERVICE_NAME = 'search';

    /**
     * @uses \Pyz\Shared\HealthCheck\HealthCheckConfig::SESSION_SERVICE_NAME
     */
    public const SESSION_SERVICE_NAME = 'session';

    /**
     * @uses \Pyz\Shared\HealthCheck\HealthCheckConfig::ZED_REQUEST_SERVICE_NAME
     */
    public const ZED_REQUEST_SERVICE_NAME = 'zed-request';

    /**
     * @return string[]
     */
    public function getAvailableHealthCheckServices(): array
    {
        return [
            static::STORAGE_SERVICE_NAME,
            static::SEARCH_SERVICE_NAME,
            static::SESSION_SERVICE_NAME,
            static::ZED_REQUEST_SERVICE_NAME,
        ];
    }
}
