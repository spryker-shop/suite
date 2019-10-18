<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Glue\Checkout\RestApi;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\PaymentMethodTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Generated\Shared\Transfer\StockProductTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use PyzTest\Glue\Checkout\CheckoutRestApiTester;
use SprykerTest\Shared\Testify\Fixtures\FixturesBuilderInterface;
use SprykerTest\Shared\Testify\Fixtures\FixturesContainerInterface;

/**
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Glue
 * @group CheckoutRestApi
 * @group RestApi
 * @group CheckoutRestApiFixtures
 * Add your own group annotations below this line
 * @group EndToEnd
 */
class CheckoutDataRestApiFixtures implements FixturesBuilderInterface, FixturesContainerInterface
{
    protected const TEST_PASSWORD = 'test password';

    /**
     * @var \Generated\Shared\Transfer\CustomerTransfer
     */
    protected $customerTransfer;

    /**
     * @var \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    protected $productConcreteTransfer;

    /**
     * @var \Generated\Shared\Transfer\ShipmentMethodTransfer
     */
    protected $shipmentMethodTransfer;

    /**
     * @var \Generated\Shared\Transfer\QuoteTransfer
     */
    protected $quoteTransfer;

    /**
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function getCustomerTransfer(): CustomerTransfer
    {
        return $this->customerTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function getProductConcreteTransfer(): ProductConcreteTransfer
    {
        return $this->productConcreteTransfer;
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
     * @return \Generated\Shared\Transfer\PaymentMethodTransfer
     */
    public function getPaymentMethodTransfer(): PaymentMethodTransfer
    {
        return $this->paymentMethodTransfer;
    }

    /**
     * @param \PyzTest\Glue\Checkout\CheckoutRestApiTester $I
     *
     * @return \SprykerTest\Shared\Testify\Fixtures\FixturesContainerInterface
     */
    public function buildFixtures(CheckoutRestApiTester $I): FixturesContainerInterface
    {
        $this->createCustomer($I);
        $this->createProductConcrete($I);
        $this->createShipmentMethod($I);
        $this->createQuote($I);

        return $this;
    }

    /**
     * @param \PyzTest\Glue\Checkout\CheckoutRestApiTester $I
     *
     * @return void
     */
    protected function createCustomer(CheckoutRestApiTester $I): void
    {
        $customerTransfer = $I->haveCustomer([
            'password' => static::TEST_PASSWORD,
        ]);

        $customerTransfer->setNewPassword(static::TEST_PASSWORD);

        $this->customerTransfer = $customerTransfer;
    }

    /**
     * @param \PyzTest\Glue\Checkout\CheckoutRestApiTester $I
     *
     * @return void
     */
    protected function createProductConcrete(CheckoutRestApiTester $I): void
    {
        $this->productConcreteTransfer = $I->haveFullProduct();
        $I->haveProductInStock([
            StockProductTransfer::SKU => $this->productConcreteTransfer->getSku(),
        ]);
        $I->havePriceProduct([
            PriceProductTransfer::SKU_PRODUCT => $this->productConcreteTransfer->getSku(),
            PriceProductTransfer::ID_PRODUCT => $this->productConcreteTransfer->getIdProductConcrete(),
            PriceProductTransfer::SKU_PRODUCT_ABSTRACT => $this->productConcreteTransfer->getAbstractSku(),
        ]);
    }

    /**
     * @param \PyzTest\Glue\Checkout\CheckoutRestApiTester $I
     *
     * @return void
     */
    protected function createShipmentMethod(CheckoutRestApiTester $I): void
    {
        $this->shipmentMethodTransfer = $I->haveShipmentMethod(['is_active' => true]);
    }

    /**
     * @param \PyzTest\Glue\Checkout\CheckoutRestApiTester $I
     *
     * @return void
     */
    protected function createQuote(CheckoutRestApiTester $I): void
    {
        $totalsTransfer = new TotalsTransfer();
        $totalsTransfer->setPriceToPay(random_int(1000, 10000));

        $this->quoteTransfer = $I->havePersistentQuote([
            QuoteTransfer::CUSTOMER => $this->customerTransfer,
            QuoteTransfer::TOTALS => $totalsTransfer,
            QuoteTransfer::ITEMS => [
                [
                    ItemTransfer::SKU => $this->productConcreteTransfer->getSku(),
                    ItemTransfer::UNIT_PRICE => random_int(100, 1000),
                ],
            ],
            QuoteTransfer::STORE => [
                StoreTransfer::NAME => $I->getLocator()->store()->client()->getCurrentStore()->getName(),
            ],
        ]);
    }
}
