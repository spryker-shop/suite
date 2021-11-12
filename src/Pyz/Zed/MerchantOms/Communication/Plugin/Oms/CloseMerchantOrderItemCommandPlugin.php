<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\MerchantOms\Communication\Plugin\Oms;

use Generated\Shared\Transfer\MerchantOmsTriggerRequestTransfer;
use Generated\Shared\Transfer\MerchantOrderItemCriteriaTransfer;
use LogicException;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Oms\Business\Util\ReadOnlyArrayObject;
use Spryker\Zed\Oms\Dependency\Plugin\Command\CommandByItemInterface;

/**
 * @method \Spryker\Zed\MerchantOms\Business\MerchantOmsFacadeInterface getFacade()
 * @method \Spryker\Zed\MerchantOms\Communication\MerchantOmsCommunicationFactory getFactory()
 * @method \Spryker\Zed\MerchantOms\MerchantOmsConfig getConfig()
 */
class CloseMerchantOrderItemCommandPlugin extends AbstractPlugin implements CommandByItemInterface
{
    /**
     * @var string
     */
    protected const EVENT_CLOSE = 'close';

    /**
     * {@inheritDoc}
     * - Triggers 'close' event on a merchant order item.
     *
     * @api
     *
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem $orderItem
     * @param \Spryker\Zed\Oms\Business\Util\ReadOnlyArrayObject $data
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
            ->setMerchantOmsEventName(static::EVENT_CLOSE)
            ->addMerchantOrderItem($merchantOrderItemTransfer);

        $transitionCount = $this->getFacade()->triggerEventForMerchantOrderItems($merchantOmsTriggerRequestTransfer);
        if ($transitionCount === 0) {
            throw new LogicException(sprintf(
                'Merchant Order Item #%s transition for event "%s" has not happened.',
                $merchantOrderItemTransfer->getIdMerchantOrderItem(),
                static::EVENT_CLOSE,
            ));
        }

        return [];
    }
}
