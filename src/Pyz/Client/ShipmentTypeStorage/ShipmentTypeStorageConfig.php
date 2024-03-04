<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\ShipmentTypeStorage;

use Spryker\Client\ShipmentTypeStorage\ShipmentTypeStorageConfig as SprykerShipmentTypeStorageConfig;

class ShipmentTypeStorageConfig extends SprykerShipmentTypeStorageConfig
{
    /**
     * @return int
     */
    public function getScanKeyStoreLimit(): int
    {
        return 20;
    }
}
