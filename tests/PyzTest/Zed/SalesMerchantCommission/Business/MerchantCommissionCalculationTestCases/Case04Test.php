<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Zed\SalesMerchantCommission\Business\MerchantCommissionCalculationTestCases;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\MerchantProfileBuilder;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\MerchantCommissionAmountTransfer;
use Generated\Shared\Transfer\MerchantCommissionTransfer;
use Generated\Shared\Transfer\MerchantTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductCategoryTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use PyzTest\Zed\SalesMerchantCommission\SalesMerchantCommissionBusinessTester;

/**
 * Case 4: Apply commissions based on product category with standard commission needs as a fallback for all other product categories with an addition of an extra fee per item.
 *
 * @link https://spryker.atlassian.net/wiki/spaces/CCS/pages/4157341822/Commission+Calculation+in+Master+Merchant+OMS#Approved-Use-Case-4%3A-Apply-commissions-based-on-product-category-with-standard-commission-needs-as-a-fallback-for-all-other-product-categories-with-an-addition-of-an-extra-fee-per-item.
 *
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Zed
 * @group SalesMerchantCommission
 * @group Business
 * @group MerchantCommissionCalculationTestCases
 * @group Case04Test
 * Add your own group annotations below this line
 */
class Case04Test extends Unit
{
    /**
     * @var string
     */
    protected const TEST_MERCHANT_COMMISSION_1_NAME = '5% off in electronics with price >= 500';

    /**
     * @var string
     */
    protected const TEST_MERCHANT_COMMISSION_1_ORDER_CONDITION = 'price-mode = "GROSS_MODE"';

    /**
     * @var string
     */
    protected const TEST_MERCHANT_COMMISSION_1_ITEM_CONDITION = 'item-price >= "500" AND category IS IN "electronics"';

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
    protected const TEST_MERCHANT_COMMISSION_2_NAME = '15% off in electronics with price < 500';

    /**
     * @var string
     */
    protected const TEST_MERCHANT_COMMISSION_2_ORDER_CONDITION = 'price-mode = "GROSS_MODE"';

    /**
     * @var string
     */
    protected const TEST_MERCHANT_COMMISSION_2_ITEM_CONDITION = 'item-price < "500" AND category IS IN "electronics"';

    /**
     * @uses \Spryker\Zed\MerchantCommission\Communication\Plugin\MerchantCommission\PercentageMerchantCommissionCalculatorPlugin::CALCULATOR_TYPE
     *
     * @var string
     */
    protected const TEST_MERCHANT_COMMISSION_2_CALCULATOR_PLUGIN_TYPE = 'percentage';

    /**
     * @var int
     */
    protected const TEST_MERCHANT_COMMISSION_2_AMOUNT = 1500;

    /**
     * @var int
     */
    protected const TEST_MERCHANT_COMMISSION_2_PRIORITY = 2;

    /**
     * @var string
     */
    protected const TEST_MERCHANT_COMMISSION_3_NAME = '	10% off for all categories';

    /**
     * @var string
     */
    protected const TEST_MERCHANT_COMMISSION_3_ORDER_CONDITION = 'price-mode = "GROSS_MODE"';

    /**
     * @var string
     */
    protected const TEST_MERCHANT_COMMISSION_3_ITEM_CONDITION = '';

    /**
     * @uses \Spryker\Zed\MerchantCommission\Communication\Plugin\MerchantCommission\PercentageMerchantCommissionCalculatorPlugin::CALCULATOR_TYPE
     *
     * @var string
     */
    protected const TEST_MERCHANT_COMMISSION_3_CALCULATOR_PLUGIN_TYPE = 'percentage';

    /**
     * @var int
     */
    protected const TEST_MERCHANT_COMMISSION_3_AMOUNT = 1000;

    /**
     * @var int
     */
    protected const TEST_MERCHANT_COMMISSION_3_PRIORITY = 3;

    /**
     * @var string
     */
    protected const TEST_MERCHANT_COMMISSION_4_NAME = '0.5 per item fixed for all';

    /**
     * @var string
     */
    protected const TEST_MERCHANT_COMMISSION_4_ORDER_CONDITION = 'price-mode = "GROSS_MODE"';

