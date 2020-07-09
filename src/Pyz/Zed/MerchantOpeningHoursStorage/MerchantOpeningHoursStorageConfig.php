<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\MerchantOpeningHoursStorage;

use Pyz\Zed\Synchronization\SynchronizationConfig;
use Spryker\Shared\Publisher\PublisherConfig;
use Spryker\Zed\MerchantOpeningHoursStorage\MerchantOpeningHoursStorageConfig as SprykerMerchantOpeningHoursStorageConfig;

class MerchantOpeningHoursStorageConfig extends SprykerMerchantOpeningHoursStorageConfig
{
    /**
     * @return string|null
     */
    public function getMerchantOpeningHoursSynchronizationPoolName(): ?string
    {
        return SynchronizationConfig::DEFAULT_SYNCHRONIZATION_POOL_NAME;
    }

    /**
     * @return string|null
     */
    public function getEventQueueName(): ?string
    {
        return PublisherConfig::PUBLISH_QUEUE;
    }
}
