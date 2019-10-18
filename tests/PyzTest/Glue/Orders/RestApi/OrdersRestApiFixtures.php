<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Glue\Orders\RestApi;

use Generated\Shared\DataBuilder\ProductConcreteBuilder;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use PyzTest\Glue\Orders\OrdersRestApiTester;
use Spryker\Shared\Shipment\ShipmentConstants;
use SprykerTest\Shared\Testify\Fixtures\FixturesBuilderInterface;
use SprykerTest\Shared\Testify\Fixtures\FixturesContainerInterface;

/**
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Glue
 * @group OrdersRestApi
 * @group RestApi
 * @group OrdersRestApiFixtures
 * Add your own group annotations below this line
 * @group EndToEnd
 */
class OrdersRestApiFixtures implements FixturesBuilderInterface, FixturesContainerInterface
{
    protected const DEFAULT_STATE_MACHINE = 'Test01';
    protected const TEST_PASSWORD = 'Test password';

    /**
     * @var \Generated\Shared\Transfer\SaveOrderTransfer
     */
    protected $saveOrderTransfer;

    /**
     * @var \Generated\Shared\Transfer\CustomerTransfer
     */
    protected $customerTransfer;

    /**
     * @var \Generated\Shared\Transfer\ShipmentMethodTransfer
     */
    protected $shipmentMethodTransfer;

    /**
     * @var \Generated\Shared\Transfer\QuoteTransfer
     */
    protected $quoteTransfer;

    /**
     * @var \Generated\Shared\Transfer\OrderTransfer
     */
    protected $orderTransfer;

    /**
     * @return \Generated\Shared\Transfer\SaveOrderTransfer
     */
    public function getSaveOrderTransfer(): SaveOrderTransfer
    {
        return $this->saveOrderTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function getCustomerTransfer(): CustomerTransfer
    {
        return $this->customerTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\ShipmentMethodTransfer
     */
    public function getShipmentMethodTransfer(): ShipmentMethodTransfer
    {
        return $this->shipmentMethodTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function getQuoteTransfer(): QuoteTransfer
    {
        return $this->quoteTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function getOrderTransfer(): OrderTransfer
    {
        return $this->orderTransfer;
    }

    /**
     * @param \PyzTest\Glue\Orders\OrdersRestApiTester $I
     *
     * @return \SprykerTest\Shared\Testify\Fixtures\FixturesContainerInterface
     */
    public function buildFixtures(OrdersRestApiTester $I): FixturesContainerInterface
    {
        $this->customerTransfer = $this->createCustomerTransfer($I);
        $this->shipmentMethodTransfer = $this->createShipmentMethodTransfer($I);
        $this->quoteTransfer = $this->createQuoteTransfer();
        $this->saveOrderTransfer = $this->createSaveOrderTransfer($I);
        $this->orderTransfer = $this->createOrderTransfer($I);

        $this->saveOrderShipment($I);

        return $this;
    }

    /**
     * @param \PyzTest\Glue\Orders\OrdersRestApiTester $I
     *
     * @return \Generated\Shared\Transfer\SaveOrderTransfer
     */
    protected function createSaveOrderTransfer(OrdersRestApiTester $I): SaveOrderTransfer
    {
        $I->configureTestStateMachine([static::DEFAULT_STATE_MACHINE]);

        return $I->haveOrderFromQuote($this->quoteTransfer, static::DEFAULT_STATE_MACHINE);
    }

    /**
     * @param \PyzTest\Glue\Orders\OrdersRestApiTester $I
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    protected function createCustomerTransfer(OrdersRestApiTester $I): CustomerTransfer
    {
        return $I->haveCustomer([
            CustomerTransfer::PASSWORD => static::TEST_PASSWORD,
            CustomerTransfer::NEW_PASSWORD => static::TEST_PASSWORD,
        ]);
    }

    /**
     * @param \PyzTest\Glue\Orders\OrdersRestApiTester $I
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodTransfer
     */
    protected function createShipmentMethodTransfer(OrdersRestApiTester $I): ShipmentMethodTransfer
    {
        return $I->haveShipmentMethod([ShipmentMethodTransfer::IS_ACTIVE => true]);
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function createQuoteTransfer(): QuoteTransfer
    {
        $quoteTransfer = (new QuoteBuilder())
            ->withItem([
                ItemTransfer::SKU => (new ProductConcreteBuilder())->build()->getSku(),
                ItemTransfer::UNIT_PRICE => 1,
                ItemTransfer::QUANTITY => 1,
            ])
            ->withItem([
                ItemTransfer::SKU => (new ProductConcreteBuilder())->build()->getSku(),
                ItemTransfer::UNIT_PRICE => 1,
                ItemTransfer::QUANTITY => 1,
            ])
            ->withShippingAddress()
            ->withBillingAddress()
            ->withShipment([
                ShipmentTransfer::METHOD => $this->shipmentMethodTransfer,
            ])
            ->withCurrency()
            ->withTotals([
                TotalsTransfer::GRAND_TOTAL => random_int(1000, 10000),
            ])
            ->build();

        $quoteTransfer->setCustomer($this->customerTransfer);
        $quoteTransfer->addExpense($this->createShipmentExpenseTransfer());

        return $quoteTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\ExpenseTransfer
     */
    protected function createShipmentExpenseTransfer(): ExpenseTransfer
    {
        $expenseTransfer = new ExpenseTransfer();
        $expenseTransfer->setType(ShipmentConstants::SHIPMENT_EXPENSE_TYPE);
        $expenseTransfer->setName($this->shipmentMethodTransfer->getName());
        $expenseTransfer->setSumGrossPrice(random_int(1000, 10000));
        $expenseTransfer->setUnitGrossPrice(random_int(1000, 10000));
        $expenseTransfer->setQuantity(1);

        return $expenseTransfer;
    }

    /**
     * @param \PyzTest\Glue\Orders\OrdersRestApiTester $I
     *
     * @return void
     */
    protected function saveOrderShipment(OrdersRestApiTester $I): void
    {
        $I->getLocator()->shipment()->facade()->saveOrderShipment(
            $this->quoteTransfer,
            $this->saveOrderTransfer
        );
    }

    /**
     * @param \PyzTest\Glue\Orders\OrdersRestApiTester $I
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    protected function createOrderTransfer(OrdersRestApiTester $I): OrderTransfer
    {
        return $I->getLocator()->sales()->facade()->getOrderByIdSalesOrder($this->saveOrderTransfer->getIdSalesOrder());
    }
}
