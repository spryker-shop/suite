<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Zed\Calculation\Business\DiscountCalculationTestCases;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\DiscountConfiguratorTransfer;
use Generated\Shared\Transfer\DiscountGeneralTransfer;
use Generated\Shared\Transfer\DiscountTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductCategoryTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use PyzTest\Zed\Calculation\CalculationBusinessTester;

/**
 * A voucher discount using IN category as a condition.
 *
 * Discount use case intro: {@link https://spryker.atlassian.net/wiki/spaces/CORE/pages/2896527845/Discount+Use+Cases}
 *
 * @link https://spryker.atlassian.net/wiki/spaces/CORE/pages/3728212168/Case+21+Voucher+code+10+discount+with+IN+category+condition
 * Case 21: Voucher code 10% discount with IN category condition
 *
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Zed
 * @group Calculation
 * @group Business
 * @group DiscountCalculationTestCases
 * @group Case21Test
 * Add your own group annotations below this line
 */
class Case21Test extends Unit
{
    /**
     * @var string
     */
    protected const TEST_PRODUCT_1_ABSTRACT_SKU = 'CASE21_P1';

    /**
     * @var string
     */
    protected const TEST_PRODUCT_1_SKU = 'CASE21_P1_SKU';

    /**
     * @var int
     */
    protected const TEST_PRODUCT_1_PRICE = 10000;

    /**
     * @var string
     */
    protected const TEST_PRODUCT_2_ABSTRACT_SKU = 'CASE21_P2';

    /**
     * @var string
     */
    protected const TEST_PRODUCT_2_SKU = 'CASE21_P2_SKU';

    /**
     * @var int
     */
    protected const TEST_PRODUCT_2_PRICE = 20000;

    /**
     * @var int
     */
    protected const DISCOUNT_AMOUNT = 1000;

    /**
     * @var string
     */
    protected const DISCOUNT_NAME = '10% off CATEGORY_211';

    /**
     * @var string
     */
    protected const CATEGORY_NAME_1 = 'CATEGORY_211';

    /**
     * @var string
     */
    protected const CATEGORY_NAME_2 = 'CATEGORY_212';

    /**
     * @var string
     */
    protected const DISCOUNT_COLLECTOR_QUERY_STRING = "category IS IN '" . self::CATEGORY_NAME_1 . "'";

    /**
     * @var int
     */
    protected const EXPECTED_GRAND_TOTAL = 29000;

    /**
     * @var int
     */
    protected const EXPECTED_SUB_TOTAL = 30000;

    /**
     * @var int
     */
    protected const EXPECTED_DISCOUNT_TOTAL = 1000;

    /**
     * @var array<string, int>
     */
    protected const EXPECTED_ITEM_DISCOUNT_AMOUNT = [
        'CASE21_P1_SKU' => 1000,
        'CASE21_P2_SKU' => 0,
    ];

