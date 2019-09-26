<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Glue\Carts\RestApi;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use PyzTest\Glue\Carts\CartsApiTester;
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
    protected const TEST_USERNAME = 'test username';
    protected const TEST_PASSWORD = 'test password';
    protected const ANONYMOUS_CUSTOMER_REFERENCE = 'anonymous:666';

    /**
     * @var \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    protected $productConcreteTransfer;

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
    public function getProductConcreteTransfer(): ProductConcreteTransfer
    {
        return $this->productConcreteTransfer;
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
        $this->createProduct($I);
        $this->createCustomer($I);
        $this->createQuote($I);

        return $this;
    }

    /**
     * @param \PyzTest\Glue\Carts\CartsApiTester $I
     *
     * @return void
     */
    protected function createProduct(CartsApiTester $I): void
    {
        $this->productConcreteTransfer = $I->haveFullProduct();
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
                    ItemTransfer::SKU => $this->productConcreteTransfer->getSku(),
                    ItemTransfer::GROUP_KEY => $this->productConcreteTransfer->getSku(),
                    ItemTransfer::ABSTRACT_SKU => $this->productConcreteTransfer->getAbstractSku(),
                    ItemTransfer::ID => $this->productConcreteTransfer->getIdProductConcrete(),
                    ItemTransfer::UNIT_PRICE => random_int(100, 1000),
                    ItemTransfer::QUANTITY => 5,
                ],
            ],
            QuoteTransfer::STORE => [StoreTransfer::NAME => 'DE'],
        ]);
    }
}
