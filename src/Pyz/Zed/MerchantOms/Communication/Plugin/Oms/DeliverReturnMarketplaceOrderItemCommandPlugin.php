<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\MerchantOms\Communication\Plugin\Oms;

class DeliverReturnMarketplaceOrderItemCommandPlugin extends AbstractTriggerOmsEventCommandPlugin
{
    /**
     * @var string
     */
    protected const EVENT_DELIVER_RETURN = 'deliver-return';

    /**
     * @return string
     */
    public function getEventName(): string
    {
        return static::EVENT_DELIVER_RETURN;
    }
}
