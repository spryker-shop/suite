<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductOfferShipmentTypeStorage;

use Pyz\Zed\Synchronization\SynchronizationConfig;
use Spryker\Zed\ProductOfferShipmentTypeStorage\ProductOfferShipmentTypeStorageConfig as SprykerProductOfferShipmentTypeStorageConfig;

class ProductOfferShipmentTypeStorageConfig extends SprykerProductOfferShipmentTypeStorageConfig
{
    /**
     * @api
     *
     * @return string|null
     */
    public function getProductOfferShipmentTypeSynchronizationPoolName(): ?string
    {
        return SynchronizationConfig::DEFAULT_SYNCHRONIZATION_POOL_NAME;
    }
}
