<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Zed\Calculation\Business\DiscountCalculationTestCases;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\DiscountPromotionTransfer;
use Generated\Shared\Transfer\DiscountTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use PyzTest\Zed\Calculation\CalculationBusinessTester;

/**
 * Cart is eligible for a €10 Promotional Product discount, and this product is added to the cart. Cart is also eligible for a percentage discount off all items. Discounts have equal priority.
 *
 * Discount use case intro: {@link https://spryker.atlassian.net/wiki/spaces/CORE/pages/2896527845/Discount+Use+Cases}
 *
 * @link https://spryker.atlassian.net/wiki/spaces/CORE/pages/3005744202/Case+9+Promotional+Product+fixed+discount+cart+rule+percentage+discount+equal+priority
 * Case 9: Promotional Product fixed discount + cart rule percentage discount (equal priority).
 *
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Zed
 * @group Calculation
 * @group Business
 * @group DiscountCalculationTestCases
 * @group Case9Test
 * Add your own group annotations below this line
 */
class Case9Test extends Unit
{
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
    protected const TEST_PRODUCT_1_PRICE = 10000;

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
    protected const TEST_PRODUCT_2_PRICE = 10000;

    /**
     * @var int
     */
    protected const DISCOUNT_AMOUNT_ONE = 1000;

    /**
     * @var string
     */
    protected const DISCOUNT_NAME_ONE = '€10 off P2';

    /**
     * @var string
     */
    protected const DISCOUNT_DECISION_RULE_ONE = "sku = 'CASE9_P1_SKU'";

    /**
     * @var string
     */
    protected const DISCOUNT_NAME_TWO = '10% off everything';

    /**
     * @var int
     */
    protected const DISCOUNT_AMOUNT_TWO = 1000;

    /**
     * @var string
     */
    protected const DISCOUNT_COLLECTOR_QUERY_STRING_TWO = "sku = '*'";

    /**
     * @var string
     */
    protected const DISCOUNT_DECISION_RULE_TWO = "sub-total >= '0'";

    /**
     * @var string
     */
    protected const DISCOUNT_PROMOTIONAL_PRODUCT_ABSTRACT_SKU = 'CASE9_P2';

    /**
     * @var int
     */
    protected const EXPECTED_GRAND_TOTAL = 18000;

    /**
     * @var int
     */
    protected const EXPECTED_SUB_TOTAL = 20000;

    /**
     * @var int
     */
    protected const EXPECTED_DISCOUNT_TOTAL = 2000;

    /**
     * @var array<string, int>
     */
    protected const EXPECTED_ITEM_DISCOUNT_AMOUNT = [
        'CASE9_P1_SKU' => 1000,
        'CASE9_P2_SKU' => 1000,
    ];

    /**
     * @var \PyzTest\Zed\Calculation\CalculationBusinessTester
     */
    protected $tester;

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
    public function testQuoteTotalsWhenFixedPromotionalProductDiscountAndRegularPercentageDiscountsAppliedToCart(): void
    {
        // Arrange
        $quoteTransfer = $this->createQuoteTransfer();
        $discountTransfers = $this->tester->createDiscounts($this->getDiscountsData($quoteTransfer));
        $quoteTransfer = $this->tester->addIdDiscountPromotionToQuoteItems($quoteTransfer, $discountTransfers);

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
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function createQuoteTransfer(): QuoteTransfer
    {
        $itemTransferData = [
            [
                ItemTransfer::ABSTRACT_SKU => static::TEST_PRODUCT_1_ABSTRACT_SKU,
                ItemTransfer::SKU => static::TEST_PRODUCT_1_SKU,
                ItemTransfer::UNIT_PRICE => static::TEST_PRODUCT_1_PRICE,
                ItemTransfer::UNIT_GROSS_PRICE => static::TEST_PRODUCT_1_PRICE,
                ItemTransfer::QUANTITY => CalculationBusinessTester::TEST_PRODUCT_QUANTITY,
            ],
            [
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
        return [
            [
                DiscountTransfer::DISCOUNT_TYPE => CalculationBusinessTester::TYPE_CART_RULE,
                DiscountTransfer::DECISION_RULE_QUERY_STRING => static::DISCOUNT_DECISION_RULE_ONE,
                DiscountTransfer::AMOUNT => static::DISCOUNT_AMOUNT_ONE,
                DiscountTransfer::CALCULATOR_PLUGIN => CalculationBusinessTester::PLUGIN_CALCULATOR_FIXED,
                DiscountTransfer::DISPLAY_NAME => static::DISCOUNT_NAME_ONE,
                DiscountTransfer::DISCOUNT_PROMOTION => static::DISCOUNT_NAME_ONE,
                DiscountPromotionTransfer::ABSTRACT_SKU => static::DISCOUNT_PROMOTIONAL_PRODUCT_ABSTRACT_SKU,
                StoreRelationTransfer::ID_STORES => [$quoteTransfer->getStore()->getIdStore()],
                CalculationBusinessTester::DISCOUNT_AMOUNTS_KEY => [
                    [
                        MoneyValueTransfer::FK_CURRENCY => $quoteTransfer->getCurrency()->getIdCurrency(),
                        MoneyValueTransfer::GROSS_AMOUNT => static::DISCOUNT_AMOUNT_ONE,
                    ],
                ],
            ],
            [
                DiscountTransfer::DISCOUNT_TYPE => CalculationBusinessTester::TYPE_CART_RULE,
                DiscountTransfer::DECISION_RULE_QUERY_STRING => static::DISCOUNT_DECISION_RULE_TWO,
                DiscountTransfer::COLLECTOR_QUERY_STRING => static::DISCOUNT_COLLECTOR_QUERY_STRING_TWO,
                DiscountTransfer::AMOUNT => static::DISCOUNT_AMOUNT_TWO,
                DiscountTransfer::CALCULATOR_PLUGIN => CalculationBusinessTester::PLUGIN_CALCULATOR_PERCENTAGE,
                DiscountTransfer::DISPLAY_NAME => static::DISCOUNT_NAME_TWO,
                StoreRelationTransfer::ID_STORES => [$quoteTransfer->getStore()->getIdStore()],
            ],
        ];
    }
}