    /**
     * @var \PyzTest\Zed\Calculation\CalculationBusinessTester
     */
    protected CalculationBusinessTester $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->resetCurrentDiscounts();
    }

    /**
     * @return void
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        $this->tester->cleanCategoryDecisionRuleCheckerStaticProperties();
    }

    /**
     * @return void
     */
    public function testQuoteTotalsWhenDiscountAppliedToCartWithItemAssignedToTwoCategoriesAndDiscountAppliedToOneOfThem(): void
    {
        // Arrange
        $categoryTransfer1 = $this->tester->haveLocalizedCategory([CategoryTransfer::CATEGORY_KEY => static::CATEGORY_NAME_1]);
        $categoryTransfer2 = $this->tester->haveLocalizedCategory([CategoryTransfer::CATEGORY_KEY => static::CATEGORY_NAME_2]);
        $productConcreteTransfer1 = $this->tester->haveProduct(
            [
                ProductConcreteTransfer::SKU => static::TEST_PRODUCT_1_SKU,
                ProductConcreteTransfer::ABSTRACT_SKU => static::TEST_PRODUCT_1_ABSTRACT_SKU,
            ],
            [
                ProductAbstractTransfer::SKU => static::TEST_PRODUCT_1_ABSTRACT_SKU,
            ],
        );
        $productConcreteTransfer2 = $this->tester->haveProduct(
            [
                ProductConcreteTransfer::SKU => static::TEST_PRODUCT_2_SKU,
                ProductConcreteTransfer::ABSTRACT_SKU => static::TEST_PRODUCT_2_ABSTRACT_SKU,
            ],
            [
                ProductAbstractTransfer::SKU => static::TEST_PRODUCT_2_ABSTRACT_SKU,
            ],
        );
        $this->tester->haveProductCategoryForCategory(
            $categoryTransfer1->getIdCategoryOrFail(),
            [ProductCategoryTransfer::FK_PRODUCT_ABSTRACT => $productConcreteTransfer1->getFkProductAbstractOrFail()],
        );
        $this->tester->haveProductCategoryForCategory(
            $categoryTransfer2->getIdCategoryOrFail(),
            [ProductCategoryTransfer::FK_PRODUCT_ABSTRACT => $productConcreteTransfer1->getFkProductAbstractOrFail()],
        );
        $this->tester->haveProductCategoryForCategory(
            $categoryTransfer2->getIdCategoryOrFail(),
            [ProductCategoryTransfer::FK_PRODUCT_ABSTRACT => $productConcreteTransfer2->getFkProductAbstractOrFail()],
        );

        $quoteTransfer = $this->createQuoteTransfer($productConcreteTransfer1, $productConcreteTransfer2);
        $discountTransfers = $this->tester->createDiscounts($this->getDiscountsData($quoteTransfer));
        $quoteTransfer = $this->tester->addVoucherDiscountsToQuote($quoteTransfer, $discountTransfers);

        // Act
        $quoteTransfer = $this->tester->getFacade()->recalculateQuote($quoteTransfer);
        $totalsTransfer = $quoteTransfer->getTotals();

        // Assert
        $this->assertSame(static::EXPECTED_GRAND_TOTAL, $totalsTransfer->getGrandTotal());
        $this->assertSame(static::EXPECTED_SUB_TOTAL, $totalsTransfer->getSubtotal());
        $this->assertSame(static::EXPECTED_DISCOUNT_TOTAL, $totalsTransfer->getDiscountTotal());
        $this->tester->assertQuoteItemsHaveExpectedDiscountAmount($quoteTransfer, static::EXPECTED_ITEM_DISCOUNT_AMOUNT);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer1
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer2
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function createQuoteTransfer(
        ProductConcreteTransfer $productConcreteTransfer1,
        ProductConcreteTransfer $productConcreteTransfer2,
    ): QuoteTransfer {
        $itemTransferData = [
            [
                ItemTransfer::ID_PRODUCT_ABSTRACT => $productConcreteTransfer1->getFkProductAbstract(),
                ItemTransfer::ABSTRACT_SKU => static::TEST_PRODUCT_1_ABSTRACT_SKU,
                ItemTransfer::SKU => static::TEST_PRODUCT_1_SKU,
                ItemTransfer::UNIT_PRICE => static::TEST_PRODUCT_1_PRICE,
                ItemTransfer::UNIT_GROSS_PRICE => static::TEST_PRODUCT_1_PRICE,
                ItemTransfer::QUANTITY => CalculationBusinessTester::TEST_PRODUCT_QUANTITY,
            ],
            [
                ItemTransfer::ID_PRODUCT_ABSTRACT => $productConcreteTransfer2->getFkProductAbstract(),
                ItemTransfer::ABSTRACT_SKU => static::TEST_PRODUCT_2_ABSTRACT_SKU,
                ItemTransfer::SKU => static::TEST_PRODUCT_2_SKU,
                ItemTransfer::UNIT_PRICE => static::TEST_PRODUCT_2_PRICE,
                ItemTransfer::UNIT_GROSS_PRICE => static::TEST_PRODUCT_2_PRICE,
                ItemTransfer::QUANTITY => CalculationBusinessTester::TEST_PRODUCT_QUANTITY,
            ],
        ];

        $totalsTransfer = (new TotalsTransfer())->setSubtotal(
            static::TEST_PRODUCT_1_PRICE + static::TEST_PRODUCT_2_PRICE,
        );

        $quoteTransfer = $this->tester->createQuoteTransferWithItems($itemTransferData);

        return $quoteTransfer->setTotals($totalsTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array<array<string, mixed>>
     */
    protected function getDiscountsData(QuoteTransfer $quoteTransfer): array
    {
        $idStore = $quoteTransfer->getStore()->getIdStore();

        return [
            [
                DiscountConfiguratorTransfer::DISCOUNT_GENERAL => [
                    DiscountTransfer::DISCOUNT_TYPE => CalculationBusinessTester::TYPE_VOUCHER,
                    DiscountTransfer::DISPLAY_NAME => static::DISCOUNT_NAME,
                    DiscountTransfer::AMOUNT => static::DISCOUNT_AMOUNT,
                    DiscountGeneralTransfer::STORE_RELATION => [
                        StoreRelationTransfer::ID_STORES => [$idStore],
                    ],
                ],
                DiscountConfiguratorTransfer::DISCOUNT_CALCULATOR => [
                    DiscountTransfer::COLLECTOR_QUERY_STRING => static::DISCOUNT_COLLECTOR_QUERY_STRING,
                    DiscountTransfer::AMOUNT => static::DISCOUNT_AMOUNT,
                    DiscountTransfer::CALCULATOR_PLUGIN => CalculationBusinessTester::PLUGIN_CALCULATOR_PERCENTAGE,
                ],
            ],
        ];
    }
}
