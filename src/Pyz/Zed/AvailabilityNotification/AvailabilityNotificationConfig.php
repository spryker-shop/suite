<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Pyz\Zed\AvailabilityNotification;

use Spryker\Shared\Publisher\PublisherConfig;
use Spryker\Zed\AvailabilityNotification\AvailabilityNotificationConfig as SprykerAvailabilityNotificationConfig;

class AvailabilityNotificationConfig extends SprykerAvailabilityNotificationConfig
{
    /**
     * @return string|null
     */
    public function getAvailabilityNotificationEventQueueName(): ?string
    {
        return PublisherConfig::PUBLISH_QUEUE;
    }
}
