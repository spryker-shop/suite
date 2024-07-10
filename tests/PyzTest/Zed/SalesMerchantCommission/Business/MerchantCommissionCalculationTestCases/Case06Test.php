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
 * Case 6: Apply special fixed commission for 1 merchant in NET price mode
 *
 * @link https://spryker.atlassian.net/wiki/spaces/CCS/pages/4157341822/Commission+Calculation+in+Master+Merchant+OMS#Approved-Use-Case-6%3A-Apply-special-fixed-commission-for-1-merchant-in-NET-price-mode
 *
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Zed
 * @group SalesMerchantCommission
 * @group Business
 * @group MerchantCommissionCalculationTestCases
 * @group Case06Test
 * Add your own group annotations below this line
 */
class Case06Test extends Unit
{
    /**
     * @var string
     */
    protected const TEST_MERCHANT_COMMISSION_ORDER_CONDITION = 'price-mode = "NET_MODE"';

    /**
     * @var string
     */
    protected const TEST_MERCHANT_COMMISSION_ITEM_CONDITION = '';

    /**
     * @uses @uses \Spryker\Zed\MerchantCommission\Communication\Plugin\MerchantCommission\FixedMerchantCommissionCalculatorPlugin::CALCULATOR_TYPE
     *
     * @var string
     */
    protected const TEST_MERCHANT_COMMISSION_CALCULATOR_PLUGIN_TYPE = 'fixed';

    /**
     * @var int
     */
    protected const TEST_MERCHANT_COMMISSION_AMOUNT_GROSS = 2000;

    /**
     * @var int
     */
    protected const TEST_MERCHANT_COMMISSION_AMOUNT_NET = 2000;

    /**
     * @var string
     */
    protected const TEST_MERCHANT_COMMISSION_NAME = 'fixed 20 for Merchant1';

    /**
     * @uses \Spryker\Shared\Calculation\CalculationPriceMode::PRICE_MODE_NET
     *
     * @var string
     */
    protected const TEST_ORDER_PRICE_MODE = 'NET_MODE';

    /**
     * @var string
     */
    protected const TEST_PRODUCT_ABSTRACT_SKU = 'CASE6_P1';

    /**
     * @var string
     */
    protected const TEST_PRODUCT_SKU = 'CASE6_P1_SKU';

    /**
     * @var int
     */
    protected const TEST_PRODUCT_PRICE_GROSS = 10000;

    /**
     * @var int
     */
    protected const TEST_PRODUCT_PRICE_NET = 5000;

    /**
     * @var string
     */
    protected const TEST_MERCHANT_REFERENCE_1 = 'CASE6_MR1';

    /**
     * @var string
     */
    protected const TEST_MERCHANT_REFERENCE_2 = 'CASE6_MR2';

    /**
     * @var string
     */
    protected const TEST_CUSTOMER_REFERENCE = 'CASE6_CR';

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
    protected const EXPECTED_TOTAL_MERCHANT_COMMISSION_AMOUNT = 2000;

    /**
     * @var int
     */
    protected const EXPECTED_MERCHANT_1_ORDER_TOTAL_MERCHANT_COMMISSION_AMOUNT = 2000;

    /**
     * @var int
     */
    protected const EXPECTED_MERCHANT_2_ORDER_TOTAL_MERCHANT_COMMISSION_AMOUNT = 0;

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
        [$merchant1Transfer, $merchant2Transfer] = $this->createMerchants($storeTransfer);
        $this->createMerchantCommission($storeTransfer, $merchant1Transfer);

        $orderTransfer = $this->createOrderWithItems();

        // Act
        $this->tester->getFacade()->createSalesMerchantCommissions($orderTransfer);

        $persistedOrderTransfer = $this->tester->getSalesFacade()->findOrderByIdSalesOrder($orderTransfer->getIdSalesOrderOrFail());
        $this->tester->getMerchantSalesOrderFacade()->createMerchantOrderCollection($persistedOrderTransfer);

        // Assert
        $salesOrderEntity = $this->tester->getSalesOrderWithSalesOrderItems($orderTransfer->getIdSalesOrderOrFail());
        $this->assertCount(2, $salesOrderEntity->getItems());

