<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Glue\Carts\RestApi\Fixtures;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\StockProductTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use PyzTest\Glue\Carts\CartsApiTester;
use Spryker\Zed\Store\Business\StoreFacadeInterface;
use SprykerTest\Shared\Testify\Fixtures\FixturesBuilderInterface;
use SprykerTest\Shared\Testify\Fixtures\FixturesContainerInterface;

/**
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Glue
 * @group Carts
 * @group RestApi
 * @group GuestCartsRestApiFixtures
 * Add your own group annotations below this line
 * @group EndToEnd
 */
class GuestCartsRestApiFixtures implements FixturesBuilderInterface, FixturesContainerInterface
{
    use CartsRestApiFixturesTrait;

    public const QUANTITY_FOR_ITEM_UPDATE = 33;
    public const TEST_GUEST_CART_NAME = 'Test guest cart name';
    public const CURRENCY_EUR = 'EUR';
    public const ANONYMOUS_PREFIX = 'anonymous:';

    /**
     * @var string
     */
    protected $guestCustomerReference;

    /**
     * @var \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    protected $productConcreteTransfer;

    /**
     * @var \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    protected $productConcreteTransfer1;

    /**
     * @var \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    protected $productConcreteTransfer2;

    /**
     * @var string
     */
    protected $valueForAnonymousCustomerReference2;

    /**
     * @var string
     */
    protected $valueForAnonymousCustomerReference3;

    /**
     * @var \Generated\Shared\Transfer\QuoteTransfer
     */
    protected $guestQuoteTransfer;

    /**
     * @var \Generated\Shared\Transfer\QuoteTransfer
     */
    protected $guestQuoteTransfer1;

    /**
     * @var \Generated\Shared\Transfer\QuoteTransfer
     */
    protected $guestQuoteTransfer2;

    /**
     * @var \Generated\Shared\Transfer\QuoteTransfer
     */
    protected $emptyGuestQuoteTransfer;

    /**
     * @return string
     */
    public function getGuestCustomerReference(): string
    {
        return $this->guestCustomerReference;
    }

    /**
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function getProductConcreteTransfer(): ProductConcreteTransfer
    {
        return $this->productConcreteTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function getGuestQuoteTransfer(): QuoteTransfer
    {
        return $this->guestQuoteTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function getProductConcreteTransfer1(): ProductConcreteTransfer
    {
        return $this->productConcreteTransfer1;
    }

    /**
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function getProductConcreteTransfer2(): ProductConcreteTransfer
    {
        return $this->productConcreteTransfer2;
    }

    /**
     * @return string
     */
    public function getValueForAnonymousCustomerReference2(): string
    {
        return $this->valueForAnonymousCustomerReference2;
    }

