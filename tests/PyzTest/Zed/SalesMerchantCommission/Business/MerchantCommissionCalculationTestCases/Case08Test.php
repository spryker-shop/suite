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
use Generated\Shared\Transfer\MerchantCommissionAmountTransfer;
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
 * Case 8: Apply two commissions to items with qty > 1
 *
 * @link https://spryker.atlassian.net/wiki/spaces/CCS/pages/4157341822/Commission+Calculation+in+Master+Merchant+OMS#Approved-Use-Case-8%3A-Apply-two-commissions-to-items-with-qty-%3E-1
 *
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Zed
 * @group SalesMerchantCommission
 * @group Business
 * @group MerchantCommissionCalculationTestCases
 * @group Case08Test
 * Add your own group annotations below this line
 */
class Case08Test extends Unit
{
    /**
     * @var string
     */
    protected const TEST_MERCHANT_COMMISSION_1_NAME = '5% fix for all';

    /**
     * @var string
     */
    protected const TEST_MERCHANT_COMMISSION_1_ORDER_CONDITION = 'price-mode = "NET_MODE"';

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
    protected const TEST_MERCHANT_COMMISSION_2_NAME = '5 fix for all';

    /**
     * @var string
     */
    protected const TEST_MERCHANT_COMMISSION_2_ORDER_CONDITION = 'price-mode = "NET_MODE"';

    /**
     * @var string
     */
    protected const TEST_MERCHANT_COMMISSION_2_ITEM_CONDITION = '';

    /**
     * @uses @uses \Spryker\Zed\MerchantCommission\Communication\Plugin\MerchantCommission\FixedMerchantCommissionCalculatorPlugin::CALCULATOR_TYPE
     *
     * @var string
     */
    protected const TEST_MERCHANT_COMMISSION_2_CALCULATOR_PLUGIN_TYPE = 'fixed';

    /**
     * @var int
     */
    protected const TEST_MERCHANT_COMMISSION_2_PRIORITY = 1;

    /**
     * @var int
     */
    protected const TEST_MERCHANT_COMMISSION_2_AMOUNT_GROSS = 500;

    /**
     * @var int
     */
    protected const TEST_MERCHANT_COMMISSION_2_AMOUNT_NET = 500;

    /**
     * @uses \Spryker\Shared\Calculation\CalculationPriceMode::PRICE_MODE_NET
     *
     * @var string
     */
    protected const TEST_ORDER_PRICE_MODE = 'NET_MODE';

    /**
     * @var string
     */
    protected const TEST_PRODUCT_ABSTRACT_SKU = 'CASE8_P1';

    /**
     * @var string
     */
    protected const TEST_PRODUCT_SKU = 'CASE8_P1_SKU';

    /**
     * @var int
     */
    protected const TEST_PRODUCT_PRICE_GROSS = 1000;

    /**
     * @var int
     */
    protected const TEST_PRODUCT_PRICE_NET = 5000;

    /**
     * @var int
     */
    protected const TEST_PRODUCT_QUANTITY = 10;

    /**
     * @var string
     */
    protected const TEST_MERCHANT_REFERENCE = 'CASE8_MR1';

    /**
     * @var string
     */
    protected const TEST_CUSTOMER_REFERENCE = 'CASE8_CR';

    /**
     * @var string
     */
    protected const STORE_NAME_AT = 'AT';

    /**
     * @var string
     */
    protected const CURRENCY_CODE_EUR = 'EUR';

    /**
     * @var int
     */
    protected const EXPECTED_ITEM_MERCHANT_COMMISSION_1_AMOUNT = 2500;

    /**
     * @var int
     */
    protected const EXPECTED_ITEM_MERCHANT_COMMISSION_2_AMOUNT = 5000;

    /**
     * @var int
     */
    protected const EXPECTED_TOTAL_MERCHANT_COMMISSION_AMOUNT = 7500;

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
        $storeTransfer = $this->tester->haveStore([StoreTransfer::NAME => static::STORE_NAME_AT]);
        $this->createMerchant($storeTransfer);
        $this->createMerchantCommissions($storeTransfer);

        $orderTransfer = $this->createOrderWithItems();

        // Act
        $this->tester->getFacade()->createSalesMerchantCommissions($orderTransfer);

