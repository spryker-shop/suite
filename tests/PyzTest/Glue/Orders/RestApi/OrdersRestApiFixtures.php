<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Glue\Orders\RestApi;

use Generated\Shared\DataBuilder\RestPaymentBuilder;
use Generated\Shared\Transfer\SaveOrderTransfer;
use PyzTest\Glue\Orders\OrdersApiTester;
use SprykerTest\Shared\Testify\Fixtures\FixturesBuilderInterface;
use SprykerTest\Shared\Testify\Fixtures\FixturesContainerInterface;
use SprykerTest\Zed\Sales\Helper\BusinessHelper;

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
    /**
     * @var \Generated\Shared\Transfer\SaveOrderTransfer
     */
    protected $saveOrderTransfer;

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
        $this->createSaveOrderTransfer($I);
        dd($this->saveOrderTransfer);

        return $this;
    }

    /**
     * @param \PyzTest\Glue\Orders\OrdersApiTester $I
     *
     * @return void
     */
    protected function createSaveOrderTransfer(OrdersApiTester $I): void
    {
//        $a = (new RestPaymentBuilder([
//            'paymentProvider' => 'dummyPayment',
//            'paymentMethod' => 'invoice',
//            'paymentSelection' => 'dummyPaymentInvoice',
//        ]));
        $this->saveOrderTransfer = $I->haveOrder();

//        $this->saveOrderTransfer = $I->haveOrder($a);
    }

    protected function createPayment(OrdersApiTester $I)
    {

        $this->payment = $I->havePayment();
    }
}
