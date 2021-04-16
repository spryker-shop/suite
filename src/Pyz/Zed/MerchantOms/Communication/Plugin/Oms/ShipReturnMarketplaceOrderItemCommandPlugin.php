<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\MerchantOms\Communication\Plugin\Oms;

class ShipReturnMarketplaceOrderItemCommandPlugin extends AbstractTriggerOmsEventCommandPlugin
{
    protected const EVENT_SHIP_RETURN = 'ship-return';

    /**
     * @return string
     */
    public function getEventName(): string
    {
        return static::EVENT_SHIP_RETURN;
    }
}
