<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Zed\Calculation\Business\DiscountCalculationTestCases;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\DiscountConfiguratorTransfer;
use Generated\Shared\Transfer\DiscountGeneralTransfer;
use Generated\Shared\Transfer\DiscountPromotionTransfer;
use Generated\Shared\Transfer\DiscountTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use PyzTest\Zed\Calculation\CalculationBusinessTester;

/**
 * Cart is eligible for a 20% Promotional Product discount, and this product is not yet added to the cart.
 *
 * Discount use case intro: {@link https://spryker.atlassian.net/wiki/spaces/CORE/pages/2896527845/Discount+Use+Cases}
 *
 * @link https://spryker.atlassian.net/wiki/spaces/CORE/pages/3017277839/Case+13+Promotional+Product+20+discount+cart+rule+percentage+discount+equal+priority
 * Case 13: Cart meets promotional product conditions, quote contains suggestion
 *
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Zed
 * @group Calculation
 * @group Business
 * @group DiscountCalculationTestCases
 * @group Case13Test
 * Add your own group annotations below this line
 */
class Case13Test extends Unit
{
    /**
     * @var string
     */
    protected const TEST_PRODUCT_1_ABSTRACT_SKU = 'CASE13_P1';

    /**
     * @var string
     */
    protected const TEST_PRODUCT_1_SKU = 'CASE13_P1_SKU';

    /**
     * @var int
     */
    protected const TEST_PRODUCT_1_PRICE = 10000;

    /**
     * @var int
     */
    protected const DISCOUNT_AMOUNT_ONE = 2000;

    /**
     * @var string
     */
    protected const DISCOUNT_NAME_ONE = '20% off P2';

    /**
     * @var string
     */
    protected const DISCOUNT_DECISION_RULE_ONE = "sku = 'CASE13_P1_SKU'";

    /**
     * @var string
     */
    protected const DISCOUNT_PROMOTIONAL_PRODUCT_ABSTRACT_SKU = 'CASE13_P2';

    /**
     * @var int
     */
    protected const EXPECTED_GRAND_TOTAL = 10000;

    /**
     * @var int
     */
    protected const EXPECTED_PROMOTIONAL_ITEMS_COUNT = 1;

    /**
     * @var int
     */
    protected const EXPECTED_DISCOUNT_TOTAL = 0;

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
    public function testPromotionalProductAddedAsSuggestionToTheCartAndQuoteHasExpectedTotals(): void
    {
        // Arrange
        $quoteTransfer = $this->createQuoteTransfer();
        $this->tester->createDiscounts($this->getDiscountsData($quoteTransfer));

        // Act
        $quoteTransfer = $this->tester->getFacade()->recalculateQuote($quoteTransfer);
        $totalsTransfer = $quoteTransfer->getTotals();

        // Assert
        /** @var \Generated\Shared\Transfer\PromotionItemTransfer $promotionalItemTransfer */
        $promotionalItemTransfer = $quoteTransfer->getPromotionItems()->getIterator()->current();
        $this->assertCount(static::EXPECTED_PROMOTIONAL_ITEMS_COUNT, $quoteTransfer->getPromotionItems());
        $this->assertSame(static::DISCOUNT_PROMOTIONAL_PRODUCT_ABSTRACT_SKU, $promotionalItemTransfer->getAbstractSku());
        $this->assertSame(static::DISCOUNT_AMOUNT_ONE, $promotionalItemTransfer->getDiscount()->getAmount());
        $this->assertSame(static::EXPECTED_GRAND_TOTAL, $totalsTransfer->getGrandTotal());
        $this->assertSame(static::EXPECTED_DISCOUNT_TOTAL, $totalsTransfer->getDiscountTotal());
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
        ];

        $totalsTransfer = (new TotalsTransfer())->setSubtotal(static::TEST_PRODUCT_1_PRICE);

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
                DiscountConfiguratorTransfer::DISCOUNT_GENERAL => [DiscountTransfer::DISCOUNT_TYPE => CalculationBusinessTester::TYPE_CART_RULE,
                    DiscountTransfer::AMOUNT => static::DISCOUNT_AMOUNT_ONE,
                    DiscountTransfer::DISPLAY_NAME => static::DISCOUNT_NAME_ONE,
                    DiscountTransfer::DISCOUNT_PROMOTION => static::DISCOUNT_NAME_ONE,
                    DiscountPromotionTransfer::ABSTRACT_SKU => static::DISCOUNT_PROMOTIONAL_PRODUCT_ABSTRACT_SKU,
                    DiscountGeneralTransfer::STORE_RELATION => [
                        StoreRelationTransfer::ID_STORES => [$quoteTransfer->getStore()->getIdStore()],
                    ],
                ],
                DiscountConfiguratorTransfer::DISCOUNT_CALCULATOR => [
                    DiscountTransfer::AMOUNT => static::DISCOUNT_AMOUNT_ONE,
                    DiscountTransfer::CALCULATOR_PLUGIN => CalculationBusinessTester::PLUGIN_CALCULATOR_PERCENTAGE,
                ],
            ],
        ];
    }
}
