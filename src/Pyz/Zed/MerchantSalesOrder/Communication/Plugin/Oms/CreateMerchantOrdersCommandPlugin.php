<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\MerchantSalesOrder\Communication\Plugin\Oms;

use ArrayObject;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Oms\Business\Util\ReadOnlyArrayObject;
use Spryker\Zed\Oms\Dependency\Plugin\Command\CommandByOrderInterface;

/**
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
        $orderTransfer = (new OrderTransfer())
            ->setIdSalesOrder($orderEntity->getIdSalesOrder())
            ->setOrderReference($orderEntity->getOrderReference())
            ->setItems(new ArrayObject($this->mapOrderItemEntitiesToOrderItemTransfers($orderItems)));

        $this->getFacade()->createMerchantOrderCollection($orderTransfer);

        return [];
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem[] $orderItemEntities
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    protected function mapOrderItemEntitiesToOrderItemTransfers(array $orderItemEntities): array
    {
        $itemTransfers = [];

        foreach ($orderItemEntities as $orderItemEntity) {
            $itemTransfers[] = (new ItemTransfer())
                ->setMerchantReference($orderItemEntity->getMerchantReference())
                ->setIdSalesOrderItem($orderItemEntity->getIdSalesOrderItem());
        }

        return $itemTransfers;
    }
}
