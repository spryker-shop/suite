<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Zed\SalesMerchantCommission\Business\MerchantCommissionCalculationTestCases;

use Codeception\Test\Unit;
use DateTime;
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
 * Case 7: Apply primary commissions with equal priority, where both rules apply to the same item.
 *
 * @link https://spryker.atlassian.net/wiki/spaces/CCS/pages/4157341822/Commission+Calculation+in+Master+Merchant+OMS#Approved-Use-Case-7%3A-Apply-primary-commissions-with-equal-priority%2C-where-both-rules-apply-to-the-same-item.
 *
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Zed
 * @group SalesMerchantCommission
 * @group Business
 * @group MerchantCommissionCalculationTestCases
 * @group Case07Test
 * Add your own group annotations below this line
 */
class Case07Test extends Unit
{
    /**
     * @var string
     */
    protected const TEST_MERCHANT_COMMISSION_1_NAME = '20 fix for all';

    /**
     * @var string
     */
    protected const TEST_MERCHANT_COMMISSION_1_ORDER_CONDITION = 'price-mode = "NET_MODE"';

    /**
     * @var string
     */
    protected const TEST_MERCHANT_COMMISSION_1_ITEM_CONDITION = '';

    /**
     * @uses @uses \Spryker\Zed\MerchantCommission\Communication\Plugin\MerchantCommission\FixedMerchantCommissionCalculatorPlugin::CALCULATOR_TYPE
     *
     * @var string
     */
    protected const TEST_MERCHANT_COMMISSION_1_CALCULATOR_PLUGIN_TYPE = 'fixed';

    /**
     * @var int
     */
    protected const TEST_MERCHANT_COMMISSION_1_AMOUNT_GROSS = 2000;

    /**
     * @var int
     */
    protected const TEST_MERCHANT_COMMISSION_1_AMOUNT_NET = 2000;

    /**
     * @var int
     */
    protected const TEST_MERCHANT_COMMISSION_1_PRIORITY = 1;

    /**
     * @var string
     */
    protected const TEST_MERCHANT_COMMISSION_2_NAME = '50 fix for all';

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
    protected const TEST_MERCHANT_COMMISSION_2_AMOUNT_GROSS = 5000;

    /**
     * @var int
     */
    protected const TEST_MERCHANT_COMMISSION_2_AMOUNT_NET = 5000;

    /**
     * @uses \Spryker\Shared\Calculation\CalculationPriceMode::PRICE_MODE_NET
     *
     * @var string
     */
    protected const TEST_ORDER_PRICE_MODE = 'NET_MODE';

    /**
     * @var string
     */
    protected const TEST_PRODUCT_ABSTRACT_SKU = 'CASE7_P1';

    /**
     * @var string
     */
    protected const TEST_PRODUCT_SKU = 'CASE7_P1_SKU';

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
    protected const TEST_MERCHANT_REFERENCE = 'CASE7_MR1';

    /**
     * @var string
     */
    protected const TEST_CUSTOMER_REFERENCE = 'CASE7_CR';

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
    protected const EXPECTED_TOTAL_MERCHANT_COMMISSION_AMOUNT = 5000;

    /**
     * @var int
     */
    protected const EXPECTED_MERCHANT_ORDER_TOTAL_MERCHANT_COMMISSION_AMOUNT = 5000;

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
        $merchantTransfer = $this->createMerchant($storeTransfer);
        $this->createMerchantCommissions($storeTransfer, $merchantTransfer);

        $orderTransfer = $this->createOrderWithItems();

        // Act
        $this->tester->getFacade()->createSalesMerchantCommissions($orderTransfer);

        $persistedOrderTransfer = $this->tester->getSalesFacade()->findOrderByIdSalesOrder($orderTransfer->getIdSalesOrderOrFail());
        $this->tester->getMerchantSalesOrderFacade()->createMerchantOrderCollection($persistedOrderTransfer);

        // Assert
        $salesOrderEntity = $this->tester->getSalesOrderWithSalesOrderItems($orderTransfer->getIdSalesOrderOrFail());
        $this->assertCount(1, $salesOrderEntity->getItems());

        $this->assertSalesOrderItemMerchantCommission(
            $salesOrderEntity->getItems()->getFirst(),
            static::TEST_MERCHANT_COMMISSION_2_AMOUNT_NET,
        );

        $this->assertSame(
            static::EXPECTED_TOTAL_MERCHANT_COMMISSION_AMOUNT,
            $salesOrderEntity->getLastOrderTotals()->getMerchantCommissionTotal(),
        );

