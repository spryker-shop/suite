<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\MerchantOms\Communication\Plugin\Oms;

use Generated\Shared\Transfer\MerchantOrderItemCriteriaTransfer;
use Generated\Shared\Transfer\StateMachineItemTransfer;
use LogicException;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\StateMachine\Dependency\Plugin\CommandPluginInterface;

/**
 * @method \Pyz\Zed\MerchantOms\Communication\MerchantOmsCommunicationFactory getFactory()
 */
class ShipByMerchantMarketplaceOrderItemCommandPlugin extends AbstractPlugin implements CommandPluginInterface
{
    protected const EVENT_SHIP_BY_MERCHANT = 'ship by merchant';

    /**
     * {@inheritDoc}
     * - Triggers 'ship' event on a marketplace order item.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\StateMachineItemTransfer $stateMachineItemTransfer
     *
     * @return void
     */
    public function run(StateMachineItemTransfer $stateMachineItemTransfer)
    {
        $merchantOrderItemTransfer = $this->getFactory()->getMerchantSalesOrderFacade()->findMerchantOrderItem(
            (new MerchantOrderItemCriteriaTransfer())
                ->setIdMerchantOrderItem($stateMachineItemTransfer->getIdentifier())
        );

        if (!$merchantOrderItemTransfer) {
            return;
        }

        $result = $this->getFactory()->getOmsFacade()->triggerEventForOneOrderItem(static::EVENT_SHIP_BY_MERCHANT, $merchantOrderItemTransfer->getIdOrderItem());

        if ($result === null) {
            throw new LogicException(sprintf(
                'Sales Order Item #%s transition for event "%s" has not happened.',
                $merchantOrderItemTransfer->getIdOrderItem(),
                static::EVENT_SHIP_BY_MERCHANT
            ));
        }
    }
}
