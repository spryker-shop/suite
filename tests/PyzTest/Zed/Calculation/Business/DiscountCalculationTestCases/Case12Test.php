<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Zed\Calculation\Business\DiscountCalculationTestCases;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\DiscountConfiguratorTransfer;
use Generated\Shared\Transfer\DiscountGeneralTransfer;
use Generated\Shared\Transfer\DiscountTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use PyzTest\Zed\Calculation\CalculationBusinessTester;

/**
 * Three non-exclusive discounts, mix of fixed amount and percentage with various priorities.
 *
 * Discount use case intro: {@link https://spryker.atlassian.net/wiki/spaces/CORE/pages/2896527845/Discount+Use+Cases}
 *
 * @link https://spryker.atlassian.net/wiki/spaces/CORE/pages/3007875271/Case+12+Different+priorities+-+Mix+of+percentage+and+fixed+amount+cart+rule+discounts
 * Case 12: Different priorities - Mix of percentage and fixed amount cart rule discounts.
 *
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Zed
 * @group Calculation
 * @group Business
 * @group DiscountCalculationTestCases
 * @group Case12Test
 * Add your own group annotations below this line
 */
class Case12Test extends Unit
{
    /**
     * @var string
     */
    protected const TEST_PRODUCT_1_ABSTRACT_SKU = 'CASE12_P1';

    /**
     * @var string
     */
    protected const TEST_PRODUCT_1_SKU = 'CASE12_P1_SKU';

    /**
     * @var int
     */
    protected const TEST_PRODUCT_1_PRICE = 10000;

    /**
     * @var string
     */
    protected const TEST_PRODUCT_2_ABSTRACT_SKU = 'CASE12_P2';

    /**
     * @var string
     */
    protected const TEST_PRODUCT_2_SKU = 'CASE12_P2_SKU';

    /**
     * @var int
     */
    protected const TEST_PRODUCT_2_PRICE = 10000;

    /**
     * @var string
     */
    protected const DISCOUNT_NAME_ONE = 'HOCKEY10';

    /**
     * @var int
     */
    protected const DISCOUNT_AMOUNT_ONE = 1000;

    /**
     * @var int
     */
    protected const DISCOUNT_PRIORITY_ONE = 300;

    /**
     * @var string
     */
    protected const DISCOUNT_DECISION_RULE_ONE = "sub-total >= '0'";

    /**
     * @var string
     */
    protected const DISCOUNT_COLLECTOR_QUERY_STRING_ONE = "sku = '*'";

    /**
     * @var string
     */
    protected const DISCOUNT_NAME_TWO = '€20 off a specific helmet';

    /**
     * @var int
     */
    protected const DISCOUNT_AMOUNT_TWO = 2000;

    /**
     * @var int
     */
    protected const DISCOUNT_PRIORITY_TWO = 200;

    /**
     * @var string
     */
    protected const DISCOUNT_DECISION_RULE_TWO = "sku = 'CASE12_P1_SKU'";

    /**
     * @var string
     */
    protected const DISCOUNT_COLLECTOR_QUERY_STRING_TWO = "sku = 'CASE12_P1_SKU'";

    /**
     * @var string
     */
    protected const DISCOUNT_NAME_THREE = '€50 off a specific stick';

    /**
     * @var int
     */
    protected const DISCOUNT_AMOUNT_THREE = 5000;

    /**
     * @var int
     */
    protected const DISCOUNT_PRIORITY_THREE = 500;

    /**
     * @var string
     */
    protected const DISCOUNT_DECISION_RULE_THREE = "sku = 'CASE12_P2_SKU'";

    /**
     * @var string
     */
    protected const DISCOUNT_COLLECTOR_QUERY_STRING_THREE = "sku = 'CASE12_P2_SKU'";

    /**
     * @var int
     */
    protected const EXPECTED_GRAND_TOTAL = 11200;

    /**
     * @var int
     */
    protected const EXPECTED_SUB_TOTAL = 20000;

    /**
     * @var int
     */
    protected const EXPECTED_DISCOUNT_TOTAL = 8800;

