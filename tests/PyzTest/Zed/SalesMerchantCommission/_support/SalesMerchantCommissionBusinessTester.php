<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Zed\SalesMerchantCommission;

use Codeception\Actor;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Orm\Zed\MerchantCommission\Persistence\SpyMerchantCommissionQuery;
use Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrder;
use Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrderQuery;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Orm\Zed\Sales\Persistence\SpySalesOrderQuery;
use Orm\Zed\SalesMerchantCommission\Persistence\Base\SpySalesMerchantCommission;
use Orm\Zed\SalesMerchantCommission\Persistence\SpySalesMerchantCommissionQuery;
use Propel\Runtime\Collection\Collection;
use Spryker\Zed\MerchantSalesOrder\Business\MerchantSalesOrderFacadeInterface;
use Spryker\Zed\Refund\Business\RefundFacadeInterface;
use Spryker\Zed\Sales\Business\SalesFacadeInterface;

/**
 * Inherited Methods
 *
 * @method void wantTo($text)
 * @method void wantToTest($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause($vars = [])
 * @method \Spryker\Zed\SalesMerchantCommission\Business\SalesMerchantCommissionFacadeInterface getFacade(?string $moduleName = NULL)
 *
 * @SuppressWarnings(PHPMD)
 */
class SalesMerchantCommissionBusinessTester extends Actor
{
    use _generated\SalesMerchantCommissionBusinessTesterActions;

    /**
     * @var string
     */
    protected const DEFAULT_OMS_PROCESS_NAME = 'Test05';

    /**
     * @return void
     */
    public function configureDefaultTestStateMachine(): void
    {
        $this->configureTestStateMachine([static::DEFAULT_OMS_PROCESS_NAME]);
    }

