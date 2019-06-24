<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Yves\Checkout\Process\Steps;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\AddressBuilder;
use Generated\Shared\DataBuilder\ItemBuilder;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\DataBuilder\ShipmentBuilder;
use Generated\Shared\Transfer\AddressesTransfer;
use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
use SprykerShop\Yves\CheckoutPage\CheckoutPageConfig;
use SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToCalculationClientInterface;
use SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToCustomerClientInterface;
use SprykerShop\Yves\CheckoutPage\Dependency\Service\CheckoutPageToCustomerServiceInterface;
use SprykerShop\Yves\CheckoutPage\Process\Steps\AddressStep;
use SprykerShop\Yves\CheckoutPage\Process\Steps\AddressStep\AddressStepExecutor;
use SprykerShop\Yves\CheckoutPage\Process\Steps\PostConditionCheckerInterface;
use SprykerShop\Yves\CompanyPage\Plugin\CheckoutPage\CompanyUnitAddressExpanderPlugin;
use SprykerShop\Yves\CustomerPage\Plugin\CheckoutPage\CustomerAddressExpanderPlugin;
use Symfony\Component\HttpFoundation\Request;

/**
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Yves
 * @group Checkout
 * @group Process
 * @group Steps
 * @group AddressStepTest
 * Add your own group annotations below this line
 */
class AddressStepTest extends Unit
{
    /**
     * @return void
     */
    public function testExecuteAddressStepWhenGuestIsSubmittedShouldUseDataFromAddressFromForm()
    {
        $addressStep = $this->createAddressStep(new CustomerTransfer());

        $quoteTransfer = new QuoteTransfer();
        $addressTransfer = new AddressTransfer();
        $addressTransfer->setAddress1('add1');
        $quoteTransfer->setShippingAddress($addressTransfer);
        $quoteTransfer->setBillingAddress(clone $addressTransfer);

        $addressStep->execute($this->createRequest(), $quoteTransfer);

        $this->assertEquals($addressTransfer->getAddress1(), $quoteTransfer->getShippingAddress()->getAddress1());
        $this->assertEquals($addressTransfer->getAddress1(), $quoteTransfer->getBillingAddress()->getAddress1());
    }

    /**
     * @return void
     */
    public function testExecuteAddressStepWhenGuestIsSubmittedShouldUseDataFromAddressFromFormWithItemLevelShippingAddresses()
    {
        $addressStep = $this->createAddressStep(new CustomerTransfer());

        $addressTransfer = (new AddressBuilder([AddressTransfer::ADDRESS1 => 'add1']))->build();

        $quoteTransfer = (new QuoteBuilder())
            ->withItem((new ItemBuilder())
                ->withShipment()
            )->build();

        $quoteTransfer->getItems()[0]->getShipment()->setShippingAddress($addressTransfer);
        $quoteTransfer->setBillingAddress(clone $addressTransfer);

        $addressStep->execute($this->createRequest(), $quoteTransfer);

        $this->assertEquals($addressTransfer->getAddress1(), $quoteTransfer->getItems()[0]->getShipment()->getShippingAddress()->getAddress1());
        $this->assertEquals($addressTransfer->getAddress1(), $quoteTransfer->getBillingAddress()->getAddress1());
    }

