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
 * @group End2End
 */
class ProductAbstractRestApiCest
{
    /**
     * @var \PyzTest\Glue\Products\RestApi\ProductAbstractsRestApiFixtures
     */
    protected $fixtures;

    /**
     * @param \PyzTest\Glue\Products\ProductsApiTester $I
     *
     * @return void
     */
    public function loadFixtures(ProductsApiTester $I): void
    {
        $this->fixtures = $I->loadFixtures(ProductAbstractsRestApiFixtures::class);
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
                    'ProductAbstractSku' => $this->fixtures->getProductAbstractTransfer()->getSku()
                ]
            ),
            [
                'included' => 'abstract-product-image-sets,abstract-product-prices'
            ]
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
            ->seeSingleResourceIdEqualTo($this->fixtures->getProductAbstractTransfer()->getIdProductAbstract());
        $I->amSure('Returned resource has included relationship abstract-product-image-sets')
            ->whenI()
            ->seeSingleResourceHasRelationshipByTypeAndId(
                'abstract-product-image-sets',
                $this->fixtures->getProductImageSetTransfer()->getIdProductImageSet()
            );
        $I->amSure('Returned resource has included relationship abstract-product-prices')
            ->whenI()
            ->seeSingleResourceHasRelationshipByTypeAndId(
                'abstract-product-prices',
                $this->fixtures->getPriceProductTransfer()->getIdPriceProduct()
            );
    }
}
