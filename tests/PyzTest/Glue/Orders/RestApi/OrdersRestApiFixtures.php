<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Glue\Orders\RestApi;

use Generated\Shared\DataBuilder\ProductConcreteBuilder;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use PyzTest\Glue\Orders\OrdersRestApiTester;
use SprykerTest\Shared\Testify\Fixtures\FixturesBuilderInterface;
use SprykerTest\Shared\Testify\Fixtures\FixturesContainerInterface;

/**
 * Auto-generated group annotations
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
     * @return \Generated\Shared\Transfer\SaveOrderTransfer
     */
    public function getSaveOrderTransfer()
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
     * @param \PyzTest\Glue\Orders\OrdersRestApiTester $I
     *
     * @return \SprykerTest\Shared\Testify\Fixtures\FixturesContainerInterface
     */
    public function buildFixtures(OrdersRestApiTester $I): FixturesContainerInterface
    {
        $this->createCustomer($I);
        $this->createShipmentMethod($I);
        $this->createSaveOrder($I);

        return $this;
    }

    /**
     * @param \PyzTest\Glue\Orders\OrdersRestApiTester $I
     *
     * @return void
     */
    protected function createSaveOrder(OrdersRestApiTester $I): void
    {
        $I->configureTestStateMachine([static::DEFAULT_STATE_MACHINE]);

        $this->saveOrderTransfer = $I->haveOrderFromQuote($this->getQuote(), static::DEFAULT_STATE_MACHINE);

        $I->haveShipment($this->saveOrderTransfer->getIdSalesOrder(), [
            'method' => $this->shipmentMethodTransfer,
        ]);
    }

    /**
     * @param \PyzTest\Glue\Orders\OrdersRestApiTester $I
     *
     * @return void
     */
    protected function createCustomer(OrdersRestApiTester $I): void
    {
        $this->customerTransfer = $I->haveCustomer([
            'password' => static::TEST_PASSWORD,
        ]);
    }

    /**
     * @param \PyzTest\Glue\Orders\OrdersRestApiTester $I
     *
     * @return void
     */
    protected function createShipmentMethod(OrdersRestApiTester $I): void
    {
        $this->shipmentMethodTransfer = $I->haveShipmentMethod(['is_active' => true]);
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function getQuote(): QuoteTransfer
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
            ->withShipment()
            ->withCurrency()
            ->withTotals([
                TotalsTransfer::GRAND_TOTAL => random_int(1000, 10000),
            ])
            ->build();

        $quoteTransfer->setCustomer($this->customerTransfer);

        return $quoteTransfer;
    }
}
