<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Zed\Calculation\Business\DiscountCalculationTestCases;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CustomerGroupToCustomerAssignmentTransfer;
use Generated\Shared\Transfer\CustomerGroupTransfer;
use Generated\Shared\Transfer\DiscountConfiguratorTransfer;
use Generated\Shared\Transfer\DiscountGeneralTransfer;
use Generated\Shared\Transfer\DiscountTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use PyzTest\Zed\Calculation\CalculationBusinessTester;

/**
 * A cart rule discount using customer-group as a condition and applied to a particular SKU.
 *
 * Discount use case intro: {@link https://spryker.atlassian.net/wiki/spaces/CORE/pages/2896527845/Discount+Use+Cases}
 *
 * @link https://spryker.atlassian.net/wiki/spaces/CORE/pages/3155361836/Case+19+Cart+rule+SKU+discount+with+customer-group+condition
 * Case 19: Cart rule SKU discount with customer-group condition
 *
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Zed
 * @group Calculation
 * @group Business
 * @group DiscountCalculationTestCases
 * @group Case19Test
 * Add your own group annotations below this line
 */
class Case19Test extends Unit
{
    /**
     * @var string
     */
    protected const TEST_PRODUCT_1_ABSTRACT_SKU = 'CASE19_P1';

    /**
     * @var string
     */
    protected const TEST_PRODUCT_1_SKU = 'CASE19_P1_SKU';

    /**
     * @var int
     */
    protected const TEST_PRODUCT_1_PRICE = 10000;

    /**
     * @var string
     */
    protected const TEST_PRODUCT_2_ABSTRACT_SKU = 'CASE19_P2';

    /**
     * @var string
     */
    protected const TEST_PRODUCT_2_SKU = 'CASE19_P2_SKU';

    /**
     * @var int
     */
    protected const TEST_PRODUCT_2_PRICE = 10000;

    /**
     * @var string
     */
    protected const CUSTOMER_GROUP_NAME = 'CASE19_Group';

    /**
     * @var string
     */
    protected const DISCOUNT_NAME = '10% off P1';

    /**
     * @var int
     */
    protected const DISCOUNT_AMOUNT = 1000;

    /**
     * @var int
     */
    protected const DISCOUNT_PRIORITY = 9999;

    /**
     * @var string
     */
    protected const DISCOUNT_DECISION_RULE = "customer-group = 'CASE19_Group'";

    /**
     * @var string
     */
    protected const DISCOUNT_COLLECTOR_QUERY_STRING = "sku = 'CASE19_P1_SKU'";

    /**
     * @var int
     */
    protected const EXPECTED_GRAND_TOTAL = 19000;

    /**
     * @var int
     */
    protected const EXPECTED_SUB_TOTAL = 20000;

    /**
     * @var int
     */
    protected const EXPECTED_DISCOUNT_TOTAL = 1000;

    /**
     * @var array<string, int>
     */
    protected const EXPECTED_ITEM_DISCOUNT_AMOUNT = [
        self::TEST_PRODUCT_1_SKU => 1000,
        self::TEST_PRODUCT_2_SKU => 0,
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
    public function testQuoteTotalsWhenCustomerGroupConditionAppliedToCart(): void
    {
        // Arrange
        $customerTransfer = $this->tester->haveCustomer();
        $this->tester->haveCustomerGroup([
            CustomerGroupTransfer::NAME => static::CUSTOMER_GROUP_NAME,
            CustomerGroupTransfer::CUSTOMER_ASSIGNMENT => [
                CustomerGroupToCustomerAssignmentTransfer::IDS_CUSTOMER_TO_ASSIGN => [$customerTransfer->getIdCustomerOrFail()],
            ],
        ]);

        $quoteTransfer = $this->createQuoteTransfer();
        $quoteTransfer->setCustomer($customerTransfer)
            ->setCustomerReference($customerTransfer->getCustomerReferenceOrFail());

        $this->tester->createDiscounts([$this->getDiscountData($quoteTransfer)]);

        // Act
        $quoteTransfer = $this->tester->getFacade()->recalculateQuote($quoteTransfer);

        // Assert
        $totalsTransfer = $quoteTransfer->getTotals();
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
     * @return array<string, mixed>
     */
    protected function getDiscountData(QuoteTransfer $quoteTransfer): array
    {
        $idStore = $quoteTransfer->getStore()->getIdStore();

        return [
            DiscountConfiguratorTransfer::DISCOUNT_GENERAL => [
                DiscountTransfer::DISCOUNT_TYPE => CalculationBusinessTester::TYPE_CART_RULE,
                DiscountTransfer::AMOUNT => static::DISCOUNT_AMOUNT,
                DiscountTransfer::DISPLAY_NAME => static::DISCOUNT_NAME,
                DiscountTransfer::PRIORITY => static::DISCOUNT_PRIORITY,
                DiscountGeneralTransfer::STORE_RELATION => [
                    StoreRelationTransfer::ID_STORES => [$idStore],
                ],
            ],
            DiscountConfiguratorTransfer::DISCOUNT_CALCULATOR => [
                DiscountTransfer::COLLECTOR_QUERY_STRING => static::DISCOUNT_COLLECTOR_QUERY_STRING,
                DiscountTransfer::AMOUNT => static::DISCOUNT_AMOUNT,
                DiscountTransfer::CALCULATOR_PLUGIN => CalculationBusinessTester::PLUGIN_CALCULATOR_PERCENTAGE,
            ],
        ];
    }
}
