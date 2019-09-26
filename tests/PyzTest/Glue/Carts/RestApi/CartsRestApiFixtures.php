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
use Generated\Shared\Transfer\StoreTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use PyzTest\Glue\Carts\CartsApiTester;
use SprykerTest\Shared\Testify\Fixtures\FixturesBuilderInterface;
use SprykerTest\Shared\Testify\Fixtures\FixturesContainerInterface;

/**
 * Auto-generated group annotations
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
    protected const TEST_USERNAME = 'test username';
    protected const TEST_PASSWORD = 'test password';
    protected const ANONYMOUS_CUSTOMER_REFERENCE = 'anonymous:666';

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
    protected $quoteTransfer;

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
    public function getQuoteTransfer(): QuoteTransfer
    {
        return $this->quoteTransfer;
    }

    /**
     * @param \PyzTest\Glue\Carts\CartsApiTester $I
     *
     * @return \SprykerTest\Shared\Testify\Fixtures\FixturesContainerInterface
     */
    public function buildFixtures(CartsApiTester $I): FixturesContainerInterface
    {
        $this->createProductConcrete1($I);
        $this->createProductConcrete2($I);
        $this->createCustomer($I);
        $this->createQuote($I);

        return $this;
    }

    /**
     * @return string
     */
    public function getTestPassword(): string
    {
        return static::TEST_PASSWORD;
    }

    /**
     * @param \PyzTest\Glue\Carts\CartsApiTester $I
     *
     * @return void
     */
    protected function createProductConcrete1(CartsApiTester $I): void
    {
        $this->productConcreteTransfer1 = $I->haveFullProduct();
        $this->createProductRelatedData($I, $this->productConcreteTransfer1);
    }

    /**
     * @param \PyzTest\Glue\Carts\CartsApiTester $I
     *
     * @return void
     */
    protected function createProductConcrete2(CartsApiTester $I): void
    {
        $this->productConcreteTransfer2 = $I->haveFullProduct();
        $this->createProductRelatedData($I, $this->productConcreteTransfer2);
    }

    /**
     * @param \PyzTest\Glue\Carts\CartsApiTester $I
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return void
     */
    protected function createProductRelatedData(CartsApiTester $I, ProductConcreteTransfer $productConcreteTransfer): void
    {
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
     * @return void
     */
    protected function createQuote(CartsApiTester $I): void
    {
        $totalsTransfer = new TotalsTransfer();
        $totalsTransfer->setPriceToPay(random_int(1000, 10000));

        $this->quoteTransfer = $I->havePersistentQuote([
            QuoteTransfer::CUSTOMER => (new CustomerTransfer())->setCustomerReference(static::ANONYMOUS_CUSTOMER_REFERENCE),
            QuoteTransfer::TOTALS => $totalsTransfer,
            QuoteTransfer::ITEMS => [
                [
                    ItemTransfer::SKU => $this->productConcreteTransfer1->getSku(),
                    ItemTransfer::GROUP_KEY => $this->productConcreteTransfer1->getSku(),
                    ItemTransfer::ABSTRACT_SKU => $this->productConcreteTransfer1->getAbstractSku(),
                    ItemTransfer::ID => $this->productConcreteTransfer1->getIdProductConcrete(),
                    ItemTransfer::UNIT_PRICE => random_int(100, 1000),
                    ItemTransfer::QUANTITY => 5,
                ],
            ],
            QuoteTransfer::STORE => [StoreTransfer::NAME => 'DE'],
        ]);
    }
}
