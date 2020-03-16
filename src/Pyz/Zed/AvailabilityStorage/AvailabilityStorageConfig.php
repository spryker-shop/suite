<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Pyz\Zed\AvailabilityStorage;

use Spryker\Shared\AvailabilityStorage\AvailabilityStorageConfig as SprykerSharedAvailabilityStorageConfig;
use Spryker\Zed\AvailabilityStorage\AvailabilityStorageConfig as SprykerAvailabilityStorageConfig;

class AvailabilityStorageConfig extends SprykerAvailabilityStorageConfig
{
    /**
     * @return string|null
     */
    public function getAvailabilityEventQueueName(): ?string
    {
        return SprykerSharedAvailabilityStorageConfig::PUBLISH_AVAILABILITY;
    }
}
