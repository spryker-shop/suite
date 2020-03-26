<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\MerchantSalesOrder\Communication\Plugin\Oms;

use Generated\Shared\Transfer\MerchantOrderCriteriaTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Oms\Business\Util\ReadOnlyArrayObject;
use Spryker\Zed\Oms\Dependency\Plugin\Command\CommandByOrderInterface;

/**
 * @method \Pyz\Zed\MerchantSalesOrder\Communication\MerchantSalesOrderCommunicationFactory getFactory()
 * @method \Spryker\Zed\MerchantSalesOrder\Business\MerchantSalesOrderFacade getFacade()
 */
class CreateMerchantOrdersCommandPlugin extends AbstractPlugin implements CommandByOrderInterface
{
    /**
     * {@inheritDoc}
     * - Requires Order.idSalesOrder, Order.orderReference, Order.items transfer fields to be set.
     * - Iterates through the order items of given order looking for merchant reference presence.
     * - Skips all the order items without merchant reference.
     * - Creates a new merchant order for each unique merchant reference found.
     * - Creates a new merchant order item for each order item with merchant reference and assign it to a merchant order accordingly.
     * - Creates a new merchant order totals and assign it to a merchant order accordingly.
     *
     * @api
     *
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem[] $orderItems
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $orderEntity
     * @param \Spryker\Zed\Oms\Business\Util\ReadOnlyArrayObject $data
     *
     * @return array
     */
    public function run(array $orderItems, SpySalesOrder $orderEntity, ReadOnlyArrayObject $data): array
    {
        $merchantOrderTransfer = $this->getFacade()
            ->findMerchantOrder((new MerchantOrderCriteriaTransfer())
                ->setIdOrder($orderEntity->getIdSalesOrder()));

        // checks if order is already splitted
        if ($merchantOrderTransfer) {
            return [];
        }

        $salesFacade = $this->getFactory()->getSalesFacade();
        $orderTransfer = $salesFacade->findOrderByIdSalesOrder($orderEntity->getIdSalesOrder());

        $this->getFacade()->createMerchantOrderCollection($orderTransfer);

        return [];
    }
}
