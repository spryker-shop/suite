<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Pyz\Yves\SelfServicePortal;

use SprykerFeature\Yves\SelfServicePortal\SelfServicePortalConfig as SprykerSelfServicePortalConfig;

class SelfServicePortalConfig extends SprykerSelfServicePortalConfig
{
    /**
     * @api
     *
     * @var string
     */
    protected const SHIPMENT_TYPE_IN_CENTER_SERVICE = 'in-center-service';

    /**
     * @api
     *
     * @uses \Spryker\Shared\ShipmentType\ShipmentTypeConfig::SHIPMENT_TYPE_DELIVERY
     *
     * @var string
     */
    public const SHIPMENT_TYPE_DELIVERY = 'delivery';

    /**
     * @api
     *
     * @var string
     */
    public const SHIPMENT_TYPE_ON_SITE_SERVICE = 'on-site-service';

    /**
     * @api
     *
     * @return list<string>
     */
    public function getDeliveryLikeShipmentTypes(): array
    {
        return [
            static::SHIPMENT_TYPE_DELIVERY,
            static::SHIPMENT_TYPE_ON_SITE_SERVICE,
        ];
    }
}