    /**
     * @return void
     */
    public function testExecuteAddressStepWhenLoggedInUserCreatesNewAddress()
    {
        $addressTransfer = new AddressTransfer();
        $addressTransfer->setIdCustomerAddress(1);
        $addressTransfer->setAddress1('add1');

        $customerTransfer = new CustomerTransfer();
        $customerTransfer->addBillingAddress($addressTransfer);
        $shippingAddress = clone $addressTransfer;
        $shippingAddress->setIdCustomerAddress(2);

        $addressesTransfer = new AddressesTransfer();
        $addressesTransfer->addAddress($addressTransfer);
        $addressesTransfer->addAddress($shippingAddress);
        $customerTransfer->setAddresses($addressesTransfer);

        $addressStep = $this->createAddressStep($customerTransfer);

        $quoteTransfer = new QuoteTransfer();

        $billingAddressTransfer = new AddressTransfer();
        $billingAddressTransfer->setIdCustomerAddress(1);
        $quoteTransfer->setBillingAddress($billingAddressTransfer);

        $shippingAddressTransfer = new AddressTransfer();
        $shippingAddressTransfer->setIdCustomerAddress(1);
        $quoteTransfer->setShippingAddress($shippingAddressTransfer);

        $addressStep->execute($this->createRequest(), $quoteTransfer);

        $this->assertEquals($shippingAddress->getAddress1(), $quoteTransfer->getShippingAddress()->getAddress1());
        $this->assertEquals($addressTransfer->getAddress1(), $quoteTransfer->getBillingAddress()->getAddress1());
    }

    /**
     * @return void
     */
    public function testExecuteAddressStepWhenLoggedInUserCreatesNewAddressWithItemLevelShippingAddresses()
    {
        $addressTransfer = new AddressTransfer();
        $addressTransfer->setIdCustomerAddress(1);
        $addressTransfer->setAddress1('add1');

        $customerTransfer = new CustomerTransfer();
        $customerTransfer->addBillingAddress($addressTransfer);
        $shippingAddress = clone $addressTransfer;
        $shippingAddress->setIdCustomerAddress(2);

        $addressesTransfer = new AddressesTransfer();
        $addressesTransfer->addAddress($addressTransfer);
        $addressesTransfer->addAddress($shippingAddress);
        $customerTransfer->setAddresses($addressesTransfer);

        $addressStep = $this->createAddressStep($customerTransfer);

        $quoteTransfer = (new QuoteBuilder())
            ->withBillingAddress((new AddressBuilder([AddressTransfer::ID_CUSTOMER_ADDRESS => 1])))
            ->withItem((new ItemBuilder())
                ->withShipment((new ShipmentBuilder())
                    ->withShippingAddress((new AddressBuilder([AddressTransfer::ID_CUSTOMER_ADDRESS => 1]))
                    )
                )
            )->build();

        $addressStep->execute($this->createRequest(), $quoteTransfer);

        $this->assertEquals($shippingAddress->getAddress1(), $quoteTransfer->getItems()[0]->getShipment()->getShippingAddress()->getAddress1());
        $this->assertEquals($addressTransfer->getAddress1(), $quoteTransfer->getBillingAddress()->getAddress1());
    }

    /**
     * @return void
     */
    public function testExecuteWhenBillingAddressSameAsShippingSelectedShouldCopyShipmentIntoBilling()
    {
        $addressTransfer = new AddressTransfer();
        $addressTransfer->setIdCustomerAddress(1);
        $addressTransfer->setAddress1('add1');

        $customerTransfer = new CustomerTransfer();
        $addressesTransfer = new AddressesTransfer();
        $addressesTransfer->addAddress($addressTransfer);
        $customerTransfer->setAddresses($addressesTransfer);

        $addressStep = $this->createAddressStep($customerTransfer);

        $quoteTransfer = new QuoteTransfer();
        $quoteTransfer->setBillingSameAsShipping(true);

        $shippingAddressTransfer = new AddressTransfer();
        $shippingAddressTransfer->setIdCustomerAddress(1);
        $quoteTransfer->setShippingAddress($shippingAddressTransfer);

        $addressStep->execute($this->createRequest(), $quoteTransfer);

        $this->assertEquals($addressTransfer->getAddress1(), $quoteTransfer->getShippingAddress()->getAddress1());
        $this->assertEquals($addressTransfer->getAddress1(), $quoteTransfer->getBillingAddress()->getAddress1());
    }

