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
 * Case 9: Apply commission for categories and child categories
 *
 * https://spryker.atlassian.net/wiki/spaces/CCS/pages/4157341822/Commission+Calculation+in+Master+Merchant+OMS#Approved-Use-Case-9%3A-Apply-commission-for-categories-and-child-categories
 *
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Zed
 * @group SalesMerchantCommission
 * @group Business
 * @group MerchantCommissionCalculationTestCases
 * @group Case09Test
 * Add your own group annotations below this line
 */
class Case09Test extends Unit
{
    /**
     * @var string
     */
    protected const TEST_MERCHANT_COMMISSION_1_NAME = '10% for electronics';

    /**
     * @var string
     */
    protected const TEST_MERCHANT_COMMISSION_1_ORDER_CONDITION = 'price-mode = "GROSS_MODE"';

    /**
     * @var string
     */
    protected const TEST_MERCHANT_COMMISSION_1_ITEM_CONDITION = 'category = "electronics"';

    /**
     * @uses \Spryker\Zed\MerchantCommission\Communication\Plugin\MerchantCommission\PercentageMerchantCommissionCalculatorPlugin::CALCULATOR_TYPE
     *
     * @var string
     */
    protected const TEST_MERCHANT_COMMISSION_1_CALCULATOR_PLUGIN_TYPE = 'percentage';

    /**
     * @var int
     */
    protected const TEST_MERCHANT_COMMISSION_1_AMOUNT = 1000;

    /**
     * @var int
     */
    protected const TEST_MERCHANT_COMMISSION_1_PRIORITY = 2;

    /**
     * @var string
     */
    protected const TEST_MERCHANT_COMMISSION_2_NAME = '5% for smartphones';

    /**
     * @var string
     */
    protected const TEST_MERCHANT_COMMISSION_2_ORDER_CONDITION = 'price-mode = "GROSS_MODE"';

    /**
     * @var string
     */
    protected const TEST_MERCHANT_COMMISSION_2_ITEM_CONDITION = 'category = "smartphone"';

    /**
     * @uses \Spryker\Zed\MerchantCommission\Communication\Plugin\MerchantCommission\PercentageMerchantCommissionCalculatorPlugin::CALCULATOR_TYPE
     *
     * @var string
     */
    protected const TEST_MERCHANT_COMMISSION_2_CALCULATOR_PLUGIN_TYPE = 'percentage';

    /**
     * @var int
     */
    protected const TEST_MERCHANT_COMMISSION_2_AMOUNT = 500;

    /**
     * @var int
     */
    protected const TEST_MERCHANT_COMMISSION_2_PRIORITY = 1;

    /**
     * @uses \Spryker\Shared\Calculation\CalculationPriceMode::PRICE_MODE_GROSS
     *
     * @var string
     */
    protected const TEST_ORDER_PRICE_MODE = 'GROSS_MODE';

    /**
     * @var string
     */
    protected const TEST_PRODUCT_1_ABSTRACT_SKU = 'CASE9_P1';

    /**
     * @var string
     */
    protected const TEST_PRODUCT_1_SKU = 'CASE9_P1_SKU';

    /**
     * @var int
     */
    protected const TEST_PRODUCT_1_PRICE_GROSS = 10000;

    /**
     * @var int
     */
    protected const TEST_PRODUCT_1_PRICE_NET = 1000;

    /**
     * @var string
     */
    protected const TEST_PRODUCT_2_ABSTRACT_SKU = 'CASE9_P2';

    /**
     * @var string
     */
    protected const TEST_PRODUCT_2_SKU = 'CASE9_P2_SKU';

    /**
     * @var int
     */
    protected const TEST_PRODUCT_2_PRICE_GROSS = 10000;

    /**
     * @var int
     */
    protected const TEST_PRODUCT_2_PRICE_NET = 1000;

    /**
     * @var string
     */
    protected const TEST_PRODUCT_3_ABSTRACT_SKU = 'CASE9_P3';

    /**
     * @var string
     */
    protected const TEST_PRODUCT_3_SKU = 'CASE9_P3_SKU';

    /**
     * @var int
     */
    protected const TEST_PRODUCT_3_PRICE_GROSS = 10000;

    /**
     * @var int
     */
    protected const TEST_PRODUCT_3_PRICE_NET = 1000;

    /**
     * @var string
     */
    protected const TEST_MERCHANT_REFERENCE = 'CASE9_MR1';

    /**
     * @var string
     */
    protected const TEST_CATEGORY_KEY_ELECTRONICS = 'electronics';

    /**
     * @var string
     */
    protected const TEST_CATEGORY_KEY_SMARTPHONE = 'smartphone';

    /**
     * @var string
     */
    protected const TEST_CATEGORY_KEY_SMARTWATCH = 'smartwatch';

    /**
     * @var string
     */
    protected const TEST_CUSTOMER_REFERENCE = 'CASE9_CR';

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
    protected const EXPECTED_ITEM_1_MERCHANT_COMMISSION_AMOUNT = 1000;

    /**
     * @var int
     */
    protected const EXPECTED_ITEM_2_MERCHANT_COMMISSION_AMOUNT = 500;

