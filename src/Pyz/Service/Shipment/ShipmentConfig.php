<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Service\Shipment;

use Generated\Shared\Transfer\ShipmentTransfer;
use Spryker\Service\Shipment\ShipmentConfig as SprykerShipmentConfig;

class ShipmentConfig extends SprykerShipmentConfig
{
    /**
     * {@inheritDoc}
     *
     * @return string[]
     */
    public function getShipmentHashFields(): array
    {
        return array_merge(parent::getShipmentHashFields(), [ShipmentTransfer::MERCHANT_REFERENCE]);
    }
}