        $this->assertSalesOrderItemMerchantCommission(
            $this->tester->findSalesOrderItemBySkuAndMerchantReference(
                $salesOrderEntity->getItems(),
                static::TEST_PRODUCT_SKU,
                static::TEST_MERCHANT_REFERENCE_1,
            ),
            1,
            static::TEST_MERCHANT_COMMISSION_AMOUNT_NET,
        );
        $this->assertSalesOrderItemMerchantCommission(
            $this->tester->findSalesOrderItemBySkuAndMerchantReference(
                $salesOrderEntity->getItems(),
                static::TEST_PRODUCT_SKU,
                static::TEST_MERCHANT_REFERENCE_2,
            ),
            0,
        );

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
     * @param int $expectedMerchantCommissionsCount
     * @param int|null $expectedMerchantCommissionAmount
     *
     * @return void
     */
    protected function assertSalesOrderItemMerchantCommission(
        SpySalesOrderItem $salesOrderItemEntity,
        int $expectedMerchantCommissionsCount,
        ?int $expectedMerchantCommissionAmount = null,
    ): void {
        $salesMerchantCommissionEntities = $this->tester->getSalesMerchantCommissionEntities($salesOrderItemEntity->getIdSalesOrderItem());
        $this->assertCount($expectedMerchantCommissionsCount, $salesMerchantCommissionEntities);
        if ($expectedMerchantCommissionsCount === 0) {
            return;
        }

        /** @var \Orm\Zed\SalesMerchantCommission\Persistence\SpySalesMerchantCommission $salesMerchantCommissionEntity */
        $salesMerchantCommissionEntity = $salesMerchantCommissionEntities->getIterator()->current();
        $this->assertSame(static::TEST_MERCHANT_COMMISSION_NAME, $salesMerchantCommissionEntity->getName());
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
        $merchants = [];
        $merchants[] = $this->tester->haveMerchant([
            MerchantTransfer::MERCHANT_REFERENCE => static::TEST_MERCHANT_REFERENCE_1,
            MerchantTransfer::STORE_RELATION => (new StoreRelationTransfer())->addStores($storeTransfer)->toArray(),
            MerchantTransfer::MERCHANT_PROFILE => (new MerchantProfileBuilder())->build()->toArray(),
        ]);
        $merchants[] = $this->tester->haveMerchant([
            MerchantTransfer::MERCHANT_REFERENCE => static::TEST_MERCHANT_REFERENCE_2,
            MerchantTransfer::STORE_RELATION => (new StoreRelationTransfer())->addStores($storeTransfer)->toArray(),
            MerchantTransfer::MERCHANT_PROFILE => (new MerchantProfileBuilder())->build()->toArray(),
        ]);

        return $merchants;
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return void
     */
    protected function createMerchantCommission(StoreTransfer $storeTransfer, MerchantTransfer $merchantTransfer): void
    {
        $merchantCommissionGroupTransfer = $this->tester->haveMerchantCommissionGroup();
        $idCurrencyEur = $this->tester->haveCurrency([CurrencyTransfer::CODE => static::CURRENCY_CODE_EUR]);
        $this->tester->haveMerchantCommission([
            MerchantCommissionTransfer::MERCHANT_COMMISSION_GROUP => $merchantCommissionGroupTransfer->toArray(),
            MerchantCommissionTransfer::NAME => static::TEST_MERCHANT_COMMISSION_NAME,
            MerchantCommissionTransfer::CALCULATOR_TYPE_PLUGIN => static::TEST_MERCHANT_COMMISSION_CALCULATOR_PLUGIN_TYPE,
            MerchantCommissionTransfer::ITEM_CONDITION => static::TEST_MERCHANT_COMMISSION_ITEM_CONDITION,
            MerchantCommissionTransfer::ORDER_CONDITION => static::TEST_MERCHANT_COMMISSION_ORDER_CONDITION,
            MerchantCommissionTransfer::STORE_RELATION => (new StoreRelationTransfer())->addStores($storeTransfer)->toArray(),
            MerchantCommissionTransfer::MERCHANTS => [$merchantTransfer->toArray()],
            MerchantCommissionTransfer::MERCHANT_COMMISSION_AMOUNTS => [
                [
                    MerchantCommissionAmountTransfer::CURRENCY => [CurrencyTransfer::ID_CURRENCY => $idCurrencyEur],
                    MerchantCommissionAmountTransfer::GROSS_AMOUNT => static::TEST_MERCHANT_COMMISSION_AMOUNT_GROSS,
                    MerchantCommissionAmountTransfer::NET_AMOUNT => static::TEST_MERCHANT_COMMISSION_AMOUNT_NET,
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
            ->withTotals([TotalsTransfer::SUBTOTAL => static::TEST_PRODUCT_PRICE_GROSS * 2])
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
            ->build();

        return $this->tester->createOrderFromQuoteTransfer($quoteTransfer);
    }
}
