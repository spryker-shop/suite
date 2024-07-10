<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Zed\SalesMerchantCommission\Business\MerchantCommissionCalculationTestCases;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\MerchantProfileBuilder;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\MerchantCommissionTransfer;
use Generated\Shared\Transfer\MerchantTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Propel\Runtime\Collection\Collection;
use PyzTest\Zed\SalesMerchantCommission\SalesMerchantCommissionBusinessTester;

/**
 * Case 5: Recalculate commissions after refund
 *
 * @link https://spryker.atlassian.net/wiki/spaces/CCS/pages/4157341822/Commission+Calculation+in+Master+Merchant+OMS#Approved-Use-Case-5%3A-Recalculate-commissions-after-refund
 *
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Zed
 * @group SalesMerchantCommission
 * @group Business
 * @group MerchantCommissionCalculationTestCases
 * @group Case05Test
 * Add your own group annotations below this line
 */
class Case05Test extends Unit
{
    /**
     * @var string
     */
    protected const TEST_MERCHANT_COMMISSION_ORDER_CONDITION = 'price-mode = "GROSS_MODE"';

    /**
     * @var string
     */
    protected const TEST_MERCHANT_COMMISSION_ITEM_CONDITION = '';

    /**
     * @uses \Spryker\Zed\MerchantCommission\Communication\Plugin\MerchantCommission\PercentageMerchantCommissionCalculatorPlugin::CALCULATOR_TYPE
     *
     * @var string
     */
    protected const TEST_MERCHANT_COMMISSION_CALCULATOR_PLUGIN_TYPE = 'percentage';

    /**
     * @var int
     */
    protected const TEST_MERCHANT_COMMISSION_AMOUNT = 1000;

    /**
     * @var string
     */
    protected const TEST_MERCHANT_COMMISSION_NAME = '10% for all';

    /**
     * @uses \Spryker\Shared\Calculation\CalculationPriceMode::PRICE_MODE_GROSS
     *
     * @var string
     */
    protected const TEST_ORDER_PRICE_MODE = 'GROSS_MODE';

    /**
     * @var string
     */
    protected const TEST_PRODUCT_ABSTRACT_SKU = 'CASE5_P1';

    /**
     * @var string
     */
    protected const TEST_PRODUCT_SKU = 'CASE5_P1_SKU';

    /**
     * @var int
     */
    protected const TEST_PRODUCT_PRICE_GROSS = 100000;

    /**
     * @var int
     */
    protected const TEST_PRODUCT_PRICE_NET = 50000;

    /**
     * @var string
     */
    protected const TEST_MERCHANT_REFERENCE_1 = 'CASE5_MR1';

    /**
     * @var string
     */
    protected const TEST_MERCHANT_REFERENCE_2 = 'CASE5_MR2';

    /**
     * @var string
     */
    protected const TEST_CUSTOMER_REFERENCE = 'CASE5_CR';

    /**
     * @var string
     */
    protected const STORE_NAME_DE = 'DE';

    /**
     * @var string
     */
    protected const CURRENCY_CODE_EUR = 'EUR';

    /**
     * @var int
     */
    protected const EXPECTED_MERCHANT_COMMISSION_REFUNDED_AMOUNT = 10000;

    /**
     * @var int
     */
    protected const EXPECTED_TOTAL_MERCHANT_COMMISSION_REFUNDED_AMOUNT = 20000;

    /**
     * @var int
     */
    protected const EXPECTED_MERCHANT_1_ORDER_TOTAL_MERCHANT_COMMISSION_REFUNDED_AMOUNT = 10000;

    /**
     * @var int
     */
    protected const EXPECTED_MERCHANT_2_ORDER_TOTAL_MERCHANT_COMMISSION_REFUNDED_AMOUNT = 10000;

