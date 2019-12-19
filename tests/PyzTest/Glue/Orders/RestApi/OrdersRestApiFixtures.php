<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Glue\Orders\RestApi;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use PyzTest\Glue\Carts\CartsApiTester;
use PyzTest\Glue\Orders\OrdersApiTester;
use SprykerTest\Shared\Testify\Fixtures\FixturesBuilderInterface;
use SprykerTest\Shared\Testify\Fixtures\FixturesContainerInterface;

/**
 * Auto-generated group annotations
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
    /**
     * @var \Generated\Shared\Transfer\SaveOrderTransfer
     */
    protected $saveOrderTransfer;
    /**
     * @var \Generated\Shared\Transfer\CustomerTransfer
     */
    protected $customerTransfer;

    /**
     * @return \Generated\Shared\Transfer\SaveOrderTransfer
     */
    public function getOrderTransfer(): SaveOrderTransfer
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
     * @param \PyzTest\Glue\Orders\OrdersApiTester $I
     *
     * @return \SprykerTest\Shared\Testify\Fixtures\FixturesContainerInterface
     */
    public function buildFixtures(OrdersApiTester $I): FixturesContainerInterface
    {
        $this->createCustomer($I);
        $this->createSaveOrderTransfer($I);

        return $this;
    }

    /**
     * @param \PyzTest\Glue\Orders\OrdersApiTester $I
     *
     * @return void
     */
    protected function createSaveOrderTransfer(OrdersApiTester $I): void
    {
        $testStateMachineProcessName = 'Test01';
        $I->configureTestStateMachine([$testStateMachineProcessName]);
        $saveOrderTransfer = $I->haveOrder([
            ItemTransfer::UNIT_PRICE => 100,
            ItemTransfer::SUM_PRICE => 100,
        ], $testStateMachineProcessName);
        $this->saveOrderTransfer = $saveOrderTransfer;
    }


    /**
     * @param \PyzTest\Glue\Orders\OrdersApiTester $I
     *
     * @return void
     */
    protected function createCustomer(OrdersApiTester $I): void
    {
        $customerTransfer = $I->haveCustomer([
            CustomerTransfer::USERNAME => static::TEST_USERNAME,
            CustomerTransfer::PASSWORD => static::TEST_PASSWORD,
            CustomerTransfer::NEW_PASSWORD => static::TEST_PASSWORD,
        ]);

        $this->customerTransfer = $customerTransfer;
    }
}
