<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\UrlStorage;

use Pyz\Shared\Publisher\PublisherConstants;
use Pyz\Zed\Synchronization\SynchronizationConfig;
use Spryker\Shared\UrlStorage\UrlStorageConfig as SprykerSharedUrlStorageConfig;
use Spryker\Zed\UrlStorage\UrlStorageConfig as SprykerUrlStorageConfig;

class UrlStorageConfig extends SprykerUrlStorageConfig
{
    /**
     * @return string|null
     */
    public function getUrlSynchronizationPoolName(): ?string
    {
        return SynchronizationConfig::DEFAULT_SYNCHRONIZATION_POOL_NAME;
    }

    /**
     * @return string|null
     */
    public function getUrlRedirectSynchronizationPoolName(): ?string
    {
        return SynchronizationConfig::DEFAULT_SYNCHRONIZATION_POOL_NAME;
    }

    /**
     * @return string|null
     */
    public function getUrlEventQueueName(): ?string
    {
        return SprykerSharedUrlStorageConfig::PUBLISH_URL;
    }

    /**
     * @return string|null
     */
    public function getUrlRedirectEventQueueName(): ?string
    {
        return SprykerSharedUrlStorageConfig::PUBLISH_URL;
    }

    /**
     * @return bool
     */
    public function isCteEnabled(): bool
    {
        return $this->get(PublisherConstants::IS_CTE_ENABLED, false);
    }
}