    /**
     * @var string
     */
    protected const TEST_MERCHANT_COMMISSION_4_ITEM_CONDITION = '';

    /**
     * @uses \Spryker\Zed\MerchantCommission\Communication\Plugin\MerchantCommission\FixedMerchantCommissionCalculatorPlugin::CALCULATOR_TYPE
     *
     * @var string
     */
    protected const TEST_MERCHANT_COMMISSION_4_CALCULATOR_PLUGIN_TYPE = 'fixed';

    /**
     * @var int
     */
    protected const TEST_MERCHANT_COMMISSION_4_AMOUNT_GROSS = 50;

    /**
     * @var int
     */
    protected const TEST_MERCHANT_COMMISSION_4_AMOUNT_NET = 50;

    /**
     * @var int
     */
    protected const TEST_MERCHANT_COMMISSION_4_PRIORITY = 2;

    /**
     * @uses \Spryker\Shared\Calculation\CalculationPriceMode::PRICE_MODE_GROSS
     *
     * @var string
     */
    protected const TEST_ORDER_PRICE_MODE = 'GROSS_MODE';

    /**
     * @var string
     */
    protected const TEST_PRODUCT_1_ABSTRACT_SKU = 'CASE4_P1';

    /**
     * @var string
     */
    protected const TEST_PRODUCT_1_SKU = 'CASE4_P1_SKU';

    /**
     * @var int
     */
    protected const TEST_PRODUCT_1_PRICE_GROSS = 100000;

    /**
     * @var int
     */
    protected const TEST_PRODUCT_1_PRICE_NET = 50000;

    /**
     * @var int
     */
    protected const TEST_PRODUCT_1_QUANTITY = 2;

    /**
     * @var string
     */
    protected const TEST_PRODUCT_2_ABSTRACT_SKU = 'CASE4_P2';

    /**
     * @var string
     */
    protected const TEST_PRODUCT_2_SKU = 'CASE4_P2_SKU';

    /**
     * @var int
     */
    protected const TEST_PRODUCT_2_PRICE_GROSS = 10000;

    /**
     * @var int
     */
    protected const TEST_PRODUCT_2_PRICE_NET = 5000;

    /**
     * @var int
     */
    protected const TEST_PRODUCT_2_QUANTITY = 2;

    /**
     * @var string
     */
    protected const TEST_PRODUCT_3_ABSTRACT_SKU = 'CASE4_P3';

    /**
     * @var string
     */
    protected const TEST_PRODUCT_3_SKU = 'CASE4_P3_SKU';

    /**
     * @var int
     */
    protected const TEST_PRODUCT_3_PRICE_GROSS = 10000;

    /**
     * @var int
     */
    protected const TEST_PRODUCT_3_PRICE_NET = 5000;

    /**
     * @var int
     */
    protected const TEST_PRODUCT_3_QUANTITY = 2;

    /**
     * @var string
     */
    protected const TEST_MERCHANT_REFERENCE = 'CASE4_MR1';

    /**
     * @var string
     */
    protected const TEST_CATEGORY_KEY_ELECTRONICS = 'electronics';

    /**
     * @var string
     */
    protected const TEST_CATEGORY_KEY_WATCHES = 'watches';

    /**
     * @var string
     */
    protected const TEST_CUSTOMER_REFERENCE = 'CASE4_CR';

    /**
     * @var string
     */
    protected const STORE_NAME_DE = 'DE';

    /**
     * @var string
     */
    protected const CURRENCY_CODE_EUR = 'EUR';

    /**
     * @var string
     */
    protected const CURRENCY_CODE_CHF = 'CHF';

    /**
     * @var int
     */
    protected const EXPECTED_ITEM_1_MERCHANT_COMMISSION_1_AMOUNT = 10000;

    /**
     * @var int
     */
    protected const EXPECTED_ITEM_1_MERCHANT_COMMISSION_4_AMOUNT = 100;

    /**
     * @var int
     */
    protected const EXPECTED_ITEM_2_MERCHANT_COMMISSION_2_AMOUNT = 3000;

    /**
     * @var int
     */
    protected const EXPECTED_ITEM_2_MERCHANT_COMMISSION_4_AMOUNT = 100;

