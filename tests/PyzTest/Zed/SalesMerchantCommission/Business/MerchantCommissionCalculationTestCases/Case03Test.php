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
use PyzTest\Zed\SalesMerchantCommission\SalesMerchantCommissionBusinessTester;

/**
 * Case 3: Apply separate commission for specific merchants
 *
 * @link https://spryker.atlassian.net/wiki/spaces/CCS/pages/4157341822/Commission+Calculation+in+Master+Merchant+OMS#Approved-Use-Case-3%3A-Apply-separate-commission-for-specific-merchants
 *
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Zed
 * @group SalesMerchantCommission
 * @group Business
 * @group MerchantCommissionCalculationTestCases
 * @group Case03Test
 * Add your own group annotations below this line
 */
class Case03Test extends Unit
{
    /**
     * @var string
     */
    protected const TEST_MERCHANT_COMMISSION_1_NAME = '5% for selected merchants';

    /**
     * @var string
     */
    protected const TEST_MERCHANT_COMMISSION_1_ORDER_CONDITION = 'price-mode = "GROSS_MODE"';

    /**
     * @var string
     */
    protected const TEST_MERCHANT_COMMISSION_1_ITEM_CONDITION = '';

    /**
     * @uses \Spryker\Zed\MerchantCommission\Communication\Plugin\MerchantCommission\PercentageMerchantCommissionCalculatorPlugin::CALCULATOR_TYPE
     *
     * @var string
     */
    protected const TEST_MERCHANT_COMMISSION_1_CALCULATOR_PLUGIN_TYPE = 'percentage';

    /**
     * @var int
     */
    protected const TEST_MERCHANT_COMMISSION_1_AMOUNT = 500;

    /**
     * @var int
     */
    protected const TEST_MERCHANT_COMMISSION_1_PRIORITY = 1;

    /**
     * @var string
     */
    protected const TEST_MERCHANT_COMMISSION_2_NAME = '10% for all merchants';

    /**
     * @var string
     */
    protected const TEST_MERCHANT_COMMISSION_2_ORDER_CONDITION = 'price-mode = "GROSS_MODE"';

    /**
     * @var string
     */
    protected const TEST_MERCHANT_COMMISSION_2_ITEM_CONDITION = '';

    /**
     * @uses \Spryker\Zed\MerchantCommission\Communication\Plugin\MerchantCommission\PercentageMerchantCommissionCalculatorPlugin::CALCULATOR_TYPE
     *
     * @var string
     */
    protected const TEST_MERCHANT_COMMISSION_2_CALCULATOR_PLUGIN_TYPE = 'percentage';

    /**
     * @var int
     */
    protected const TEST_MERCHANT_COMMISSION_2_AMOUNT = 1000;

    /**
     * @var int
     */
    protected const TEST_MERCHANT_COMMISSION_2_PRIORITY = 2;

    /**
     * @uses \Spryker\Shared\Calculation\CalculationPriceMode::PRICE_MODE_GROSS
     *
     * @var string
     */
    protected const TEST_ORDER_PRICE_MODE = 'GROSS_MODE';

    /**
     * @var string
     */
    protected const TEST_PRODUCT_1_ABSTRACT_SKU = 'CASE3_P1';

    /**
     * @var string
     */
    protected const TEST_PRODUCT_1_SKU = 'CASE3_P1_SKU';

    /**
     * @var int
     */
    protected const TEST_PRODUCT_1_PRICE_GROSS = 100000;

    /**
     * @var int
     */
    protected const TEST_PRODUCT_1_PRICE_NET = 50000;

    /**
     * @var string
     */
    protected const TEST_PRODUCT_2_ABSTRACT_SKU = 'CASE3_P2';

    /**
     * @var string
     */
    protected const TEST_PRODUCT_2_SKU = 'CASE3_P2_SKU';

    /**
     * @var int
     */
    protected const TEST_PRODUCT_2_PRICE_GROSS = 100000;