    /**
     * @return void
     */
    public function testExecuteWhenBillingAddressSameAsShippingSelectedShouldCopyShipmentIntoBillingWithItemLevelShippingAddresses()
    {
        $addressTransfer = (new AddressBuilder([
            AddressTransfer::ID_CUSTOMER_ADDRESS => 1,
            AddressTransfer::ADDRESS1 => 'add1',
        ]))->build();

        $customerTransfer = new CustomerTransfer();
        $addressesTransfer = new AddressesTransfer();
        $addressesTransfer->addAddress($addressTransfer);
        $customerTransfer->setAddresses($addressesTransfer);

        $addressStep = $this->createAddressStep($customerTransfer);

        $quoteTransfer = (new QuoteBuilder([QuoteTransfer::BILLING_SAME_AS_SHIPPING => true]))
            ->withItem((new ItemBuilder())
                ->withShipment((new ShipmentBuilder())
                    ->withShippingAddress((new AddressBuilder([AddressTransfer::ID_CUSTOMER_ADDRESS => 1]))
                    )
                )
            )->build();

        $addressStep->execute($this->createRequest(), $quoteTransfer);

        $this->assertEquals($addressTransfer->getAddress1(), $quoteTransfer->getItems()[0]->getShipment()->getShippingAddress()->getAddress1());
        $this->assertEquals($addressTransfer->getAddress1(), $quoteTransfer->getBillingAddress()->getAddress1());
    }

    /**
     * @return void
     */
    public function testPostConditionWhenNoAddressesSetShouldReturnFalse()
    {
        $addressStep = $this->createAddressStep(new CustomerTransfer());
        $this->assertFalse($addressStep->postCondition(new QuoteTransfer()));
    }

    /**
     * @return void
     */
    public function testPostConditionIfShippingIsEmptyShouldReturnFalse()
    {
        $addressStep = $this->createAddressStep(new CustomerTransfer());
        $quoteTransfer = new QuoteTransfer();
        $quoteTransfer->setBillingAddress(new AddressTransfer());

        $this->assertFalse($addressStep->postCondition($quoteTransfer));
    }

    /**
     * @return void
     */
    public function testPostConditionIfBillingIsEmptyShouldReturnFalse()
    {
        $addressStep = $this->createAddressStep(new CustomerTransfer());
        $quoteTransfer = new QuoteTransfer();
        $quoteTransfer->setShippingAddress(new AddressTransfer());

        $this->assertFalse($addressStep->postCondition($quoteTransfer));
    }

    /**
     * @return void
     */
    public function testPostConditionIfBillingIsEmptyShouldReturnFalseWithItemLevelShippingAddresses()
    {
        $addressStep = $this->createAddressStep(new CustomerTransfer());

        $quoteTransfer = (new QuoteBuilder())
            ->withItem((new ItemBuilder())
                ->withShipment((new ShipmentBuilder())
                    ->withShippingAddress()
                )
            )->build();

        $this->assertFalse($addressStep->postCondition($quoteTransfer));
    }

    /**
     * @return void
     */
    public function testPostConditionIfAddressesIsSetShouldReturnTrue()
    {
        $addressStep = $this->createAddressStep(new CustomerTransfer());
        $quoteTransfer = new QuoteTransfer();
        $quoteTransfer->setShippingAddress(new AddressTransfer());
        $quoteTransfer->setBillingAddress(new AddressTransfer());

        $this->assertFalse($addressStep->postCondition($quoteTransfer));
    }

    /**
     * @return void
     */
    public function testPostConditionIfAddressesIsSetShouldReturnTrueWithItemLevelShippingAddresses()
    {
        $addressStep = $this->createAddressStep(new CustomerTransfer());

        $quoteTransfer = (new QuoteBuilder())
            ->withBillingAddress()
            ->withItem((new ItemBuilder())
                ->withShipment((new ShipmentBuilder())
                    ->withShippingAddress()
                )
            )->build();

        $this->assertFalse($addressStep->postCondition($quoteTransfer));
    }