    /**
     * @var int
     */
    protected const EXPECTED_ITEM_3_MERCHANT_COMMISSION_3_AMOUNT = 2000;

    /**
     * @var int
     */
    protected const EXPECTED_ITEM_3_MERCHANT_COMMISSION_4_AMOUNT = 100;

    /**
     * @var int
     */
    protected const EXPECTED_TOTAL_MERCHANT_COMMISSION_AMOUNT = 15300;

    /**
     * @var array<string, int>
     */
    protected const EXPECTED_MERCHANT_COMMISSION_AGGREGATION_AMOUNT_PRODUCT_SKU_MAP = [
        self::TEST_PRODUCT_1_SKU => self::EXPECTED_ITEM_1_MERCHANT_COMMISSION_1_AMOUNT + self::EXPECTED_ITEM_1_MERCHANT_COMMISSION_4_AMOUNT,
        self::TEST_PRODUCT_2_SKU => self::EXPECTED_ITEM_2_MERCHANT_COMMISSION_2_AMOUNT + self::EXPECTED_ITEM_2_MERCHANT_COMMISSION_4_AMOUNT,
        self::TEST_PRODUCT_3_SKU => self::EXPECTED_ITEM_3_MERCHANT_COMMISSION_3_AMOUNT + self::EXPECTED_ITEM_3_MERCHANT_COMMISSION_4_AMOUNT,
    ];