    /**
     * @var int
     */
    protected const TEST_PRODUCT_2_PRICE_NET = 50000;

    /**
     * @var string
     */
    protected const TEST_MERCHANT_REFERENCE_1 = 'CASE3_MR1';

    /**
     * @var string
     */
    protected const TEST_MERCHANT_REFERENCE_2 = 'CASE3_MR2';

    /**
     * @var string
     */
    protected const TEST_CUSTOMER_REFERENCE = 'CASE3_CR';

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
    protected const EXPECTED_MERCHANT_1_MERCHANT_COMMISSION_AMOUNT = 5000;

    /**
     * @var int
     */
    protected const EXPECTED_MERCHANT_2_MERCHANT_COMMISSION_AMOUNT = 10000;

    /**
     * @var int
     */
    protected const EXPECTED_TOTAL_MERCHANT_COMMISSION_AMOUNT = 30000;

    /**
     * @var int
     */
    protected const EXPECTED_MERCHANT_1_ORDER_TOTAL_MERCHANT_COMMISSION_AMOUNT = 10000;

    /**
     * @var int
     */
    protected const EXPECTED_MERCHANT_2_ORDER_TOTAL_MERCHANT_COMMISSION_AMOUNT = 20000;

    /**
     * @var array<string, int>
     */
    protected const EXPECTED_MERCHANT_COMMISSION_AMOUNT_MERCHANT_REFERENCE_MAP = [
        self::TEST_MERCHANT_REFERENCE_1 => self::EXPECTED_MERCHANT_1_MERCHANT_COMMISSION_AMOUNT,
        self::TEST_MERCHANT_REFERENCE_2 => self::EXPECTED_MERCHANT_2_MERCHANT_COMMISSION_AMOUNT,
    ];

    /**
     * @var array<string, int>
     */
    protected const EXPECTED_MERCHANT_COMMISSION_NAME_MERCHANT_REFERENCE_MAP = [
        self::TEST_MERCHANT_REFERENCE_1 => self::TEST_MERCHANT_COMMISSION_1_NAME,
        self::TEST_MERCHANT_REFERENCE_2 => self::TEST_MERCHANT_COMMISSION_2_NAME,
    ];

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
        [$merchant1Transfer, $merchant2Transfer] = $this->createMerchants($storeTransfer);
        $this->createMerchantCommissions($storeTransfer, $merchant1Transfer);

        $orderTransfer = $this->createOrderWithItems();

        // Act
        $this->tester->getFacade()->createSalesMerchantCommissions($orderTransfer);

        $persistedOrderTransfer = $this->tester->getSalesFacade()->findOrderByIdSalesOrder($orderTransfer->getIdSalesOrderOrFail());
        $this->tester->getMerchantSalesOrderFacade()->createMerchantOrderCollection($persistedOrderTransfer);

        // Assert
        $salesOrderEntity = $this->tester->getSalesOrderWithSalesOrderItems($orderTransfer->getIdSalesOrderOrFail());
        $salesOrderItemEntitiesIterator = $salesOrderEntity->getItems()->getIterator();
        $this->assertCount(4, $salesOrderItemEntitiesIterator);

        $this->assertSalesOrderItemMerchantCommission($salesOrderItemEntitiesIterator->current());
        $salesOrderItemEntitiesIterator->next();
        $this->assertSalesOrderItemMerchantCommission($salesOrderItemEntitiesIterator->current());
        $salesOrderItemEntitiesIterator->next();
        $this->assertSalesOrderItemMerchantCommission($salesOrderItemEntitiesIterator->current());
        $salesOrderItemEntitiesIterator->next();
        $this->assertSalesOrderItemMerchantCommission($salesOrderItemEntitiesIterator->current());

        $this->assertSame(
            static::EXPECTED_TOTAL_MERCHANT_COMMISSION_AMOUNT,
            $salesOrderEntity->getLastOrderTotals()->getMerchantCommissionTotal(),
        );

