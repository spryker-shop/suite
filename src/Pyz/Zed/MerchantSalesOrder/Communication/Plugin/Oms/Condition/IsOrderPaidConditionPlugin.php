<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\MerchantSalesOrder\Communication\Plugin\Oms\Condition;

use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Oms\Dependency\Plugin\Condition\ConditionInterface;

/**
 * @method \Pyz\Zed\MerchantSalesOrder\Communication\MerchantSalesOrderCommunicationFactory getFactory()
 * @method \Spryker\Zed\MerchantSalesOrder\Business\MerchantSalesOrderFacadeInterface getFacade()
 */
class IsOrderPaidConditionPlugin extends AbstractPlugin implements ConditionInterface
{
    /**
     * @var string
     */
    protected const ITEM_STATE_PAID = 'paid';

    /**
     * {@inheritDoc}
     * - Returns TRUE if all order items are in a correct state.
     *
     * @api
     *
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem $orderItem
     *
     * @return bool
     */
    public function check(SpySalesOrderItem $orderItem): bool
    {
        $salesFacade = $this->getFactory()->getSalesFacade();
        $orderTransfer = $salesFacade->findOrderByIdSalesOrder($orderItem->getFkSalesOrder());

        foreach ($orderTransfer->getItems() as $itemTransfer) {
            if ($itemTransfer->getState()->getName() !== static::ITEM_STATE_PAID) {
                return false;
            }
        }

        return true;
    }
}