    /**
     * @var array<string, array<string, int>>
     */
    protected const EXPECTED_MERCHANT_COMMISSIONS_PRODUCT_SKU_MAP = [
        self::TEST_PRODUCT_1_SKU => [
            self::TEST_MERCHANT_COMMISSION_1_NAME => self::EXPECTED_ITEM_1_MERCHANT_COMMISSION_1_AMOUNT,
            self::TEST_MERCHANT_COMMISSION_4_NAME => self::EXPECTED_ITEM_1_MERCHANT_COMMISSION_4_AMOUNT,
        ],
        self::TEST_PRODUCT_2_SKU => [
            self::TEST_MERCHANT_COMMISSION_2_NAME => self::EXPECTED_ITEM_2_MERCHANT_COMMISSION_2_AMOUNT,
            self::TEST_MERCHANT_COMMISSION_4_NAME => self::EXPECTED_ITEM_2_MERCHANT_COMMISSION_4_AMOUNT,
        ],
        self::TEST_PRODUCT_3_SKU => [
            self::TEST_MERCHANT_COMMISSION_3_NAME => self::EXPECTED_ITEM_3_MERCHANT_COMMISSION_3_AMOUNT,
            self::TEST_MERCHANT_COMMISSION_4_NAME => self::EXPECTED_ITEM_3_MERCHANT_COMMISSION_4_AMOUNT,
        ],
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
        $this->createMerchant($storeTransfer);
        $this->createMerchantCommissions($storeTransfer);

        $orderTransfer = $this->createOrderWithItems();

        // Act
        $this->tester->getFacade()->createSalesMerchantCommissions($orderTransfer);

        $persistedOrderTransfer = $this->tester->getSalesFacade()->findOrderByIdSalesOrder($orderTransfer->getIdSalesOrderOrFail());
        $this->tester->getMerchantSalesOrderFacade()->createMerchantOrderCollection($persistedOrderTransfer);

        // Assert
        $salesOrderEntity = $this->tester->getSalesOrderWithSalesOrderItems($orderTransfer->getIdSalesOrderOrFail());
        $salesOrderItemEntitiesIterator = $salesOrderEntity->getItems()->getIterator();
        $this->assertCount(3, $salesOrderItemEntitiesIterator);

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
        $expectedMerchantCommissionAmountsIndexedByMerchantCommissionName = static::EXPECTED_MERCHANT_COMMISSIONS_PRODUCT_SKU_MAP[$salesOrderItemEntity->getSku()];
        $expectedMerchantCommissionAggregationAmount = static::EXPECTED_MERCHANT_COMMISSION_AGGREGATION_AMOUNT_PRODUCT_SKU_MAP[$salesOrderItemEntity->getSku()];

        $salesMerchantCommissionEntities = $this->tester->getSalesMerchantCommissionEntities($salesOrderItemEntity->getIdSalesOrderItem());
        $this->assertCount(2, $salesMerchantCommissionEntities);

        foreach ($expectedMerchantCommissionAmountsIndexedByMerchantCommissionName as $expectedMerchantCommissionName => $expectedMerchantCommissionAmount) {
            $salesMerchantCommissionEntity = $this->tester->findSalesMerchantCommissionEntityByName($salesMerchantCommissionEntities, $expectedMerchantCommissionName);
            $this->assertNotNull($salesMerchantCommissionEntity);
            $this->assertSame($expectedMerchantCommissionAmount, $salesMerchantCommissionEntity->getAmount());
        }

        $this->assertSame($expectedMerchantCommissionAggregationAmount, $salesOrderItemEntity->getMerchantCommissionAmountAggregation());
        $this->assertSame($expectedMerchantCommissionAggregationAmount, $salesOrderItemEntity->getMerchantCommissionAmountFullAggregation());
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
        $this->tester->haveMerchantCommission([
            MerchantCommissionTransfer::MERCHANT_COMMISSION_GROUP => $merchantCommissionGroup1Transfer->toArray(),
            MerchantCommissionTransfer::NAME => static::TEST_MERCHANT_COMMISSION_2_NAME,
            MerchantCommissionTransfer::CALCULATOR_TYPE_PLUGIN => static::TEST_MERCHANT_COMMISSION_2_CALCULATOR_PLUGIN_TYPE,
            MerchantCommissionTransfer::AMOUNT => static::TEST_MERCHANT_COMMISSION_2_AMOUNT,
            MerchantCommissionTransfer::PRIORITY => static::TEST_MERCHANT_COMMISSION_2_PRIORITY,
            MerchantCommissionTransfer::ITEM_CONDITION => static::TEST_MERCHANT_COMMISSION_2_ITEM_CONDITION,
            MerchantCommissionTransfer::ORDER_CONDITION => static::TEST_MERCHANT_COMMISSION_2_ORDER_CONDITION,
            MerchantCommissionTransfer::STORE_RELATION => (new StoreRelationTransfer())->addStores($storeTransfer)->toArray(),
        ]);
        $this->tester->haveMerchantCommission([
            MerchantCommissionTransfer::MERCHANT_COMMISSION_GROUP => $merchantCommissionGroup1Transfer->toArray(),
            MerchantCommissionTransfer::NAME => static::TEST_MERCHANT_COMMISSION_3_NAME,
            MerchantCommissionTransfer::CALCULATOR_TYPE_PLUGIN => static::TEST_MERCHANT_COMMISSION_3_CALCULATOR_PLUGIN_TYPE,
            MerchantCommissionTransfer::AMOUNT => static::TEST_MERCHANT_COMMISSION_3_AMOUNT,
            MerchantCommissionTransfer::PRIORITY => static::TEST_MERCHANT_COMMISSION_3_PRIORITY,
            MerchantCommissionTransfer::ITEM_CONDITION => static::TEST_MERCHANT_COMMISSION_3_ITEM_CONDITION,
            MerchantCommissionTransfer::ORDER_CONDITION => static::TEST_MERCHANT_COMMISSION_3_ORDER_CONDITION,
            MerchantCommissionTransfer::STORE_RELATION => (new StoreRelationTransfer())->addStores($storeTransfer)->toArray(),
        ]);

        $idCurrencyEur = $this->tester->haveCurrency([CurrencyTransfer::CODE => static::CURRENCY_CODE_EUR]);
        $idCurrencyChf = $this->tester->haveCurrency([CurrencyTransfer::CODE => static::CURRENCY_CODE_CHF]);
        $merchantCommissionGroup2Transfer = $this->tester->haveMerchantCommissionGroup();
        $this->tester->haveMerchantCommission([
            MerchantCommissionTransfer::MERCHANT_COMMISSION_GROUP => $merchantCommissionGroup2Transfer->toArray(),
            MerchantCommissionTransfer::NAME => static::TEST_MERCHANT_COMMISSION_4_NAME,
            MerchantCommissionTransfer::CALCULATOR_TYPE_PLUGIN => static::TEST_MERCHANT_COMMISSION_4_CALCULATOR_PLUGIN_TYPE,
            MerchantCommissionTransfer::AMOUNT => 0,
            MerchantCommissionTransfer::PRIORITY => static::TEST_MERCHANT_COMMISSION_4_PRIORITY,
            MerchantCommissionTransfer::ITEM_CONDITION => static::TEST_MERCHANT_COMMISSION_4_ITEM_CONDITION,
            MerchantCommissionTransfer::ORDER_CONDITION => static::TEST_MERCHANT_COMMISSION_4_ORDER_CONDITION,
            MerchantCommissionTransfer::STORE_RELATION => (new StoreRelationTransfer())->addStores($storeTransfer)->toArray(),
            MerchantCommissionTransfer::MERCHANT_COMMISSION_AMOUNTS => [
                [
                    MerchantCommissionAmountTransfer::CURRENCY => [CurrencyTransfer::ID_CURRENCY => $idCurrencyEur],
                    MerchantCommissionAmountTransfer::GROSS_AMOUNT => static::TEST_MERCHANT_COMMISSION_4_AMOUNT_GROSS,
                    MerchantCommissionAmountTransfer::NET_AMOUNT => static::TEST_MERCHANT_COMMISSION_4_AMOUNT_NET,
                ],
                [
                    MerchantCommissionAmountTransfer::CURRENCY => [CurrencyTransfer::ID_CURRENCY => $idCurrencyChf],
                    MerchantCommissionAmountTransfer::GROSS_AMOUNT => static::TEST_MERCHANT_COMMISSION_4_AMOUNT_GROSS,
                    MerchantCommissionAmountTransfer::NET_AMOUNT => static::TEST_MERCHANT_COMMISSION_4_AMOUNT_NET,
                ],
            ],
        ]);
    }