    /**
     * @var \PyzTest\Zed\SalesMerchantCommission\SalesMerchantCommissionBusinessTester
     */
    protected SalesMerchantCommissionBusinessTester $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->ensureMerchantCommissionTableIsEmpty();
        $this->tester->configureDefaultTestStateMachine();
    }

    /**
     * @return void
     */
    public function testGlobalCommissionShouldBeAppliedToAllOrderItems(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore([StoreTransfer::NAME => static::STORE_NAME_DE]);
        $this->createMerchants($storeTransfer);
        $this->createMerchantCommission($storeTransfer);
        $orderTransfer = $this->createOrderWithItems();

        $salesOrderEntity = $this->tester->getSalesOrderWithSalesOrderItems($orderTransfer->getIdSalesOrderOrFail());
        $salesOrderItemToRefund1 = $this->tester->findSalesOrderItemBySkuAndMerchantReference($salesOrderEntity->getItems(), static::TEST_PRODUCT_SKU, static::TEST_MERCHANT_REFERENCE_1);
        $salesOrderItemToRefund2 = $this->tester->findSalesOrderItemBySkuAndMerchantReference($salesOrderEntity->getItems(), static::TEST_PRODUCT_SKU, static::TEST_MERCHANT_REFERENCE_2);

        // Act
        $this->tester->getFacade()->createSalesMerchantCommissions($orderTransfer);

        $persistedOrderTransfer = $this->tester->getSalesFacade()->findOrderByIdSalesOrder($orderTransfer->getIdSalesOrderOrFail());
        $this->tester->getMerchantSalesOrderFacade()->createMerchantOrderCollection($persistedOrderTransfer);
        $this->tester->refundSalesOrderItems($salesOrderEntity, [$salesOrderItemToRefund1]);
        $this->tester->refundSalesOrderItems($salesOrderEntity, [$salesOrderItemToRefund2]);

        // Assert
        $salesOrderEntity = $this->tester->getSalesOrderWithSalesOrderItems($orderTransfer->getIdSalesOrderOrFail());
        $refundedSalesOrderItem1 = $this->findSalesOrderItemByIdSalesOrderItem($salesOrderEntity->getItems(), $salesOrderItemToRefund1->getIdSalesOrderItem());
        $refundedSalesOrderItem2 = $this->findSalesOrderItemByIdSalesOrderItem($salesOrderEntity->getItems(), $salesOrderItemToRefund2->getIdSalesOrderItem());

        $this->assertNotNull($refundedSalesOrderItem1);
        $this->assertNotNull($refundedSalesOrderItem2);

        $this->assertSalesOrderItemMerchantCommissionRefund(
            $refundedSalesOrderItem1,
            static::EXPECTED_MERCHANT_COMMISSION_REFUNDED_AMOUNT,
        );
        $this->assertSalesOrderItemMerchantCommissionRefund(
            $refundedSalesOrderItem2,
            static::EXPECTED_MERCHANT_COMMISSION_REFUNDED_AMOUNT,
        );

        $this->assertSame(
            static::EXPECTED_TOTAL_MERCHANT_COMMISSION_REFUNDED_AMOUNT,
            $salesOrderEntity->getLastOrderTotals()->getMerchantCommissionRefundedTotal(),
        );

        $this->tester->assertMerchantSalesOrderRefundedTotals(
            $orderTransfer->getIdSalesOrderOrFail(),
            static::TEST_MERCHANT_REFERENCE_1,
            static::EXPECTED_MERCHANT_1_ORDER_TOTAL_MERCHANT_COMMISSION_REFUNDED_AMOUNT,
        );
        $this->tester->assertMerchantSalesOrderRefundedTotals(
            $orderTransfer->getIdSalesOrderOrFail(),
            static::TEST_MERCHANT_REFERENCE_2,
            static::EXPECTED_MERCHANT_2_ORDER_TOTAL_MERCHANT_COMMISSION_REFUNDED_AMOUNT,
        );
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem $salesOrderItemEntity
     * @param int $expectedMerchantCommissionRefundedAmount
     *
     * @return void
     */
    protected function assertSalesOrderItemMerchantCommissionRefund(
        SpySalesOrderItem $salesOrderItemEntity,
        int $expectedMerchantCommissionRefundedAmount,
    ): void {
        $salesMerchantCommissionEntities = $this->tester->getSalesMerchantCommissionEntities($salesOrderItemEntity->getIdSalesOrderItem());
        $this->assertCount(1, $salesMerchantCommissionEntities);

        /** @var \Orm\Zed\SalesMerchantCommission\Persistence\SpySalesMerchantCommission $salesMerchantCommissionEntity */
        $salesMerchantCommissionEntity = $salesMerchantCommissionEntities->getIterator()->current();
        $this->assertSame($expectedMerchantCommissionRefundedAmount, $salesMerchantCommissionEntity->getRefundedAmount());
        $this->assertSame($expectedMerchantCommissionRefundedAmount, $salesOrderItemEntity->getMerchantCommissionRefundedAmount());
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return void
     */
    protected function createMerchants(StoreTransfer $storeTransfer): void
    {
        $this->tester->haveMerchant([
            MerchantTransfer::MERCHANT_REFERENCE => static::TEST_MERCHANT_REFERENCE_1,
            MerchantTransfer::STORE_RELATION => (new StoreRelationTransfer())->addStores($storeTransfer)->toArray(),
            MerchantTransfer::MERCHANT_PROFILE => (new MerchantProfileBuilder())->build()->toArray(),
        ]);
        $this->tester->haveMerchant([
            MerchantTransfer::MERCHANT_REFERENCE => static::TEST_MERCHANT_REFERENCE_2,
            MerchantTransfer::STORE_RELATION => (new StoreRelationTransfer())->addStores($storeTransfer)->toArray(),
            MerchantTransfer::MERCHANT_PROFILE => (new MerchantProfileBuilder())->build()->toArray(),
        ]);
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return void
     */
    protected function createMerchantCommission(StoreTransfer $storeTransfer): void
    {
        $merchantCommissionGroupTransfer = $this->tester->haveMerchantCommissionGroup();
        $this->tester->haveMerchantCommission([
            MerchantCommissionTransfer::MERCHANT_COMMISSION_GROUP => $merchantCommissionGroupTransfer->toArray(),
            MerchantCommissionTransfer::NAME => static::TEST_MERCHANT_COMMISSION_NAME,
            MerchantCommissionTransfer::CALCULATOR_TYPE_PLUGIN => static::TEST_MERCHANT_COMMISSION_CALCULATOR_PLUGIN_TYPE,
            MerchantCommissionTransfer::AMOUNT => static::TEST_MERCHANT_COMMISSION_AMOUNT,
            MerchantCommissionTransfer::ITEM_CONDITION => static::TEST_MERCHANT_COMMISSION_ITEM_CONDITION,
            MerchantCommissionTransfer::ORDER_CONDITION => static::TEST_MERCHANT_COMMISSION_ORDER_CONDITION,
            MerchantCommissionTransfer::STORE_RELATION => (new StoreRelationTransfer())->addStores($storeTransfer)->toArray(),
        ]);
    }

    /**
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    protected function createOrderWithItems(): OrderTransfer
    {
        $this->tester->haveFullProduct(
            [ProductConcreteTransfer::SKU => static::TEST_PRODUCT_SKU],
            [ProductAbstractTransfer::SKU => static::TEST_PRODUCT_ABSTRACT_SKU],
        );

        $quoteTransfer = (new QuoteBuilder([QuoteTransfer::PRICE_MODE => static::TEST_ORDER_PRICE_MODE]))
            ->withTotals([TotalsTransfer::SUBTOTAL => (static::TEST_PRODUCT_PRICE_GROSS) * 4])
            ->withStore([StoreTransfer::NAME => static::STORE_NAME_DE])
            ->withCurrency([CurrencyTransfer::CODE => static::CURRENCY_CODE_EUR])
            ->withCustomer([CustomerTransfer::CUSTOMER_REFERENCE => static::TEST_CUSTOMER_REFERENCE])
            ->withBillingAddress()
            ->withItem([
                ItemTransfer::SKU => static::TEST_PRODUCT_SKU,
                ItemTransfer::ABSTRACT_SKU => static::TEST_PRODUCT_ABSTRACT_SKU,
                ItemTransfer::UNIT_PRICE => static::TEST_PRODUCT_PRICE_GROSS,
                ItemTransfer::UNIT_GROSS_PRICE => static::TEST_PRODUCT_PRICE_GROSS,
                ItemTransfer::UNIT_NET_PRICE => static::TEST_PRODUCT_PRICE_NET,
                ItemTransfer::SUM_PRICE => static::TEST_PRODUCT_PRICE_GROSS,
                ItemTransfer::SUM_GROSS_PRICE => static::TEST_PRODUCT_PRICE_GROSS,
                ItemTransfer::SUM_NET_PRICE => static::TEST_PRODUCT_PRICE_NET,
                ItemTransfer::QUANTITY => 1,
                ItemTransfer::MERCHANT_REFERENCE => static::TEST_MERCHANT_REFERENCE_1,
            ])
            ->withAnotherItem([
                ItemTransfer::SKU => static::TEST_PRODUCT_SKU,
                ItemTransfer::ABSTRACT_SKU => static::TEST_PRODUCT_ABSTRACT_SKU,
                ItemTransfer::UNIT_PRICE => static::TEST_PRODUCT_PRICE_GROSS,
                ItemTransfer::UNIT_GROSS_PRICE => static::TEST_PRODUCT_PRICE_GROSS,
                ItemTransfer::UNIT_NET_PRICE => static::TEST_PRODUCT_PRICE_NET,
                ItemTransfer::SUM_PRICE => static::TEST_PRODUCT_PRICE_GROSS,
                ItemTransfer::SUM_GROSS_PRICE => static::TEST_PRODUCT_PRICE_GROSS,
                ItemTransfer::SUM_NET_PRICE => static::TEST_PRODUCT_PRICE_NET,
                ItemTransfer::QUANTITY => 1,
                ItemTransfer::MERCHANT_REFERENCE => static::TEST_MERCHANT_REFERENCE_1,
            ])
            ->withAnotherItem([
                ItemTransfer::SKU => static::TEST_PRODUCT_SKU,
                ItemTransfer::ABSTRACT_SKU => static::TEST_PRODUCT_ABSTRACT_SKU,
                ItemTransfer::UNIT_PRICE => static::TEST_PRODUCT_PRICE_GROSS,
                ItemTransfer::UNIT_GROSS_PRICE => static::TEST_PRODUCT_PRICE_GROSS,
                ItemTransfer::UNIT_NET_PRICE => static::TEST_PRODUCT_PRICE_NET,
                ItemTransfer::SUM_PRICE => static::TEST_PRODUCT_PRICE_GROSS,
                ItemTransfer::SUM_GROSS_PRICE => static::TEST_PRODUCT_PRICE_GROSS,
                ItemTransfer::SUM_NET_PRICE => static::TEST_PRODUCT_PRICE_NET,
                ItemTransfer::QUANTITY => 1,
                ItemTransfer::MERCHANT_REFERENCE => static::TEST_MERCHANT_REFERENCE_2,
            ])
            ->withAnotherItem([
                ItemTransfer::SKU => static::TEST_PRODUCT_SKU,
                ItemTransfer::ABSTRACT_SKU => static::TEST_PRODUCT_ABSTRACT_SKU,
                ItemTransfer::UNIT_PRICE => static::TEST_PRODUCT_PRICE_GROSS,
                ItemTransfer::UNIT_GROSS_PRICE => static::TEST_PRODUCT_PRICE_GROSS,
                ItemTransfer::UNIT_NET_PRICE => static::TEST_PRODUCT_PRICE_NET,
                ItemTransfer::SUM_PRICE => static::TEST_PRODUCT_PRICE_GROSS,
                ItemTransfer::SUM_GROSS_PRICE => static::TEST_PRODUCT_PRICE_GROSS,
                ItemTransfer::SUM_NET_PRICE => static::TEST_PRODUCT_PRICE_NET,
                ItemTransfer::QUANTITY => 1,
                ItemTransfer::MERCHANT_REFERENCE => static::TEST_MERCHANT_REFERENCE_2,
            ])
            ->build();

        return $this->tester->createOrderFromQuoteTransfer($quoteTransfer);
    }

    /**
     * @param \Propel\Runtime\Collection\Collection<array-key, \Orm\Zed\Sales\Persistence\SpySalesOrderItem> $salesOrderItemEntities
     * @param int $idSalesOrderItem
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItem|null
     */
    protected function findSalesOrderItemByIdSalesOrderItem(Collection $salesOrderItemEntities, int $idSalesOrderItem): ?SpySalesOrderItem
    {
        foreach ($salesOrderItemEntities as $salesOrderItemEntity) {
            if ($salesOrderItemEntity->getIdSalesOrderItem() === $idSalesOrderItem) {
                return $salesOrderItemEntity;
            }
        }

        return null;
    }
}
