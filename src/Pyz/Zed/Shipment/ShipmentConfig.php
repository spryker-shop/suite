<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Shared\Shipment;

use Spryker\Zed\Shipment\ShipmentConfig as SprykerShipmentConfig;

class ShipmentConfig extends SprykerShipmentConfig
{
   /**
    * @return bool
    */
    public function isMultiShipmentSelectionEnabled(): bool
    {
        return true;
    }
}