    /**
     * @var int
     */
    protected const EXPECTED_ITEM_3_MERCHANT_COMMISSION_AMOUNT = 1000;

    /**
     * @var int
     */
    protected const EXPECTED_TOTAL_MERCHANT_COMMISSION_AMOUNT = 2500;

    /**
     * @var array<string, int>
     */
    protected const EXPECTED_MERCHANT_COMMISSION_AMOUNT_PRODUCT_SKU_MAP = [
        self::TEST_PRODUCT_1_SKU => self::EXPECTED_ITEM_1_MERCHANT_COMMISSION_AMOUNT,
        self::TEST_PRODUCT_2_SKU => self::EXPECTED_ITEM_2_MERCHANT_COMMISSION_AMOUNT,
        self::TEST_PRODUCT_3_SKU => self::EXPECTED_ITEM_1_MERCHANT_COMMISSION_AMOUNT,
    ];

    /**
     * @var array<string, int>
     */
    protected const EXPECTED_MERCHANT_COMMISSION_NAME_PRODUCT_SKU_MAP = [
        self::TEST_PRODUCT_1_SKU => self::TEST_MERCHANT_COMMISSION_1_NAME,
        self::TEST_PRODUCT_2_SKU => self::TEST_MERCHANT_COMMISSION_2_NAME,
        self::TEST_PRODUCT_3_SKU => self::TEST_MERCHANT_COMMISSION_1_NAME,
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
        $expectedMerchantCommissionAmount = static::EXPECTED_MERCHANT_COMMISSION_AMOUNT_PRODUCT_SKU_MAP[$salesOrderItemEntity->getSku()];
        $expectedMerchantCommissionName = static::EXPECTED_MERCHANT_COMMISSION_NAME_PRODUCT_SKU_MAP[$salesOrderItemEntity->getSku()];

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

        $categorySmartphonesTransfer = $this->tester->haveLocalizedCategory([
            CategoryTransfer::CATEGORY_KEY => static::TEST_CATEGORY_KEY_SMARTPHONE,
            CategoryTransfer::NAME => ucfirst(static::TEST_CATEGORY_KEY_SMARTPHONE),
            CategoryTransfer::PARENT_CATEGORY_NODE => $categoryElectronicsTransfer->getCategoryNodeOrFail(),
        ]);

        $categorySmartwatchesTransfer = $this->tester->haveLocalizedCategory([
            CategoryTransfer::CATEGORY_KEY => static::TEST_CATEGORY_KEY_SMARTWATCH,
            CategoryTransfer::NAME => ucfirst(static::TEST_CATEGORY_KEY_SMARTWATCH),
            CategoryTransfer::PARENT_CATEGORY_NODE => $categoryElectronicsTransfer->getCategoryNodeOrFail(),
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
        $this->tester->haveProductCategoryForCategory($categorySmartphonesTransfer->getIdCategoryOrFail(), [
            ProductCategoryTransfer::FK_PRODUCT_ABSTRACT => $productConcrete2Transfer->getFkProductAbstractOrFail(),
        ]);
        $this->tester->haveProductCategoryForCategory($categorySmartwatchesTransfer->getIdCategoryOrFail(), [
            ProductCategoryTransfer::FK_PRODUCT_ABSTRACT => $productConcrete3Transfer->getFkProductAbstractOrFail(),
        ]);

        $quoteTransfer = (new QuoteBuilder([QuoteTransfer::PRICE_MODE => static::TEST_ORDER_PRICE_MODE]))
            ->withTotals([TotalsTransfer::SUBTOTAL => static::TEST_PRODUCT_1_PRICE_GROSS + static::TEST_PRODUCT_2_PRICE_GROSS + static::TEST_PRODUCT_3_PRICE_GROSS])
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
                ItemTransfer::MERCHANT_REFERENCE => static::TEST_MERCHANT_REFERENCE,
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
                ItemTransfer::MERCHANT_REFERENCE => static::TEST_MERCHANT_REFERENCE,
            ])
            ->withAnotherItem([
                ItemTransfer::SKU => static::TEST_PRODUCT_3_SKU,
                ItemTransfer::ABSTRACT_SKU => static::TEST_PRODUCT_3_ABSTRACT_SKU,
                ItemTransfer::UNIT_PRICE => static::TEST_PRODUCT_3_PRICE_GROSS,
                ItemTransfer::UNIT_GROSS_PRICE => static::TEST_PRODUCT_3_PRICE_GROSS,
                ItemTransfer::UNIT_NET_PRICE => static::TEST_PRODUCT_3_PRICE_NET,
                ItemTransfer::SUM_PRICE => static::TEST_PRODUCT_3_PRICE_GROSS,
                ItemTransfer::SUM_GROSS_PRICE => static::TEST_PRODUCT_3_PRICE_GROSS,
                ItemTransfer::SUM_NET_PRICE => static::TEST_PRODUCT_3_PRICE_NET,
                ItemTransfer::QUANTITY => 1,
                ItemTransfer::MERCHANT_REFERENCE => static::TEST_MERCHANT_REFERENCE,
            ])
            ->build();

        return $this->tester->createOrderFromQuoteTransfer($quoteTransfer);
    }
}
