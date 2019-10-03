<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Glue\Carts\RestApi;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\StockProductTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use PyzTest\Glue\Carts\CartsApiTester;
use Spryker\Glue\CartsRestApi\CartsRestApiConfig;
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
    /**
     * @var \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    protected $productConcreteTransfer1;

    /**
     * @var \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    protected $productConcreteTransfer2;

    /**
     * @var \Generated\Shared\Transfer\CustomerTransfer
     */
    protected $customerTransfer;

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
     * @var \Generated\Shared\Transfer\QuoteTransfer
     */
    protected $quoteTransfer;

    /**
     * @var \Generated\Shared\Transfer\QuoteTransfer
     */
    protected $emptyQuoteTransfer;

    /**
     * @var string
     */
    protected $cartResourceEntityTag;

    /**
     * @var string
     */
    protected $emptyCartResourceEntityTag;

    /**
     * @var string
     */
    protected $valueForAnonymousCustomerReference1;

    /**
     * @var string
     */
    protected $valueForAnonymousCustomerReference2;

    /**
     * @var string
     */
    protected $valueForAnonymousCustomerReference3;

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
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function getCustomerTransfer(): CustomerTransfer
    {
        return $this->customerTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function getGuestQuoteTransfer1(): QuoteTransfer
    {
        return $this->guestQuoteTransfer1;
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
     * @return string
     */
    public function getValueForAnonymousCustomerReference1(): string
    {
        return $this->valueForAnonymousCustomerReference1;
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
     * @param \PyzTest\Glue\Carts\CartsApiTester $I
     *
     * @return \SprykerTest\Shared\Testify\Fixtures\FixturesContainerInterface
     */
    public function buildFixtures(CartsApiTester $I): FixturesContainerInterface
    {
        $this->createCustomer($I);
        $this->productConcreteTransfer1 = $this->createProductData($I);
        $this->productConcreteTransfer2 = $this->createProductData($I);
        $this->emptyQuoteTransfer = $this->createEmptyQuote($I, $this->getCustomerTransfer()->getCustomerReference());
        $this->emptyCartResourceEntityTag = $this->createCartResourceEntityTag(
            $I,
            $this->emptyQuoteTransfer->getUuid(),
            $this->emptyQuoteTransfer->toArray()
        );
        $this->quoteTransfer = $this->createQuote($I, $this->getCustomerTransfer()->getCustomerReference());
        $this->cartResourceEntityTag = $this->createCartResourceEntityTag(
            $I,
            $this->quoteTransfer->getUuid(),
            $this->quoteTransfer->toArray()
        );
        $this->valueForAnonymousCustomerReference1 = $this->createValueForAnonymousCustomerReference();
        $this->valueForAnonymousCustomerReference2 = $this->createValueForAnonymousCustomerReference();
        $this->valueForAnonymousCustomerReference3 = $this->createValueForAnonymousCustomerReference();
        $this->guestQuoteTransfer1 = $this->createQuote(
            $I,
            $I::ANONYMOUS_PREFIX . $this->valueForAnonymousCustomerReference1
        );
        $this->guestQuoteTransfer2 = $this->createQuote(
            $I,
            $I::ANONYMOUS_PREFIX . $this->valueForAnonymousCustomerReference2
        );
        $this->emptyGuestQuoteTransfer = $this->createEmptyQuote(
            $I,
            $I::ANONYMOUS_PREFIX . $this->valueForAnonymousCustomerReference3
        );

        return $this;
    }

    /**
     * @param \PyzTest\Glue\Carts\CartsApiTester $I
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    protected function createProductData(CartsApiTester $I): ProductConcreteTransfer
    {
        $productConcreteTransfer = $I->haveFullProduct();

        $I->haveProductInStock([
            StockProductTransfer::SKU => $productConcreteTransfer->getSku(),
            StockProductTransfer::IS_NEVER_OUT_OF_STOCK => 1,
        ]);

        $priceProductOverride = [
            PriceProductTransfer::ID_PRICE_PRODUCT => $productConcreteTransfer->getFkProductAbstract(),
            PriceProductTransfer::SKU_PRODUCT_ABSTRACT => $productConcreteTransfer->getAbstractSku(),
            PriceProductTransfer::ID_PRODUCT => $productConcreteTransfer->getIdProductConcrete(),
            PriceProductTransfer::PRICE_TYPE_NAME => 'DEFAULT',
            PriceProductTransfer::MONEY_VALUE => [
                MoneyValueTransfer::NET_AMOUNT => 777,
                MoneyValueTransfer::GROSS_AMOUNT => 888,
            ],
        ];

        $I->havePriceProduct($priceProductOverride);

        return $productConcreteTransfer;
    }

    /**
     * @param \PyzTest\Glue\Carts\CartsApiTester $I
     *
     * @return void
     */
    protected function createCustomer(CartsApiTester $I): void
    {
        $customerTransfer = $I->haveCustomer([
            CustomerTransfer::USERNAME => $I::TEST_USERNAME,
            CustomerTransfer::PASSWORD => $I::TEST_PASSWORD,
            CustomerTransfer::NEW_PASSWORD => $I::TEST_PASSWORD,
        ]);

        $this->customerTransfer = $customerTransfer;
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
     * @return string
     */
    protected function createValueForAnonymousCustomerReference(): string
    {
        return uniqid('testReference', true);
    }
}