    /**
     * @return void
     */
    public function testRequireInputShouldReturnTrue()
    {
        $addressStep = $this->createAddressStep(new CustomerTransfer());
        $this->assertTrue($addressStep->requireInput(new QuoteTransfer()));
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \SprykerShop\Yves\CheckoutPage\Process\Steps\AddressStep|\PHPUnit_Framework_MockObject_MockObject
     */
    protected function createAddressStep(CustomerTransfer $customerTransfer)
    {
        $calculationClientMock = $this->createCalculationClientMock();
        $stepExecutorMock = $this->createStepExecutorMock($customerTransfer);
        $postConditionMock = $this->createPostConditionCheckerMock();
        $checkoutPageConfigMock = $this->createCheckoutPageConfigMock();

        $addressStepMock = $this->getMockBuilder(AddressStep::class)
            ->setMethods(['getDataClass'])
            ->setConstructorArgs([
                $calculationClientMock,
                $stepExecutorMock,
                $postConditionMock,
                $checkoutPageConfigMock,
                'address_step',
                'escape_route',
            ])
            ->getMock();

        $addressStepMock->method('getDataClass')->willReturn(new QuoteTransfer());

        return $addressStepMock;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToCalculationClientInterface
     */
    protected function createCalculationClientMock()
    {
        return $this->getMockBuilder(CheckoutPageToCalculationClientInterface::class)->getMock();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\SprykerShop\Yves\CheckoutPage\Dependency\Service\CheckoutPageToCustomerServiceInterface
     */
    protected function createCustomerServiceMock()
    {
        return $this->getMockBuilder(CheckoutPageToCustomerServiceInterface::class)->getMock();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\SprykerShop\Yves\CheckoutPageExtension\Dependency\Plugin\AddressTransferExpanderPluginInterface
     */
    protected function createCustomerAddressExpanderPluginMock()
    {
        return $this->getMockBuilder(CustomerAddressExpanderPlugin::class)
            ->enableProxyingToOriginalMethods()
            ->getMock();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\SprykerShop\Yves\CheckoutPageExtension\Dependency\Plugin\AddressTransferExpanderPluginInterface
     */
    protected function createCompanyUnitAddressExpanderPluginMock()
    {
        return $this->getMockBuilder(CompanyUnitAddressExpanderPlugin::class)
            ->enableProxyingToOriginalMethods()
            ->getMock();
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|\SprykerShop\Yves\CheckoutPage\Process\Steps\AddressStep\AddressStepExecutor
     */
    protected function createStepExecutorMock(CustomerTransfer $customerTransfer)
    {
        $customerClientMock = $this->createCustomerClientMock($customerTransfer);
        $customerService = $this->createCustomerServiceMock();

        return $this->getMockBuilder(AddressStepExecutor::class)
            ->setConstructorArgs([
                $customerService,
                $customerClientMock,
                [
                    $this->createCustomerAddressExpanderPluginMock(),
                    $this->createCompanyUnitAddressExpanderPluginMock(),
                ],
            ])
            ->enableProxyingToOriginalMethods()
            ->getMock();
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Request
     */
    protected function createRequest()
    {
        return Request::createFromGlobals();
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|\SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToCustomerClientInterface
     */
    protected function createCustomerClientMock(CustomerTransfer $customerTransfer)
    {
        $customerClientMock = $this->getMockBuilder(CheckoutPageToCustomerClientInterface::class)->getMock();

        $customerClientMock->method('getCustomer')->willReturn($customerTransfer);

        return $customerClientMock;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\SprykerShop\Yves\CheckoutPage\Process\Steps\PostConditionCheckerInterface
     */
    protected function createPostConditionCheckerMock()
    {
        return $this->getMockBuilder(PostConditionCheckerInterface::class)->getMock();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\SprykerShop\Yves\CheckoutPage\CheckoutPageConfig
     */
    protected function createCheckoutPageConfigMock()
    {
        return $this->getMockBuilder(CheckoutPageConfig::class)->getMock();
    }
}
