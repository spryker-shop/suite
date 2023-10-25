<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Shared\ShipmentTypeServicePointsRestApi;

use Spryker\Shared\ShipmentTypeServicePointsRestApi\ShipmentTypeServicePointsRestApiConfig as SprykerShipmentTypeServicePointsRestApiConfig;

class ShipmentTypeServicePointsRestApiConfig extends SprykerShipmentTypeServicePointsRestApiConfig
{
    /**
     * @var string
     */
    protected const SHIPMENT_TYPE_KEY_PICKUP = 'pickup';

    /**
     * @return list<string>
     */
    public function getApplicableShipmentTypeKeysForShippingAddress(): array
    {
        return [static::SHIPMENT_TYPE_KEY_PICKUP];
    }
}
