<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\PriceProductStorage;

use Pyz\Shared\Publisher\PublisherConstants;
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

    /**
     * @return bool
     */
    public function isCteEnabled(): bool
    {
        return $this->get(PublisherConstants::IS_CTE_ENABLED, false);
    }
}
