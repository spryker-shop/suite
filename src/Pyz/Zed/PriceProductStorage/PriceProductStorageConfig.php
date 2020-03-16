<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Pyz\Zed\PriceProductStorage;

use Spryker\Shared\PriceProductStorage\PriceProductStorageConfig as SprykerSharedPriceProductStorageConfig;
use Spryker\Zed\PriceProductStorage\PriceProductStorageConfig as SprykerPriceProductStorageConfig;

class PriceProductStorageConfig extends SprykerPriceProductStorageConfig
{
    /**
     * @return string|null
     */
    public function getPriceProductAbstractEventQueueName(): ?string
    {
        return SprykerSharedPriceProductStorageConfig::PUBLISH_PRICE_PRODUCT_ABSTRACT;
    }

    /**
     * @return string|null
     */
    public function getPriceProductConcreteEventQueueName(): ?string
    {
        return SprykerSharedPriceProductStorageConfig::PUBLISH_PRICE_PRODUCT_CONCRETE;
    }
}