    /**
     * @var array<string, int>
     */
    protected const EXPECTED_ITEM_DISCOUNT_AMOUNT = [
        'CASE12_P1_SKU' => 2800,
        'CASE12_P2_SKU' => 6000,
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
    public function testQuoteTotalsWhenPrioritizedPercentageAndFixedDiscountsAppliedToCart(): void
    {
        // Arrange
        $quoteTransfer = $this->createQuoteTransfer();
        $this->tester->createDiscounts($this->getDiscountsData($quoteTransfer));

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
                ItemTransfer::UNIT_GROSS_PRICE => static::TEST_PRODUCT_2_PRICE,
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
        $idStore = $quoteTransfer->getStore()->getIdStore();
        $idCurrency = $quoteTransfer->getCurrency()->getIdCurrency();

        return [
            [
                DiscountConfiguratorTransfer::DISCOUNT_GENERAL => [DiscountTransfer::DISCOUNT_TYPE => CalculationBusinessTester::TYPE_CART_RULE,
                    DiscountTransfer::AMOUNT => static::DISCOUNT_AMOUNT_ONE,
                    DiscountTransfer::PRIORITY => static::DISCOUNT_PRIORITY_ONE,
                    DiscountTransfer::DISPLAY_NAME => static::DISCOUNT_NAME_ONE,
                    DiscountGeneralTransfer::STORE_RELATION => [
                        StoreRelationTransfer::ID_STORES => [$idStore],
                    ],
                ],
                DiscountConfiguratorTransfer::DISCOUNT_CALCULATOR => [
                    DiscountTransfer::COLLECTOR_QUERY_STRING => static::DISCOUNT_COLLECTOR_QUERY_STRING_ONE,
                    DiscountTransfer::AMOUNT => static::DISCOUNT_AMOUNT_ONE,
                    DiscountTransfer::CALCULATOR_PLUGIN => CalculationBusinessTester::PLUGIN_CALCULATOR_PERCENTAGE,
                ],
            ],
            [
                DiscountConfiguratorTransfer::DISCOUNT_GENERAL => [DiscountTransfer::DISCOUNT_TYPE => CalculationBusinessTester::TYPE_CART_RULE,
                    DiscountTransfer::AMOUNT => static::DISCOUNT_AMOUNT_TWO,
                    DiscountTransfer::PRIORITY => static::DISCOUNT_PRIORITY_TWO,
                    DiscountTransfer::DISPLAY_NAME => static::DISCOUNT_NAME_TWO,
                    DiscountGeneralTransfer::STORE_RELATION => [
                        StoreRelationTransfer::ID_STORES => [$idStore],
                    ],
                    CalculationBusinessTester::DISCOUNT_AMOUNTS_KEY => [
                        [
                            MoneyValueTransfer::FK_CURRENCY => $idCurrency,
                            MoneyValueTransfer::GROSS_AMOUNT => static::DISCOUNT_AMOUNT_TWO,
                        ],
                    ],
                ],
                DiscountConfiguratorTransfer::DISCOUNT_CALCULATOR => [
                    DiscountTransfer::COLLECTOR_QUERY_STRING => static::DISCOUNT_COLLECTOR_QUERY_STRING_TWO,
                    DiscountTransfer::AMOUNT => static::DISCOUNT_AMOUNT_TWO,
                    DiscountTransfer::CALCULATOR_PLUGIN => CalculationBusinessTester::PLUGIN_CALCULATOR_FIXED,
                ],
            ],
            [
                DiscountConfiguratorTransfer::DISCOUNT_GENERAL => [DiscountTransfer::DISCOUNT_TYPE => CalculationBusinessTester::TYPE_CART_RULE,
                    DiscountTransfer::AMOUNT => static::DISCOUNT_AMOUNT_THREE,
                    DiscountTransfer::PRIORITY => static::DISCOUNT_PRIORITY_THREE,
                    DiscountTransfer::DISPLAY_NAME => static::DISCOUNT_NAME_THREE,
                    DiscountGeneralTransfer::STORE_RELATION => [
                        StoreRelationTransfer::ID_STORES => [$idStore],
                    ],
                    CalculationBusinessTester::DISCOUNT_AMOUNTS_KEY => [
                        [
                            MoneyValueTransfer::FK_CURRENCY => $idCurrency,
                            MoneyValueTransfer::GROSS_AMOUNT => static::DISCOUNT_AMOUNT_THREE,
                        ],
                    ],
                ],
                DiscountConfiguratorTransfer::DISCOUNT_CALCULATOR => [
                    DiscountTransfer::COLLECTOR_QUERY_STRING => static::DISCOUNT_COLLECTOR_QUERY_STRING_THREE,
                    DiscountTransfer::AMOUNT => static::DISCOUNT_AMOUNT_THREE,
                    DiscountTransfer::CALCULATOR_PLUGIN => CalculationBusinessTester::PLUGIN_CALCULATOR_FIXED,
                ],
            ],
        ];
    }
}
