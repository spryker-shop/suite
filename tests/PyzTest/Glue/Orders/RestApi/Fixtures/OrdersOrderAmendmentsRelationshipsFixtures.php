<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace PyzTest\Glue\Orders\RestApi\Fixtures;

use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\SalesOrderAmendmentTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use PyzTest\Glue\Orders\OrdersApiTester;
use SprykerTest\Shared\Testify\Fixtures\FixturesBuilderInterface;
use SprykerTest\Shared\Testify\Fixtures\FixturesContainerInterface;

/**
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Glue
 * @group Orders
 * @group RestApi
 * @group OrdersOrderAmendmentsRelationshipsFixtures
 * Add your own group annotations below this line
 * @group EndToEnd
 */
class OrdersOrderAmendmentsRelationshipsFixtures implements FixturesBuilderInterface, FixturesContainerInterface
{
    /**
     * @var string
     */
    protected const TEST_USERNAME = 'OrdersOrderAmendmentsRelationshipsFixtures';

    /**
     * @var string
     */
    protected const TEST_PASSWORD = 'change123';

    /**
     * @var string
     */
    protected const STATE_MACHINE_PROCESS_NAME = 'DummyPayment01';

    /**
     * @var \Generated\Shared\Transfer\CustomerTransfer
     */
    protected CustomerTransfer $customerTransfer;

    /**
     * @var \Generated\Shared\Transfer\SaveOrderTransfer
     */
    protected SaveOrderTransfer $saveOrderTransfer;

    /**
     * @var \Generated\Shared\Transfer\SalesOrderAmendmentTransfer
     */
    protected SalesOrderAmendmentTransfer $salesOrderAmendmentTransfer;

    /**
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function getCustomerTransfer(): CustomerTransfer
    {
        return $this->customerTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\SaveOrderTransfer
     */
    public function getSaveOrderTransfer(): SaveOrderTransfer
    {
        return $this->saveOrderTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\SalesOrderAmendmentTransfer
     */
    public function getSalesOrderAmendmentTransfer(): SalesOrderAmendmentTransfer
    {
        return $this->salesOrderAmendmentTransfer;
    }

    /**
     * @param \PyzTest\Glue\Orders\OrdersApiTester $I
     *
     * @return \SprykerTest\Shared\Testify\Fixtures\FixturesContainerInterface
     */
    public function buildFixtures(OrdersApiTester $I): FixturesContainerInterface
    {
        $I->ensureSalesOrderAmendmentTableIsEmpty();

        $customerTransfer = $I->haveCustomer([
            CustomerTransfer::USERNAME => static::TEST_USERNAME,
            CustomerTransfer::PASSWORD => static::TEST_PASSWORD,
            CustomerTransfer::NEW_PASSWORD => static::TEST_PASSWORD,
        ]);
        $this->customerTransfer = $I->confirmCustomer($customerTransfer);

        $quoteTransfer = (new QuoteBuilder())
            ->withItem([$I->haveProduct()])
            ->withCustomer([CustomerTransfer::CUSTOMER_REFERENCE => $this->customerTransfer->getCustomerReference()])
            ->withTotals([TotalsTransfer::GRAND_TOTAL => 1000])
            ->withShippingAddress()
            ->withBillingAddress()
            ->withCurrency()
            ->withPayment()
            ->build();

        $I->configureTestStateMachine([static::STATE_MACHINE_PROCESS_NAME]);

        $this->saveOrderTransfer = $I->haveOrderFromQuote($quoteTransfer, static::STATE_MACHINE_PROCESS_NAME);

        $this->salesOrderAmendmentTransfer = $I->haveSalesOrderAmendment([
            SalesOrderAmendmentTransfer::ORIGINAL_ORDER_REFERENCE => $this->saveOrderTransfer->getOrderReference(),
            SalesOrderAmendmentTransfer::AMENDED_ORDER_REFERENCE => $this->saveOrderTransfer->getOrderReference(),
        ]);

        return $this;
    }
}
