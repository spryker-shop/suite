<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\MerchantOms\Communication\Plugin\Oms;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\MerchantOmsTriggerRequestTransfer;
use Generated\Shared\Transfer\MerchantOrderItemCriteriaTransfer;
use LogicException;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Oms\Business\Util\ReadOnlyArrayObject;
use Spryker\Zed\Oms\Dependency\Plugin\Command\CommandByItemInterface;

/**
 * @method \Pyz\Zed\MerchantOms\Communication\MerchantOmsCommunicationFactory getFactory()
 * @method \Pyz\Zed\MerchantOms\MerchantOmsConfig getConfig()
 * @method \Spryker\Zed\MerchantOms\Business\MerchantOmsFacade getFacade()()
 */
class ReturnMerchantOrderItemCommandPlugin extends AbstractPlugin implements CommandByItemInterface
{
    /**
     * @var string
     */
    protected const EVENT_START_RETURN = 'start-return';

    /**
     * {@inheritDoc}
     * - Triggers 'start return' event on a merchant order item.
     *
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem $orderItem
     * @param \Spryker\Zed\Oms\Business\Util\ReadOnlyArrayObject $data
     *
     * @throws \LogicException
     *
     * @return array
     */
    public function run(SpySalesOrderItem $orderItem, ReadOnlyArrayObject $data): array
    {
        $merchantOrderItemTransfer = $this->getFactory()->getMerchantSalesOrderFacade()->findMerchantOrderItem(
            (new MerchantOrderItemCriteriaTransfer())
                ->setIdOrderItem($orderItem->getIdSalesOrderItem()),
        );

        if (!$merchantOrderItemTransfer) {
            return [];
        }

        $merchantOmsTriggerRequestTransfer = (new MerchantOmsTriggerRequestTransfer())
            ->setMerchantOmsEventName(static::EVENT_START_RETURN)
            ->addMerchantOrderItem($merchantOrderItemTransfer);

        $transitionCount = $this->getFacade()->triggerEventForMerchantOrderItems($merchantOmsTriggerRequestTransfer);

        if ($transitionCount === 0) {
            throw new LogicException(sprintf(
                'Merchant Order Item #%s transition for event "%s" has not happened.',
                $merchantOrderItemTransfer->getIdMerchantOrderItem(),
                static::EVENT_START_RETURN,
            ));
        }

        $itemTransfer = (new ItemTransfer())
            ->setIdSalesOrderItem($orderItem->getIdSalesOrderItem());

        $this->getFactory()->getSalesReturnFacade()->setOrderItemRemunerationAmount($itemTransfer);

        return [];
    }
}