        $persistedOrderTransfer = $this->tester->getSalesFacade()->findOrderByIdSalesOrder($orderTransfer->getIdSalesOrderOrFail());
        $this->tester->getMerchantSalesOrderFacade()->createMerchantOrderCollection($persistedOrderTransfer);

        // Assert
        $salesOrderEntity = $this->tester->getSalesOrderWithSalesOrderItems($orderTransfer->getIdSalesOrderOrFail());
        $this->assertCount(1, $salesOrderEntity->getItems());

        $this->assertSalesOrderItemMerchantCommission($salesOrderEntity->getItems()->getFirst());

        $this->assertSame(
            static::EXPECTED_TOTAL_MERCHANT_COMMISSION_AMOUNT,
            $salesOrderEntity->getLastOrderTotals()->getMerchantCommissionTotal(),
        );

        $this->tester->assertMerchantSalesOrderTotals(
            $orderTransfer->getIdSalesOrderOrFail(),
            static::TEST_MERCHANT_REFERENCE,
            static::EXPECTED_TOTAL_MERCHANT_COMMISSION_AMOUNT,
        );
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem $salesOrderItemEntity
     *
     * @return void
     */
    protected function assertSalesOrderItemMerchantCommission(SpySalesOrderItem $salesOrderItemEntity): void
    {
        $salesMerchantCommissionEntities = $this->tester->getSalesMerchantCommissionEntities($salesOrderItemEntity->getIdSalesOrderItem());
        $this->assertCount(2, $salesMerchantCommissionEntities);

        $salesMerchantCommission1Entity = $this->tester->findSalesMerchantCommissionEntityByName($salesMerchantCommissionEntities, static::TEST_MERCHANT_COMMISSION_1_NAME);
        $salesMerchantCommission2Entity = $this->tester->findSalesMerchantCommissionEntityByName($salesMerchantCommissionEntities, static::TEST_MERCHANT_COMMISSION_2_NAME);

        $this->assertNotNull($salesMerchantCommission1Entity);
        $this->assertNotNull($salesMerchantCommission2Entity);
        $this->assertSame(static::EXPECTED_ITEM_MERCHANT_COMMISSION_1_AMOUNT, $salesMerchantCommission1Entity->getAmount());
        $this->assertSame(static::EXPECTED_ITEM_MERCHANT_COMMISSION_2_AMOUNT, $salesMerchantCommission2Entity->getAmount());

        $this->assertSame(static::EXPECTED_TOTAL_MERCHANT_COMMISSION_AMOUNT, $salesOrderItemEntity->getMerchantCommissionAmountAggregation());
        $this->assertSame(static::EXPECTED_TOTAL_MERCHANT_COMMISSION_AMOUNT, $salesOrderItemEntity->getMerchantCommissionAmountFullAggregation());
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return void
     */
    protected function createMerchant(StoreTransfer $storeTransfer): void
    {
        $this->tester->haveMerchant([
            MerchantTransfer::MERCHANT_REFERENCE => static::TEST_MERCHANT_REFERENCE,
            MerchantTransfer::STORE_RELATION => (new StoreRelationTransfer())->addStores($storeTransfer)->toArray(),
            MerchantTransfer::MERCHANT_PROFILE => (new MerchantProfileBuilder())->build()->toArray(),
        ]);
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return void
     */
    protected function createMerchantCommissions(StoreTransfer $storeTransfer): void
    {
        $merchantCommissionGroup1Transfer = $this->tester->haveMerchantCommissionGroup();
        $this->tester->haveMerchantCommission([
            MerchantCommissionTransfer::MERCHANT_COMMISSION_GROUP => $merchantCommissionGroup1Transfer->toArray(),
            MerchantCommissionTransfer::NAME => static::TEST_MERCHANT_COMMISSION_1_NAME,
            MerchantCommissionTransfer::CALCULATOR_TYPE_PLUGIN => static::TEST_MERCHANT_COMMISSION_1_CALCULATOR_PLUGIN_TYPE,
            MerchantCommissionTransfer::AMOUNT => static::TEST_MERCHANT_COMMISSION_1_AMOUNT,
            MerchantCommissionTransfer::PRIORITY => static::TEST_MERCHANT_COMMISSION_1_PRIORITY,
            MerchantCommissionTransfer::ITEM_CONDITION => static::TEST_MERCHANT_COMMISSION_1_ITEM_CONDITION,
            MerchantCommissionTransfer::ORDER_CONDITION => static::TEST_MERCHANT_COMMISSION_1_ORDER_CONDITION,
            MerchantCommissionTransfer::STORE_RELATION => (new StoreRelationTransfer())->addStores($storeTransfer)->toArray(),
        ]);

        $idCurrencyEur = $this->tester->haveCurrency([CurrencyTransfer::CODE => static::CURRENCY_CODE_EUR]);
        $merchantCommissionGroup2Transfer = $this->tester->haveMerchantCommissionGroup();
        $this->tester->haveMerchantCommission([
            MerchantCommissionTransfer::MERCHANT_COMMISSION_GROUP => $merchantCommissionGroup2Transfer->toArray(),
            MerchantCommissionTransfer::NAME => static::TEST_MERCHANT_COMMISSION_2_NAME,
            MerchantCommissionTransfer::CALCULATOR_TYPE_PLUGIN => static::TEST_MERCHANT_COMMISSION_2_CALCULATOR_PLUGIN_TYPE,
            MerchantCommissionTransfer::AMOUNT => 0,
            MerchantCommissionTransfer::PRIORITY => static::TEST_MERCHANT_COMMISSION_2_PRIORITY,
            MerchantCommissionTransfer::ITEM_CONDITION => static::TEST_MERCHANT_COMMISSION_2_ITEM_CONDITION,
            MerchantCommissionTransfer::ORDER_CONDITION => static::TEST_MERCHANT_COMMISSION_2_ORDER_CONDITION,
            MerchantCommissionTransfer::STORE_RELATION => (new StoreRelationTransfer())->addStores($storeTransfer)->toArray(),
            MerchantCommissionTransfer::MERCHANT_COMMISSION_AMOUNTS => [
                [
                    MerchantCommissionAmountTransfer::CURRENCY => [CurrencyTransfer::ID_CURRENCY => $idCurrencyEur],
                    MerchantCommissionAmountTransfer::GROSS_AMOUNT => static::TEST_MERCHANT_COMMISSION_2_AMOUNT_GROSS,
                    MerchantCommissionAmountTransfer::NET_AMOUNT => static::TEST_MERCHANT_COMMISSION_2_AMOUNT_NET,
                ],
            ],
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
            ->withTotals([TotalsTransfer::SUBTOTAL => static::TEST_PRODUCT_PRICE_GROSS * static::TEST_PRODUCT_QUANTITY])
            ->withStore([StoreTransfer::NAME => static::STORE_NAME_AT])
            ->withCurrency([CurrencyTransfer::CODE => static::CURRENCY_CODE_EUR])
            ->withCustomer([CustomerTransfer::CUSTOMER_REFERENCE => static::TEST_CUSTOMER_REFERENCE])
            ->withBillingAddress()
            ->withItem([
                ItemTransfer::SKU => static::TEST_PRODUCT_SKU,
                ItemTransfer::ABSTRACT_SKU => static::TEST_PRODUCT_ABSTRACT_SKU,
                ItemTransfer::UNIT_PRICE => static::TEST_PRODUCT_PRICE_GROSS,
                ItemTransfer::UNIT_GROSS_PRICE => static::TEST_PRODUCT_PRICE_GROSS,
                ItemTransfer::UNIT_NET_PRICE => static::TEST_PRODUCT_PRICE_NET,
                ItemTransfer::SUM_PRICE => static::TEST_PRODUCT_PRICE_NET * static::TEST_PRODUCT_QUANTITY,
                ItemTransfer::SUM_GROSS_PRICE => static::TEST_PRODUCT_PRICE_GROSS * static::TEST_PRODUCT_QUANTITY,
                ItemTransfer::SUM_NET_PRICE => static::TEST_PRODUCT_PRICE_NET * static::TEST_PRODUCT_QUANTITY,
                ItemTransfer::QUANTITY => static::TEST_PRODUCT_QUANTITY,
                ItemTransfer::MERCHANT_REFERENCE => static::TEST_MERCHANT_REFERENCE,
            ])
            ->build();

        return $this->tester->createOrderFromQuoteTransfer($quoteTransfer);
    }
}