    /**
     * @return void
     */
    public function ensureMerchantCommissionTableIsEmpty(): void
    {
        $this->ensureDatabaseTableIsEmpty($this->getMerchantCommissionQuery());
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function createOrderFromQuoteTransfer(QuoteTransfer $quoteTransfer): OrderTransfer
    {
        $saveOrderTransfer = $this->haveOrderFromQuote($quoteTransfer, static::DEFAULT_OMS_PROCESS_NAME);

        return (new OrderTransfer())->fromArray($saveOrderTransfer->toArray(), true);
    }

    /**
     * @param int $idSalesOrderItem
     *
     * @return \Propel\Runtime\Collection\Collection<array-key, \Orm\Zed\SalesMerchantCommission\Persistence\SpySalesMerchantCommission>
     */
    public function getSalesMerchantCommissionEntities(int $idSalesOrderItem): Collection
    {
        return $this->getSalesMerchantCommissionQuery()
            ->filterByFkSalesOrderItem($idSalesOrderItem)
            ->find();
    }

    /**
     * @param int $idSalesOrder
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrder
     */
    public function getSalesOrderWithSalesOrderItems(int $idSalesOrder): SpySalesOrder
    {
        return $this->getSalesOrderQuery()
            ->joinWithItem()
            ->joinWithOrderTotal()
            ->findByIdSalesOrder($idSalesOrder)
            ->getFirst();
    }

    /**
     * @param int $idSalesOrder
     * @param string $merchantReference
     *
     * @return \Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrder
     */
    public function getMerchantSalesOrderWithTotals(int $idSalesOrder, string $merchantReference): SpyMerchantSalesOrder
    {
        return $this->getMerchantSalesOrderQuery()
            ->joinWithMerchantSalesOrderTotal()
            ->filterByFkSalesOrder($idSalesOrder)
            ->filterByMerchantReference($merchantReference)
            ->find()
            ->getFirst();
    }

    /**
     * @param \Propel\Runtime\Collection\Collection<array-key, \Orm\Zed\Sales\Persistence\SpySalesOrderItem> $salesOrderItemEntities
     * @param string $sku
     * @param string $merchantReference
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItem|null
     */
    public function findSalesOrderItemBySkuAndMerchantReference(
        Collection $salesOrderItemEntities,
        string $sku,
        string $merchantReference,
    ): ?SpySalesOrderItem {
        foreach ($salesOrderItemEntities as $salesOrderItemEntity) {
            if ($salesOrderItemEntity->getSku() === $sku && $salesOrderItemEntity->getMerchantReference() === $merchantReference) {
                return $salesOrderItemEntity;
            }
        }

        return null;
    }

    /**
     * @param \Propel\Runtime\Collection\Collection<array-key, \Orm\Zed\SalesMerchantCommission\Persistence\SpySalesMerchantCommission> $salesMerchantCommissionEntities
     * @param string $merchantCommissionName
     *
     * @return \Orm\Zed\SalesMerchantCommission\Persistence\Base\SpySalesMerchantCommission|null
     */
    public function findSalesMerchantCommissionEntityByName(
        Collection $salesMerchantCommissionEntities,
        string $merchantCommissionName,
    ): ?SpySalesMerchantCommission {
        foreach ($salesMerchantCommissionEntities as $merchantCommissionEntity) {
            if ($merchantCommissionEntity->getName() === $merchantCommissionName) {
                return $merchantCommissionEntity;
            }
        }

        return null;
    }

    /**
     * @param int $idSalesOrder
     * @param string $merchantReference
     * @param int $expectedMerchantCommissionTotal
     *
     * @return void
     */
    public function assertMerchantSalesOrderTotals(int $idSalesOrder, string $merchantReference, int $expectedMerchantCommissionTotal): void
    {
        $merchantSalesOrder1Entity = $this->getMerchantSalesOrderWithTotals($idSalesOrder, $merchantReference);

        $this->assertSame(
            $expectedMerchantCommissionTotal,
            $merchantSalesOrder1Entity->getMerchantSalesOrderTotals()->getLast()->getMerchantCommissionTotal(),
        );
    }

    /**
     * @param int $idSalesOrder
     * @param string $merchantReference
     * @param int $expectedMerchantCommissionRefundedTotal
     *
     * @return void
     */
    public function assertMerchantSalesOrderRefundedTotals(int $idSalesOrder, string $merchantReference, int $expectedMerchantCommissionRefundedTotal): void
    {
        $merchantSalesOrder1Entity = $this->getMerchantSalesOrderWithTotals($idSalesOrder, $merchantReference);

        $this->assertSame(
            $expectedMerchantCommissionRefundedTotal,
            $merchantSalesOrder1Entity->getMerchantSalesOrderTotals()->getLast()->getMerchantCommissionRefundedTotal(),
        );
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $salesOrderEntity
     * @param list<\Orm\Zed\Sales\Persistence\SpySalesOrderItem> $salesOrderItemEntities
     *
     * @return void
     */
    public function refundSalesOrderItems(SpySalesOrder $salesOrderEntity, array $salesOrderItemEntities): void
    {
        $refundTransfer = $this->getRefundFacade()->calculateRefund($salesOrderItemEntities, $salesOrderEntity);
        $this->getRefundFacade()->saveRefund($refundTransfer);
    }

    /**
     * @return \Spryker\Zed\Sales\Business\SalesFacadeInterface
     */
    public function getSalesFacade(): SalesFacadeInterface
    {
        return $this->getLocator()->sales()->facade();
    }

    /**
     * @return \Spryker\Zed\MerchantSalesOrder\Business\MerchantSalesOrderFacadeInterface
     */
    public function getMerchantSalesOrderFacade(): MerchantSalesOrderFacadeInterface
    {
        return $this->getLocator()->merchantSalesOrder()->facade();
    }

    /**
     * @return \Spryker\Zed\Refund\Business\RefundFacadeInterface
     */
    public function getRefundFacade(): RefundFacadeInterface
    {
        return $this->getLocator()->refund()->facade();
    }

    /**
     * @return \Orm\Zed\MerchantCommission\Persistence\SpyMerchantCommissionQuery
     */
    protected function getMerchantCommissionQuery(): SpyMerchantCommissionQuery
    {
        return SpyMerchantCommissionQuery::create();
    }

    /**
     * @return \Orm\Zed\SalesMerchantCommission\Persistence\SpySalesMerchantCommissionQuery
     */
    protected function getSalesMerchantCommissionQuery(): SpySalesMerchantCommissionQuery
    {
        return SpySalesMerchantCommissionQuery::create();
    }

    /**
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderQuery
     */
    protected function getSalesOrderQuery(): SpySalesOrderQuery
    {
        return SpySalesOrderQuery::create();
    }

    /**
     * @return \Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrderQuery
     */
    protected function getMerchantSalesOrderQuery(): SpyMerchantSalesOrderQuery
    {
        return SpyMerchantSalesOrderQuery::create();
    }
}