    /**
     * @return string
     */
    public function getValueForAnonymousCustomerReference3(): string
    {
        return $this->valueForAnonymousCustomerReference3;
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function getGuestQuoteTransfer2(): QuoteTransfer
    {
        return $this->guestQuoteTransfer2;
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function getEmptyGuestQuoteTransfer(): QuoteTransfer
    {
        return $this->emptyGuestQuoteTransfer;
    }

    /**
     * @param \PyzTest\Glue\Carts\CartsApiTester $I
     *
     * @return \SprykerTest\Shared\Testify\Fixtures\FixturesContainerInterface
     */
    public function buildFixtures(CartsApiTester $I): FixturesContainerInterface
    {
        $this->createGuestQuote($I);
        $this->productConcreteTransfer1 = $this->createProductData($I);
        $this->productConcreteTransfer2 = $this->createProductData($I);
        $this->valueForAnonymousCustomerReference2 = $this->createValueForAnonymousCustomerReference();
        $this->valueForAnonymousCustomerReference3 = $this->createValueForAnonymousCustomerReference();
        $this->guestQuoteTransfer2 = $this->createQuote(
            $I,
            static::ANONYMOUS_PREFIX . $this->valueForAnonymousCustomerReference2
        );
        $this->emptyGuestQuoteTransfer = $this->createEmptyQuote(
            $I,
            static::ANONYMOUS_PREFIX . $this->valueForAnonymousCustomerReference3
        );

        return $this;
    }

    /**
     * @param \PyzTest\Glue\Carts\CartsApiTester $I
     * @param string $customerReference
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function createQuote(CartsApiTester $I, string $customerReference): QuoteTransfer
    {
        $totalsTransfer = new TotalsTransfer();
        $totalsTransfer->setPriceToPay(random_int(1000, 10000));
        return $I->havePersistentQuote([
            QuoteTransfer::CUSTOMER => (new CustomerTransfer())->setCustomerReference($customerReference),
            QuoteTransfer::TOTALS => $totalsTransfer,
            QuoteTransfer::ITEMS => [
                [
                    ItemTransfer::SKU => $this->productConcreteTransfer1->getSku(),
                    ItemTransfer::GROUP_KEY => $this->productConcreteTransfer1->getSku(),
                    ItemTransfer::ABSTRACT_SKU => $this->productConcreteTransfer1->getAbstractSku(),
                    ItemTransfer::ID => $this->productConcreteTransfer1->getIdProductConcrete(),
                    ItemTransfer::UNIT_PRICE => random_int(100, 1000),
                    ItemTransfer::UNIT_GROSS_PRICE => random_int(100, 1000),
                    ItemTransfer::QUANTITY => 5,
                ],
            ],
        ]);
    }

    /**
     * @param \PyzTest\Glue\Carts\CartsApiTester $I
     *
     * @return void
     */
    protected function createGuestQuote(CartsApiTester $I): void
    {
        $this->productConcreteTransfer = $I->haveFullProduct();
        $this->guestCustomerReference = $this->createGuestCustomerReference();
        $guestCustomerTransfer = (new CustomerTransfer())
            ->setCustomerReference(static::ANONYMOUS_PREFIX . $this->guestCustomerReference);
        $this->guestQuoteTransfer = $this->createPersistentQuote($I, $guestCustomerTransfer, [$this->productConcreteTransfer]);
    }

    /**
     * @param \PyzTest\Glue\Carts\CartsApiTester $I
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    protected function createProductData(CartsApiTester $I): ProductConcreteTransfer
    {
        $productConcreteTransfer = $I->haveFullProduct();
        $I->haveProductInStockForStore($this->getStoreFacade($I)->getCurrentStore(), [
            StockProductTransfer::SKU => $productConcreteTransfer->getSku(),
            StockProductTransfer::IS_NEVER_OUT_OF_STOCK => 1,
        ]);
        $priceProductOverride = [
            PriceProductTransfer::SKU_PRODUCT_ABSTRACT => $productConcreteTransfer->getAbstractSku(),
            PriceProductTransfer::SKU_PRODUCT => $productConcreteTransfer->getSku(),
            PriceProductTransfer::ID_PRODUCT => $productConcreteTransfer->getIdProductConcrete(),
            PriceProductTransfer::PRICE_TYPE_NAME => 'DEFAULT',
            PriceProductTransfer::MONEY_VALUE => [
                MoneyValueTransfer::NET_AMOUNT => 777,
                MoneyValueTransfer::GROSS_AMOUNT => 888,
                MoneyValueTransfer::FK_CURRENCY => $I->getLocator()->currency()->facade()->getDefaultCurrencyForCurrentStore()->getIdCurrency(),
                MoneyValueTransfer::CURRENCY => $I->getLocator()->currency()->facade()->getDefaultCurrencyForCurrentStore(),
            ],
        ];
        $I->havePriceProduct($priceProductOverride);

        return $productConcreteTransfer;
    }

    /**
     * @param \PyzTest\Glue\Carts\CartsApiTester $I
     * @param string $customerReference
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function createEmptyQuote(CartsApiTester $I, string $customerReference): QuoteTransfer
    {
        return $I->havePersistentQuote([
            QuoteTransfer::CUSTOMER => (new CustomerTransfer())->setCustomerReference($customerReference),
        ]);
    }

    /**
     * @param \PyzTest\Glue\Carts\CartsApiTester $I
     *
     * @return \Spryker\Zed\Store\Business\StoreFacadeInterface
     */
    protected function getStoreFacade(CartsApiTester $I): StoreFacadeInterface
    {
        return $I->getLocator()->store()->facade();
    }

    /**
     * @return string
     */
    protected function createValueForAnonymousCustomerReference(): string
    {
        return uniqid('testReference', true);
    }
}
