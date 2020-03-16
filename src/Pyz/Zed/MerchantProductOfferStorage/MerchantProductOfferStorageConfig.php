<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Pyz\Zed\MerchantProductOfferStorage;

use Spryker\Shared\Publisher\PublisherConfig;
use Spryker\Zed\MerchantProductOfferStorage\MerchantProductOfferStorageConfig as SprykerMerchantProductOfferStorageConfig;

class MerchantProductOfferStorageConfig extends SprykerMerchantProductOfferStorageConfig
{
    /**
     * @return string|null
     */
    public function getMerchantProductOfferEventQueueName(): ?string
    {
        return PublisherConfig::PUBLISH_QUEUE;
    }
}