        $this->tester->assertMerchantSalesOrderTotals(
            $orderTransfer->getIdSalesOrderOrFail(),
            static::TEST_MERCHANT_REFERENCE_1,
            static::EXPECTED_MERCHANT_1_ORDER_TOTAL_MERCHANT_COMMISSION_AMOUNT,
        );
        $this->tester->assertMerchantSalesOrderTotals(
            $orderTransfer->getIdSalesOrderOrFail(),
            static::TEST_MERCHANT_REFERENCE_2,
            static::EXPECTED_MERCHANT_2_ORDER_TOTAL_MERCHANT_COMMISSION_AMOUNT,
        );
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem $salesOrderItemEntity
     *
     * @return void
     */
    protected function assertSalesOrderItemMerchantCommission(SpySalesOrderItem $salesOrderItemEntity): void
    {
        $expectedMerchantCommissionAmount = static::EXPECTED_MERCHANT_COMMISSION_AMOUNT_MERCHANT_REFERENCE_MAP[$salesOrderItemEntity->getMerchantReference()];
        $expectedMerchantCommissionName = static::EXPECTED_MERCHANT_COMMISSION_NAME_MERCHANT_REFERENCE_MAP[$salesOrderItemEntity->getMerchantReference()];

        $salesMerchantCommissionEntities = $this->tester->getSalesMerchantCommissionEntities($salesOrderItemEntity->getIdSalesOrderItem());
        $this->assertCount(1, $salesMerchantCommissionEntities);

        /** @var \Orm\Zed\SalesMerchantCommission\Persistence\SpySalesMerchantCommission $salesMerchantCommissionEntity */
        $salesMerchantCommissionEntity = $salesMerchantCommissionEntities->getIterator()->current();
        $this->assertSame($expectedMerchantCommissionName, $salesMerchantCommissionEntity->getName());
        $this->assertSame($expectedMerchantCommissionAmount, $salesMerchantCommissionEntity->getAmount());
        $this->assertSame($expectedMerchantCommissionAmount, $salesOrderItemEntity->getMerchantCommissionAmountAggregation());
        $this->assertSame($expectedMerchantCommissionAmount, $salesOrderItemEntity->getMerchantCommissionAmountFullAggregation());
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return list<\Generated\Shared\Transfer\MerchantTransfer>
     */
    protected function createMerchants(StoreTransfer $storeTransfer): array
    {
        $merchant1Transfer = $this->tester->haveMerchant([
            MerchantTransfer::MERCHANT_REFERENCE => static::TEST_MERCHANT_REFERENCE_1,
            MerchantTransfer::STORE_RELATION => (new StoreRelationTransfer())->addStores($storeTransfer)->toArray(),
            MerchantTransfer::MERCHANT_PROFILE => (new MerchantProfileBuilder())->build()->toArray(),
        ]);
        $merchant2Transfer = $this->tester->haveMerchant([
            MerchantTransfer::MERCHANT_REFERENCE => static::TEST_MERCHANT_REFERENCE_2,
            MerchantTransfer::STORE_RELATION => (new StoreRelationTransfer())->addStores($storeTransfer)->toArray(),
            MerchantTransfer::MERCHANT_PROFILE => (new MerchantProfileBuilder())->build()->toArray(),
        ]);

        return [$merchant1Transfer, $merchant2Transfer];
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return void
     */
    protected function createMerchantCommissions(
        StoreTransfer $storeTransfer,
        MerchantTransfer $merchantTransfer,
    ): void {
        $merchantCommissionGroupTransfer = $this->tester->haveMerchantCommissionGroup();

        $this->tester->haveMerchantCommission([
            MerchantCommissionTransfer::MERCHANT_COMMISSION_GROUP => $merchantCommissionGroupTransfer->toArray(),
            MerchantCommissionTransfer::NAME => static::TEST_MERCHANT_COMMISSION_1_NAME,
            MerchantCommissionTransfer::CALCULATOR_TYPE_PLUGIN => static::TEST_MERCHANT_COMMISSION_1_CALCULATOR_PLUGIN_TYPE,
            MerchantCommissionTransfer::AMOUNT => static::TEST_MERCHANT_COMMISSION_1_AMOUNT,
            MerchantCommissionTransfer::PRIORITY => static::TEST_MERCHANT_COMMISSION_1_PRIORITY,
            MerchantCommissionTransfer::ITEM_CONDITION => static::TEST_MERCHANT_COMMISSION_1_ITEM_CONDITION,
            MerchantCommissionTransfer::ORDER_CONDITION => static::TEST_MERCHANT_COMMISSION_1_ORDER_CONDITION,
            MerchantCommissionTransfer::STORE_RELATION => (new StoreRelationTransfer())->addStores($storeTransfer)->toArray(),
            MerchantCommissionTransfer::MERCHANTS => [$merchantTransfer->toArray()],
        ]);

        $this->tester->haveMerchantCommission([
            MerchantCommissionTransfer::MERCHANT_COMMISSION_GROUP => $merchantCommissionGroupTransfer->toArray(),
            MerchantCommissionTransfer::NAME => static::TEST_MERCHANT_COMMISSION_2_NAME,
            MerchantCommissionTransfer::CALCULATOR_TYPE_PLUGIN => static::TEST_MERCHANT_COMMISSION_2_CALCULATOR_PLUGIN_TYPE,
            MerchantCommissionTransfer::AMOUNT => static::TEST_MERCHANT_COMMISSION_2_AMOUNT,
            MerchantCommissionTransfer::PRIORITY => static::TEST_MERCHANT_COMMISSION_2_PRIORITY,
            MerchantCommissionTransfer::ITEM_CONDITION => static::TEST_MERCHANT_COMMISSION_2_ITEM_CONDITION,
            MerchantCommissionTransfer::ORDER_CONDITION => static::TEST_MERCHANT_COMMISSION_2_ORDER_CONDITION,
            MerchantCommissionTransfer::STORE_RELATION => (new StoreRelationTransfer())->addStores($storeTransfer)->toArray(),
        ]);
    }

    /**
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    protected function createOrderWithItems(): OrderTransfer
    {
        $this->tester->haveFullProduct(
            [ProductConcreteTransfer::SKU => static::TEST_PRODUCT_1_SKU],
            [ProductAbstractTransfer::SKU => static::TEST_PRODUCT_1_ABSTRACT_SKU],
        );
        $this->tester->haveFullProduct(
            [ProductConcreteTransfer::SKU => static::TEST_PRODUCT_2_SKU],
            [ProductAbstractTransfer::SKU => static::TEST_PRODUCT_2_ABSTRACT_SKU],
        );

        $quoteTransfer = (new QuoteBuilder([QuoteTransfer::PRICE_MODE => static::TEST_ORDER_PRICE_MODE]))
            ->withTotals([TotalsTransfer::SUBTOTAL => (static::TEST_PRODUCT_1_PRICE_GROSS + static::TEST_PRODUCT_2_PRICE_GROSS) * 2])
            ->withStore([StoreTransfer::NAME => static::STORE_NAME_DE])
            ->withCurrency([CurrencyTransfer::CODE => static::CURRENCY_CODE_EUR])
            ->withCustomer([CustomerTransfer::CUSTOMER_REFERENCE => static::TEST_CUSTOMER_REFERENCE])
            ->withBillingAddress()
            ->withItem([
                ItemTransfer::SKU => static::TEST_PRODUCT_1_SKU,
                ItemTransfer::ABSTRACT_SKU => static::TEST_PRODUCT_1_ABSTRACT_SKU,
                ItemTransfer::UNIT_PRICE => static::TEST_PRODUCT_1_PRICE_GROSS,
                ItemTransfer::UNIT_GROSS_PRICE => static::TEST_PRODUCT_1_PRICE_GROSS,
                ItemTransfer::UNIT_NET_PRICE => static::TEST_PRODUCT_1_PRICE_NET,
                ItemTransfer::SUM_PRICE => static::TEST_PRODUCT_1_PRICE_GROSS,
                ItemTransfer::SUM_GROSS_PRICE => static::TEST_PRODUCT_1_PRICE_GROSS,
                ItemTransfer::SUM_NET_PRICE => static::TEST_PRODUCT_1_PRICE_NET,
                ItemTransfer::QUANTITY => 1,
                ItemTransfer::MERCHANT_REFERENCE => static::TEST_MERCHANT_REFERENCE_1,
            ])
            ->withAnotherItem([
                ItemTransfer::SKU => static::TEST_PRODUCT_2_SKU,
                ItemTransfer::ABSTRACT_SKU => static::TEST_PRODUCT_2_ABSTRACT_SKU,
                ItemTransfer::UNIT_PRICE => static::TEST_PRODUCT_2_PRICE_GROSS,
                ItemTransfer::UNIT_GROSS_PRICE => static::TEST_PRODUCT_2_PRICE_GROSS,
                ItemTransfer::UNIT_NET_PRICE => static::TEST_PRODUCT_2_PRICE_NET,
                ItemTransfer::SUM_PRICE => static::TEST_PRODUCT_2_PRICE_GROSS,
                ItemTransfer::SUM_GROSS_PRICE => static::TEST_PRODUCT_2_PRICE_GROSS,
                ItemTransfer::SUM_NET_PRICE => static::TEST_PRODUCT_2_PRICE_NET,
                ItemTransfer::QUANTITY => 1,
                ItemTransfer::MERCHANT_REFERENCE => static::TEST_MERCHANT_REFERENCE_1,
            ])
            ->withAnotherItem([
                ItemTransfer::SKU => static::TEST_PRODUCT_1_SKU,
                ItemTransfer::ABSTRACT_SKU => static::TEST_PRODUCT_1_ABSTRACT_SKU,
                ItemTransfer::UNIT_PRICE => static::TEST_PRODUCT_1_PRICE_GROSS,
                ItemTransfer::UNIT_GROSS_PRICE => static::TEST_PRODUCT_1_PRICE_GROSS,
                ItemTransfer::UNIT_NET_PRICE => static::TEST_PRODUCT_1_PRICE_NET,
                ItemTransfer::SUM_PRICE => static::TEST_PRODUCT_1_PRICE_GROSS,
                ItemTransfer::SUM_GROSS_PRICE => static::TEST_PRODUCT_1_PRICE_GROSS,
                ItemTransfer::SUM_NET_PRICE => static::TEST_PRODUCT_1_PRICE_NET,
                ItemTransfer::QUANTITY => 1,
                ItemTransfer::MERCHANT_REFERENCE => static::TEST_MERCHANT_REFERENCE_2,
            ])
            ->withAnotherItem([
                ItemTransfer::SKU => static::TEST_PRODUCT_2_SKU,
                ItemTransfer::ABSTRACT_SKU => static::TEST_PRODUCT_2_ABSTRACT_SKU,
                ItemTransfer::UNIT_PRICE => static::TEST_PRODUCT_2_PRICE_GROSS,
                ItemTransfer::UNIT_GROSS_PRICE => static::TEST_PRODUCT_2_PRICE_GROSS,
                ItemTransfer::UNIT_NET_PRICE => static::TEST_PRODUCT_2_PRICE_NET,
                ItemTransfer::SUM_PRICE => static::TEST_PRODUCT_2_PRICE_GROSS,
                ItemTransfer::SUM_GROSS_PRICE => static::TEST_PRODUCT_2_PRICE_GROSS,
                ItemTransfer::SUM_NET_PRICE => static::TEST_PRODUCT_2_PRICE_NET,
                ItemTransfer::QUANTITY => 1,
                ItemTransfer::MERCHANT_REFERENCE => static::TEST_MERCHANT_REFERENCE_2,
            ])
            ->build();

        return $this->tester->createOrderFromQuoteTransfer($quoteTransfer);
    }
}
