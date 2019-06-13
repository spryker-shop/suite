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
 * @group ProductsRestApiCest
 * Add your own group annotations below this line
 * @group EndToEnd
 */
class ProductsRestApiCest
{
    /**
     * @param \PyzTest\Glue\Products\ProductsApiTester $I
     *
     * @return void
     */
    public function requestTheNonExistedConcreteProduct(ProductsApiTester $I): void
    {
        $I->sendGET('concrete-products/non-exist');
        $I->seeResponseCodeIs(HttpCode::NOT_FOUND);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesOpenApiSchema();
    }

    /**
     * @param \PyzTest\Glue\Products\ProductsApiTester $I
     *
     * @return void
     */
    public function requestTheConcreteProductWithExtraParamsEuroAndNetMode(ProductsApiTester $I): void
    {
        //arrange
        $expectedDataType = 'concrete-products';
        $productSku = '177_24867659';

        //act
        $I->sendGET($I->formatUrl(
            'concrete-products/{concreteProductId}',
            [
                'concreteProductId' => $productSku
            ]),
            [
                'include' => 'concrete-product-image-sets,concrete-product-availabilities,concrete-product-prices',
            ]
        );

        //assert
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesOpenApiSchema();

        $I->amSure('response type is correct')->whenI()->seeResponseJsonPathContains(
            [
                'type' => $expectedDataType,
            ],
            '$.data'
        );

        $I->amSure('id is equal to the sku from url')->whenI()->seeResponseJsonPathContains(
            [
                'id' => $productSku,
            ],
            '$.data'
        );

        $I->seeIncludedResourceByTypeAndIdHasRelationshipByTypeAndId();
    }
}