        $this->tester->assertMerchantSalesOrderTotals(
            $orderTransfer->getIdSalesOrderOrFail(),
            static::TEST_MERCHANT_REFERENCE,
            static::EXPECTED_MERCHANT_ORDER_TOTAL_MERCHANT_COMMISSION_AMOUNT,
        );
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem $salesOrderItemEntity
     * @param int $expectedMerchantCommissionAmount
     *
     * @return void
     */
    protected function assertSalesOrderItemMerchantCommission(SpySalesOrderItem $salesOrderItemEntity, int $expectedMerchantCommissionAmount): void
    {
        $salesMerchantCommissionEntities = $this->tester->getSalesMerchantCommissionEntities($salesOrderItemEntity->getIdSalesOrderItem());
        $this->assertCount(1, $salesMerchantCommissionEntities);

        /** @var \Orm\Zed\SalesMerchantCommission\Persistence\SpySalesMerchantCommission $salesMerchantCommissionEntity */
        $salesMerchantCommissionEntity = $salesMerchantCommissionEntities->getIterator()->current();
        $this->assertSame(static::TEST_MERCHANT_COMMISSION_2_NAME, $salesMerchantCommissionEntity->getName());
        $this->assertSame($expectedMerchantCommissionAmount, $salesMerchantCommissionEntity->getAmount());
        $this->assertSame($expectedMerchantCommissionAmount, $salesOrderItemEntity->getMerchantCommissionAmountAggregation());
        $this->assertSame($expectedMerchantCommissionAmount, $salesOrderItemEntity->getMerchantCommissionAmountFullAggregation());
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantTransfer
     */
    protected function createMerchant(StoreTransfer $storeTransfer): MerchantTransfer
    {
        return $this->tester->haveMerchant([
            MerchantTransfer::MERCHANT_REFERENCE => static::TEST_MERCHANT_REFERENCE,
            MerchantTransfer::STORE_RELATION => (new StoreRelationTransfer())->addStores($storeTransfer)->toArray(),
            MerchantTransfer::MERCHANT_PROFILE => (new MerchantProfileBuilder())->build()->toArray(),
        ]);
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return void
     */
    protected function createMerchantCommissions(StoreTransfer $storeTransfer, MerchantTransfer $merchantTransfer): void
    {
        $merchantCommissionGroupTransfer = $this->tester->haveMerchantCommissionGroup();
        $idCurrencyEur = $this->tester->haveCurrency([CurrencyTransfer::CODE => static::CURRENCY_CODE_EUR]);
        $this->tester->haveMerchantCommission([
            MerchantCommissionTransfer::MERCHANT_COMMISSION_GROUP => $merchantCommissionGroupTransfer->toArray(),
            MerchantCommissionTransfer::NAME => static::TEST_MERCHANT_COMMISSION_1_NAME,
            MerchantCommissionTransfer::CALCULATOR_TYPE_PLUGIN => static::TEST_MERCHANT_COMMISSION_1_CALCULATOR_PLUGIN_TYPE,
            MerchantCommissionTransfer::ITEM_CONDITION => static::TEST_MERCHANT_COMMISSION_1_ITEM_CONDITION,
            MerchantCommissionTransfer::ORDER_CONDITION => static::TEST_MERCHANT_COMMISSION_1_ORDER_CONDITION,
            MerchantCommissionTransfer::STORE_RELATION => (new StoreRelationTransfer())->addStores($storeTransfer)->toArray(),
            MerchantCommissionTransfer::CREATED_AT => (new DateTime('-1 hour'))->format('Y-m-d H:i:s'),
            MerchantCommissionTransfer::MERCHANTS => [$merchantTransfer->toArray()],
            MerchantCommissionTransfer::MERCHANT_COMMISSION_AMOUNTS => [
                [
                    MerchantCommissionAmountTransfer::CURRENCY => [CurrencyTransfer::ID_CURRENCY => $idCurrencyEur],
                    MerchantCommissionAmountTransfer::GROSS_AMOUNT => static::TEST_MERCHANT_COMMISSION_1_AMOUNT_GROSS,
                    MerchantCommissionAmountTransfer::NET_AMOUNT => static::TEST_MERCHANT_COMMISSION_1_AMOUNT_NET,
                ],
            ],
        ]);
        $this->tester->haveMerchantCommission([
            MerchantCommissionTransfer::MERCHANT_COMMISSION_GROUP => $merchantCommissionGroupTransfer->toArray(),
            MerchantCommissionTransfer::NAME => static::TEST_MERCHANT_COMMISSION_2_NAME,
            MerchantCommissionTransfer::CALCULATOR_TYPE_PLUGIN => static::TEST_MERCHANT_COMMISSION_2_CALCULATOR_PLUGIN_TYPE,
            MerchantCommissionTransfer::ITEM_CONDITION => static::TEST_MERCHANT_COMMISSION_2_ITEM_CONDITION,
            MerchantCommissionTransfer::ORDER_CONDITION => static::TEST_MERCHANT_COMMISSION_2_ORDER_CONDITION,
            MerchantCommissionTransfer::STORE_RELATION => (new StoreRelationTransfer())->addStores($storeTransfer)->toArray(),
            MerchantCommissionTransfer::CREATED_AT => (new DateTime('+1 hour'))->format('Y-m-d H:i:s'),
            MerchantCommissionTransfer::MERCHANTS => [$merchantTransfer->toArray()],
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
            ->withTotals([TotalsTransfer::SUBTOTAL => static::TEST_PRODUCT_PRICE_GROSS])
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
                ItemTransfer::SUM_PRICE => static::TEST_PRODUCT_PRICE_GROSS,
                ItemTransfer::SUM_GROSS_PRICE => static::TEST_PRODUCT_PRICE_GROSS,
                ItemTransfer::SUM_NET_PRICE => static::TEST_PRODUCT_PRICE_NET,
                ItemTransfer::QUANTITY => 1,
                ItemTransfer::MERCHANT_REFERENCE => static::TEST_MERCHANT_REFERENCE,
            ])
            ->build();

        return $this->tester->createOrderFromQuoteTransfer($quoteTransfer);
    }
}
