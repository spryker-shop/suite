<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Pyz\Zed\ProductOptionStorage;

use Spryker\Shared\Publisher\PublisherConfig;
use Spryker\Zed\ProductOptionStorage\ProductOptionStorageConfig as SprykerProductOptionStorageConfig;

class ProductOptionStorageConfig extends SprykerProductOptionStorageConfig
{
    /**
     * @return string|null
     */
    public function getProductOptionEventQueueName(): ?string
    {
        return PublisherConfig::PUBLISH_QUEUE;
    }
}
