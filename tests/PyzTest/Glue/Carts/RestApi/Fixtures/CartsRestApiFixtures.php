<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Glue\Carts\RestApi\Fixtures;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\StockProductTransfer;
use PyzTest\Glue\Carts\CartsApiTester;
use Spryker\Glue\CartsRestApi\CartsRestApiConfig;
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
 * @group CartsRestApiFixtures
 * Add your own group annotations below this line
 * @group EndToEnd
 */
class CartsRestApiFixtures implements FixturesBuilderInterface, FixturesContainerInterface
{
    use CartsRestApiFixturesTrait;

    protected const TEST_USERNAME = 'UserCartsRestApiFixtures';
    protected const TEST_PASSWORD = 'password';

    public const QUANTITY_FOR_ITEM_UPDATE = 33;
    public const STORE_DE = 'DE';
    public const TEST_CART_NAME = 'Test cart name';
    public const CURRENCY_EUR = 'EUR';

    /**
     * @var \Generated\Shared\Transfer\CustomerTransfer
     */
    protected $customerTransfer;

    /**
     * @var \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    protected $productConcreteTransfer;

    /**
     * @var \Generated\Shared\Transfer\QuoteTransfer
     */
    protected $quoteTransfer;

    /**
     * @var \Generated\Shared\Transfer\QuoteTransfer
     */
    protected $emptyQuoteTransfer;

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
    protected $cartResourceEntityTag;

    /**
     * @var string
     */
    protected $emptyCartResourceEntityTag;

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
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function getQuoteTransfer(): QuoteTransfer
    {
        return $this->quoteTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function getEmptyQuoteTransfer(): QuoteTransfer
    {
        return $this->emptyQuoteTransfer;
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
    public function getCartResourceEntityTag(): string
    {
        return $this->cartResourceEntityTag;
    }

    /**
     * @return string
     */
    public function getEmptyCartResourceEntityTag(): string
    {
        return $this->emptyCartResourceEntityTag;
    }

    /**
     * @param \PyzTest\Glue\Carts\CartsApiTester $I
     *
     * @return \SprykerTest\Shared\Testify\Fixtures\FixturesContainerInterface
     */
    public function buildFixtures(CartsApiTester $I): FixturesContainerInterface
    {
        $this->createQuote($I);

        return $this;
    }

    /**
     * @param \PyzTest\Glue\Carts\CartsApiTester $I
     *
     * @return void
     */
    protected function createQuote(CartsApiTester $I): void
    {
        $this->createCustomer($I);
        $this->productConcreteTransfer = $this->createProductData($I);

        $this->quoteTransfer = $this->createPersistentQuote($I, $this->customerTransfer, [$this->productConcreteTransfer]);

        $this->cartResourceEntityTag = $this->createCartResourceEntityTag(
            $I,
            $this->quoteTransfer->getUuid(),
            $this->quoteTransfer->toArray()
        );

        $this->productConcreteTransfer1 = $this->createProductData($I);
        $this->productConcreteTransfer2 = $this->createProductData($I);

        $this->emptyQuoteTransfer = $this->createEmptyQuote($I, $this->getCustomerTransfer()->getCustomerReference());
        $this->emptyCartResourceEntityTag = $this->createCartResourceEntityTag(
            $I,
            $this->emptyQuoteTransfer->getUuid(),
            $this->emptyQuoteTransfer->toArray()
        );
    }

    /**
     * @param \PyzTest\Glue\Carts\CartsApiTester $I
     *
     * @return void
     */
    protected function createCustomer(CartsApiTester $I): void
    {
        $customerTransfer = $I->haveCustomer([
            CustomerTransfer::USERNAME => static::TEST_USERNAME,
            CustomerTransfer::PASSWORD => static::TEST_PASSWORD,
            CustomerTransfer::NEW_PASSWORD => static::TEST_PASSWORD,
        ]);
        $this->customerTransfer = $customerTransfer;
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
     * @param string $cartUuid
     * @param array $attributes
     *
     * @return string
     */
    protected function createCartResourceEntityTag(
        CartsApiTester $I,
        string $cartUuid,
        array $attributes
    ): string {
        return $I->haveEntityTag(
            CartsRestApiConfig::RESOURCE_CARTS,
            $cartUuid,
            $attributes
        );
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
}
