<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Glue\Products\RestApi;

use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\PriceTypeTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use PyzTest\Glue\PriceProducts\PriceProductsApiTester;
use PyzTest\Glue\Products\ProductsApiTester;
use SprykerTest\Shared\Testify\Fixtures\FixturesBuilderInterface;
use SprykerTest\Shared\Testify\Fixtures\FixturesContainerInterface;

/**
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Glue
 * @group Products
 * @group RestApi
 * @group ProductAbstractRestFixtures
 * Add your own group annotations below this line
 * @group EndToEnd
 */
class ProductsRestApiFixtures implements FixturesBuilderInterface, FixturesContainerInterface
{
    /**
     * @var \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    protected $productConcreteTransfer;

    /**
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function getProductConcreteTransfer(): ProductConcreteTransfer
    {
        return $this->productConcreteTransfer;
    }

    /**
     * @param \PyzTest\Glue\Products\ProductsApiTester $I
     *
     * @return \SprykerTest\Shared\Testify\Fixtures\FixturesContainerInterface
     */
    public function buildFixtures(ProductsApiTester $I): FixturesContainerInterface
    {
        $this->createProductConcrete($I);
        $this->createPriceProduct($I);

        return $this;
    }

    /**
     * @param \PyzTest\Glue\Products\ProductsApiTester $I
     *
     * @return void
     */
    protected function createProductConcrete(ProductsApiTester $I): void
    {
        $this->productConcreteTransfer = $I->haveFullProduct();
    }

    /**
     * @param \PyzTest\Glue\PriceProducts\PriceProductsApiTester $I
     *
     * @return void
     */
    protected function createPriceProduct(PriceProductsApiTester $I): void
    {
        $priceTypeTransfer = $I->havePriceType([PriceTypeTransfer::NAME => 'DEFAULT']);
        $currencyTransfer = $I->getLocator()->currency()->facade()->getDefaultCurrencyForCurrentStore();
        $I->havePriceProduct([
            PriceProductTransfer::ID_PRODUCT => $this->productConcreteTransfer->getIdProductConcrete(),
            PriceProductTransfer::SKU_PRODUCT => $this->productConcreteTransfer->getSku(),
            PriceProductTransfer::ID_PRICE_PRODUCT => $this->productConcreteTransfer->getFkProductAbstract(),
            PriceProductTransfer::SKU_PRODUCT_ABSTRACT => $this->productConcreteTransfer->getAbstractSku(),
            PriceProductTransfer::PRICE_TYPE => $priceTypeTransfer,
            PriceProductTransfer::MONEY_VALUE => [
                MoneyValueTransfer::NET_AMOUNT => 100,
                MoneyValueTransfer::GROSS_AMOUNT => 100,
                MoneyValueTransfer::CURRENCY => $currencyTransfer,
            ],
        ]);
    }
}
