<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Glue\Orders\RestApi;

use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use PyzTest\Glue\Orders\OrdersApiTester;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use SprykerTest\Shared\Testify\Fixtures\FixturesBuilderInterface;
use SprykerTest\Shared\Testify\Fixtures\FixturesContainerInterface;

/**
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Glue
 * @group Orders
 * @group RestApi
 * Add your own group annotations below this line
 * @group EndToEnd
 */
class OrdersRestApiFixtures implements FixturesBuilderInterface, FixturesContainerInterface
{
    protected const TEST_USERNAME = 'test username';
    protected const TEST_PASSWORD = 'test password';

    protected const TEST_GRAND_TOTAL = 1;

    /**
     * @var \Generated\Shared\Transfer\SaveOrderTransfer
     */
    protected $saveOrderTransfer;

    /**
     * @var \Generated\Shared\Transfer\CustomerTransfer
     */
    protected $customerTransfer1;

    /**
     * @var \Generated\Shared\Transfer\CustomerTransfer
     */
    protected $customerTransfer2;

    /**
     * @var \Spryker\Shared\Kernel\Transfer\AbstractTransfer
     */
    protected $quoteTransfer;

    /**
     * @var \Generated\Shared\Transfer\ProductConcreteTransfer[]
     */
    protected $productTransfers;

    /**
     * @var string
     */
    protected $stateMachineProcessName;

    /**
     * @return string
     */
    protected function getStateMachineProcessName(): string
    {
        return $this->stateMachineProcessName;
    }

    /**
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function getCustomerTransfer1(): CustomerTransfer
    {
        return $this->customerTransfer1;
    }

    /**
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function getCustomerTransfer2(): CustomerTransfer
    {
        return $this->customerTransfer2;
    }

    /**
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer[]
     */
    protected function getProductTransfers(): array
    {
        return $this->productTransfers;
    }

    /**
     * @return \Spryker\Shared\Kernel\Transfer\AbstractTransfer
     */
    protected function getQuoteTransfer(): AbstractTransfer
    {
        return $this->quoteTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\SaveOrderTransfer
     */
    public function getOrderTransfer(): SaveOrderTransfer
    {
        return $this->saveOrderTransfer;
    }

    /**
     * @param \PyzTest\Glue\Orders\OrdersApiTester $I
     *
     * @return \SprykerTest\Shared\Testify\Fixtures\FixturesContainerInterface
     */
    public function buildFixtures(OrdersApiTester $I): FixturesContainerInterface
    {
        $this->createStateMachine($I);
        $this->productTransfers[] = $this->createProduct($I);

        $this->customerTransfer1 = $this->createCustomer($I, static::TEST_USERNAME, static::TEST_PASSWORD);

        $this->customerTransfer2 = $this->createCustomer($I, static::TEST_USERNAME, static::TEST_PASSWORD);
        $this->quoteTransfer = $this->createQuote($this->customerTransfer2);
        $this->saveOrderTransfer = $this->createOrderFromQuote($I);

        return $this;
    }

    /**
     * @param \PyzTest\Glue\Orders\OrdersApiTester $I
     *
     * @return void
     */
    protected function createStateMachine(OrdersApiTester $I): void
    {
        $testStateMachineProcessName = 'DummyPayment01';
        $I->configureTestStateMachine([$testStateMachineProcessName]);

        $this->stateMachineProcessName = $testStateMachineProcessName;
    }

    /**
     * @param \PyzTest\Glue\Orders\OrdersApiTester $I
     * @param string $name
     * @param string $password
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    protected function createCustomer(OrdersApiTester $I, string $name, string $password): CustomerTransfer
    {
        return $I->haveCustomer([
            CustomerTransfer::USERNAME => $name,
            CustomerTransfer::PASSWORD => $password,
            CustomerTransfer::NEW_PASSWORD => $password,
        ]);
    }

    /**
     * @param \PyzTest\Glue\Orders\OrdersApiTester $I
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function createProduct(OrdersApiTester $I): ProductConcreteTransfer
    {
        $productTransfer = $I->haveProduct();

        return $productTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Spryker\Shared\Kernel\Transfer\AbstractTransfer
     */
    public function createQuote(CustomerTransfer $customerTransfer): AbstractTransfer
    {
        return (new QuoteBuilder())
            ->withItem($this->getProductTransfers())
            ->withCustomer([CustomerTransfer::CUSTOMER_REFERENCE => $customerTransfer->getCustomerReference()])
            ->withTotals([TotalsTransfer::GRAND_TOTAL => static::TEST_GRAND_TOTAL])
            ->withShippingAddress()
            ->withBillingAddress()
            ->withCurrency()
            ->withPayment()
            ->build();
    }

    /**
     * @param \PyzTest\Glue\Orders\OrdersApiTester $I
     *
     * @return \Generated\Shared\Transfer\SaveOrderTransfer
     */
    public function createOrderFromQuote(OrdersApiTester $I): SaveOrderTransfer
    {
        return $I->haveOrderFromQuote($this->getQuoteTransfer(), $this->getStateMachineProcessName());
    }
}
