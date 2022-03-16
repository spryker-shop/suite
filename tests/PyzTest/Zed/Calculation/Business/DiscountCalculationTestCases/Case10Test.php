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
 * Cart is eligible for two Promotional Product discounts, and they contain the same abstract product SKU.
 *
 * Discount use case intro: {@link https://spryker.atlassian.net/wiki/spaces/CORE/pages/2896527845/Discount+Use+Cases}
 *
 * @link https://spryker.atlassian.net/wiki/spaces/CORE/pages/3005711668/Case+10+Same+product+in+two+Promotional+Product+discounts+equal+priority
 * Case 10: Same product in two Promotional Product discounts (equal priority).
 *
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Zed
 * @group Calculation
 * @group Business
 * @group DiscountCalculationTestCases
 * @group Case10Test
 * Add your own group annotations below this line
 */
class Case10Test extends Unit
{
    /**
     * @var string
     */
    protected const TEST_PRODUCT_1_ABSTRACT_SKU = 'CASE10_P1';

    /**
     * @var string
     */
    protected const TEST_PRODUCT_1_SKU = 'CASE10_P1_SKU';

    /**
     * @var int
     */
    protected const TEST_PRODUCT_1_PRICE = 10000;

    /**
     * @var string
     */
    protected const TEST_PRODUCT_2_ABSTRACT_SKU = 'CASE10_P2';

    /**
     * @var string
     */
    protected const TEST_PRODUCT_2_SKU = 'CASE10_P2_SKU';

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
    protected const DISCOUNT_DECISION_RULE_ONE = "sku = 'CASE10_P1_SKU'";

    /**
     * @var string
     */
    protected const DISCOUNT_NAME_TWO = '20% off P2';

    /**
     * @var int
     */
    protected const DISCOUNT_AMOUNT_TWO = 2000;

    /**
     * @var string
     */
    protected const DISCOUNT_DECISION_RULE_TWO = "sub-total >= '100' AND currency = 'EUR'";

    /**
     * @var string
     */
    protected const DISCOUNT_PROMOTIONAL_PRODUCT_ABSTRACT_SKU_ONE = 'CASE10_P2';

    /**
     * @var string
     */
    protected const DISCOUNT_PROMOTIONAL_PRODUCT_ABSTRACT_SKU_TWO = 'CASE10_P2';

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
        'CASE10_P1_SKU' => 0,
        'CASE10_P2_SKU' => 2000,
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
    public function testQuoteTotalsWhenOnlyOneDiscountAppliedToThePromotionalProductAndHaveMoreThenOneDiscountForSamePromotionalProduct(): void
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
     * @return array<string, mixed>
     */
    protected function getDiscountsData(QuoteTransfer $quoteTransfer): array
    {
        $idStore = $quoteTransfer->getStore()->getIdStore();

        return [
            [
                DiscountTransfer::DISCOUNT_TYPE => CalculationBusinessTester::TYPE_CART_RULE,
                DiscountTransfer::AMOUNT => static::DISCOUNT_AMOUNT_ONE,
                DiscountTransfer::CALCULATOR_PLUGIN => CalculationBusinessTester::PLUGIN_CALCULATOR_FIXED,
                DiscountTransfer::DISPLAY_NAME => static::DISCOUNT_NAME_ONE,
                DiscountTransfer::DISCOUNT_PROMOTION => static::DISCOUNT_NAME_ONE,
                DiscountPromotionTransfer::ABSTRACT_SKU => static::DISCOUNT_PROMOTIONAL_PRODUCT_ABSTRACT_SKU_ONE,
                StoreRelationTransfer::ID_STORES => [$idStore],
                CalculationBusinessTester::DISCOUNT_AMOUNTS_KEY => [
                    [
                        MoneyValueTransfer::FK_CURRENCY => $quoteTransfer->getCurrency()->getIdCurrency(),
                        MoneyValueTransfer::GROSS_AMOUNT => static::DISCOUNT_AMOUNT_ONE,
                    ],
                ],
            ],
            [
                DiscountTransfer::DISCOUNT_TYPE => CalculationBusinessTester::TYPE_CART_RULE,
                DiscountTransfer::AMOUNT => static::DISCOUNT_AMOUNT_TWO,
                DiscountTransfer::CALCULATOR_PLUGIN => CalculationBusinessTester::PLUGIN_CALCULATOR_PERCENTAGE,
                DiscountTransfer::DISPLAY_NAME => static::DISCOUNT_NAME_TWO,
                DiscountTransfer::DISCOUNT_PROMOTION => static::DISCOUNT_NAME_TWO,
                DiscountPromotionTransfer::ABSTRACT_SKU => static::DISCOUNT_PROMOTIONAL_PRODUCT_ABSTRACT_SKU_TWO,
                StoreRelationTransfer::ID_STORES => [$idStore],
            ],
        ];
    }
}
