<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Pyz\Yves\ClickAndCollectPageExample;

use SprykerShop\Yves\ClickAndCollectPageExample\ClickAndCollectPageExampleConfig as SprykerClickAndCollectPageExampleConfig;

class ClickAndCollectPageExampleConfig extends SprykerClickAndCollectPageExampleConfig
{
    /**
     * @uses \SprykerFeature\Yves\SspServiceManagement\SspServiceManagementConfig::SHIPMENT_TYPE_ON_SITE_SERVICE
     *
     * @var string
     */
    protected const SHIPMENT_TYPE_ON_SITE_SERVICE = 'on-site-service';

    /**
     * @var list<string>
     */
    protected const CLICK_AND_COLLECT_SHIPMENT_TYPES = [
        self::SHIPMENT_TYPE_ON_SITE_SERVICE,
        self::SHIPMENT_TYPE_DELIVERY,
        self::SHIPMENT_TYPE_PICKUP,
    ];
}
