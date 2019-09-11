<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Glue\Products\RestApi;

use Codeception\Util\HttpCode;
use PyzTest\Glue\Products\ProductsApiTester;

/**
 * Auto-generated group annotations
 * @group PyzTest
 * @group Glue
 * @group Products
 * @group RestApi
 * @group ProductAbstractRestApiCest
 * Add your own group annotations below this line
 * @group EndToEnd
 */
class ProductAbstractRestApiCest
{
    /**
     * @var \PyzTest\Glue\Products\RestApi\ProductsRestApiFixtures
     */
    protected $fixtures;

    /**
     * @param \PyzTest\Glue\Products\ProductsApiTester $I
     *
     * @return void
     */
    public function loadFixtures(ProductsApiTester $I): void
    {
        $this->fixtures = $I->loadFixtures(ProductsRestApiFixtures::class);
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\Products\ProductsApiTester $I
     *
     * @return void
     */
    public function requestTheNonExistedProductAbstract(ProductsApiTester $I): void
    {
        //act
        $I->sendGET('abstract-products/non-exist');

        //assert
        $I->seeResponseCodeIs(HttpCode::NOT_FOUND);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesOpenApiSchema();
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\Products\ProductsApiTester $I
     *
     * @return void
     */
    public function requestProductAbstractWithoutId(ProductsApiTester $I): void
    {
        //act
        $I->sendGET('abstract-products');

        //assert
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseIsJson();
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\Products\ProductsApiTester $I
     *
     * @return void
     */
    public function requestExistingProductAbstract(ProductsApiTester $I): void
    {
        //act
        $I->sendGET(
            $I->formatUrl(
                'abstract-products/{ProductAbstractSku}',
                [
                    'ProductAbstractSku' => $this->fixtures->getProductConcreteTransfer()->getAbstractSku(),
                ]
            )
        );

        //assert
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesOpenApiSchema();

        $I->amSure('Returned resource is of type abstract-products')
            ->whenI()
            ->seeResponseDataContainsSingleResourceOfType('abstract-products');
        $I->amSure('Returned resource has correct id')
            ->whenI()
            ->seeSingleResourceIdEqualTo($this->fixtures->getProductConcreteTransfer()->getAbstractSku());
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\Products\ProductsApiTester $I
     *
     * @return void
     */
    public function requestExistingProductAbstractWithProductConcreteRelationship(ProductsApiTester $I): void
    {
        //act
        $I->sendGET(
            $I->formatUrl(
                'abstract-products/{ProductAbstractSku}?include=concrete-products',
                [
                    'ProductAbstractSku' => $this->fixtures->getProductConcreteTransfer()->getAbstractSku(),
                ]
            )
        );

        //assert
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesOpenApiSchema();

        $I->amSure('Returned resource has include of type concrete-products')
            ->whenI()
            ->seeSingleResourceHasRelationshipByTypeAndId('concrete-products', $this->fixtures->getProductConcreteTransfer()->getSku());

        $I->amSure('Returned resource has include of type concrete-products')
            ->whenI()
            ->seeIncludesContainsResourceByTypeAndId('concrete-products', $this->fixtures->getProductConcreteTransfer()->getSku());
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\Products\ProductsApiTester $I
     *
     * @return void
     */
    public function requestProductAbstractHasUrlAttribute(ProductsApiTester $I): void
    {
        //act
        $I->sendGET(
            $I->formatUrl(
                'abstract-products/{ProductAbstractSku}',
                [
                    'ProductAbstractSku' => $this->fixtures->getProductConcreteTransfer()->getAbstractSku(),
                ]
            )
        );

        //assert
        $I->amSure('Returned resource contains `url` attribute')
            ->whenI()
            ->seeSingleResourceHasAttribute('url');
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\Products\ProductsApiTester $I
     *
     * @return void
     */
    public function requestExistingProductAbstractWithProductPriceRelationship(ProductsApiTester $I): void
    {
        //act
        $I->sendGET(
            $I->formatUrl(
                'abstract-products/{ProductAbstractSku}?include=abstract-product-prices',
                [
                    'ProductAbstractSku' => $this->fixtures->getProductConcreteTransfer()->getAbstractSku(),
                ]
            )
        );

        //assert
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesOpenApiSchema();

        $I->amSure('Returned resource has include of type abstract-product-prices')
            ->whenI()
            ->seeSingleResourceHasRelationshipByTypeAndId('abstract-product-prices', $this->fixtures->getProductConcreteTransfer()->getAbstractSku());

        $I->amSure('Returned resource has include of type abstract-product-prices')
            ->whenI()
            ->seeIncludesContainsResourceByTypeAndId('abstract-product-prices', $this->fixtures->getProductConcreteTransfer()->getAbstractSku());
    }
}