    /**
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    protected function createOrderWithItems(): OrderTransfer
    {
        $categoryElectronicsTransfer = $this->tester->haveLocalizedCategory([
            CategoryTransfer::CATEGORY_KEY => static::TEST_CATEGORY_KEY_ELECTRONICS,
            CategoryTransfer::NAME => ucfirst(static::TEST_CATEGORY_KEY_ELECTRONICS),
        ]);

        $categoryWatchesTransfer = $this->tester->haveLocalizedCategory([
            CategoryTransfer::CATEGORY_KEY => static::TEST_CATEGORY_KEY_WATCHES,
            CategoryTransfer::NAME => ucfirst(static::TEST_CATEGORY_KEY_WATCHES),
        ]);

        $productConcrete1Transfer = $this->tester->haveFullProduct(
            [ProductConcreteTransfer::SKU => static::TEST_PRODUCT_1_SKU],
            [ProductAbstractTransfer::SKU => static::TEST_PRODUCT_1_ABSTRACT_SKU],
        );
        $productConcrete2Transfer = $this->tester->haveFullProduct(
            [ProductConcreteTransfer::SKU => static::TEST_PRODUCT_2_SKU],
            [ProductAbstractTransfer::SKU => static::TEST_PRODUCT_2_ABSTRACT_SKU],
        );
        $productConcrete3Transfer = $this->tester->haveFullProduct(
            [ProductConcreteTransfer::SKU => static::TEST_PRODUCT_3_SKU],
            [ProductAbstractTransfer::SKU => static::TEST_PRODUCT_3_ABSTRACT_SKU],
        );

        $this->tester->haveProductCategoryForCategory($categoryElectronicsTransfer->getIdCategoryOrFail(), [
            ProductCategoryTransfer::FK_PRODUCT_ABSTRACT => $productConcrete1Transfer->getFkProductAbstractOrFail(),
        ]);
        $this->tester->haveProductCategoryForCategory($categoryElectronicsTransfer->getIdCategoryOrFail(), [
            ProductCategoryTransfer::FK_PRODUCT_ABSTRACT => $productConcrete2Transfer->getFkProductAbstractOrFail(),
        ]);
        $this->tester->haveProductCategoryForCategory($categoryWatchesTransfer->getIdCategoryOrFail(), [
            ProductCategoryTransfer::FK_PRODUCT_ABSTRACT => $productConcrete3Transfer->getFkProductAbstractOrFail(),
        ]);

        $quoteTransfer = (new QuoteBuilder([QuoteTransfer::PRICE_MODE => static::TEST_ORDER_PRICE_MODE]))
            ->withTotals([
                TotalsTransfer::SUBTOTAL => static::TEST_PRODUCT_1_PRICE_GROSS * static::TEST_PRODUCT_1_QUANTITY
                    + static::TEST_PRODUCT_2_PRICE_GROSS * static::TEST_PRODUCT_2_QUANTITY
                    + static::TEST_PRODUCT_3_PRICE_GROSS * static::TEST_PRODUCT_3_QUANTITY,
            ])
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
                ItemTransfer::SUM_PRICE => static::TEST_PRODUCT_1_PRICE_GROSS * static::TEST_PRODUCT_1_QUANTITY,
                ItemTransfer::SUM_GROSS_PRICE => static::TEST_PRODUCT_1_PRICE_GROSS * static::TEST_PRODUCT_1_QUANTITY,
                ItemTransfer::SUM_NET_PRICE => static::TEST_PRODUCT_1_PRICE_NET * static::TEST_PRODUCT_1_QUANTITY,
                ItemTransfer::QUANTITY => static::TEST_PRODUCT_1_QUANTITY,
                ItemTransfer::MERCHANT_REFERENCE => static::TEST_MERCHANT_REFERENCE,
            ])
            ->withAnotherItem([
                ItemTransfer::SKU => static::TEST_PRODUCT_2_SKU,
                ItemTransfer::ABSTRACT_SKU => static::TEST_PRODUCT_2_ABSTRACT_SKU,
                ItemTransfer::UNIT_PRICE => static::TEST_PRODUCT_2_PRICE_GROSS,
                ItemTransfer::UNIT_GROSS_PRICE => static::TEST_PRODUCT_2_PRICE_GROSS,
                ItemTransfer::UNIT_NET_PRICE => static::TEST_PRODUCT_2_PRICE_NET,
                ItemTransfer::SUM_PRICE => static::TEST_PRODUCT_2_PRICE_GROSS * static::TEST_PRODUCT_2_QUANTITY,
                ItemTransfer::SUM_GROSS_PRICE => static::TEST_PRODUCT_2_PRICE_GROSS * static::TEST_PRODUCT_2_QUANTITY,
                ItemTransfer::SUM_NET_PRICE => static::TEST_PRODUCT_2_PRICE_NET * static::TEST_PRODUCT_2_QUANTITY,
                ItemTransfer::QUANTITY => static::TEST_PRODUCT_2_QUANTITY,
                ItemTransfer::MERCHANT_REFERENCE => static::TEST_MERCHANT_REFERENCE,
            ])
            ->withAnotherItem([
                ItemTransfer::SKU => static::TEST_PRODUCT_3_SKU,
                ItemTransfer::ABSTRACT_SKU => static::TEST_PRODUCT_3_ABSTRACT_SKU,
                ItemTransfer::UNIT_PRICE => static::TEST_PRODUCT_3_PRICE_GROSS,
                ItemTransfer::UNIT_GROSS_PRICE => static::TEST_PRODUCT_3_PRICE_GROSS,
                ItemTransfer::UNIT_NET_PRICE => static::TEST_PRODUCT_3_PRICE_NET,
                ItemTransfer::SUM_PRICE => static::TEST_PRODUCT_3_PRICE_GROSS * static::TEST_PRODUCT_3_QUANTITY,
                ItemTransfer::SUM_GROSS_PRICE => static::TEST_PRODUCT_3_PRICE_GROSS * static::TEST_PRODUCT_3_QUANTITY,
                ItemTransfer::SUM_NET_PRICE => static::TEST_PRODUCT_3_PRICE_NET * static::TEST_PRODUCT_3_QUANTITY,
                ItemTransfer::QUANTITY => static::TEST_PRODUCT_3_QUANTITY,
                ItemTransfer::MERCHANT_REFERENCE => static::TEST_MERCHANT_REFERENCE,
            ])
            ->build();

        return $this->tester->createOrderFromQuoteTransfer($quoteTransfer);
    }
}
