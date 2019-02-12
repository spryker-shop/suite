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
        $expectedDataType = 'concrete-products';
        $priceIncludeType = 'concrete-product-prices';
        $productSku = '177_24867659';
        $currencyCode = 'EUR';
        $currencyName = 'Euro';
        $currencySymbol = '€';

        $I->sendGET($I->formatUrl('concrete-products/{concreteProductId}', ['concreteProductId' => $productSku]), [
            'currency' => 'EUR',
            'priceMode' => 'NET_MODE',
        ]);
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

        $I->amSure('particular included object exists')->whenI()->seeResponseMatchesJsonPath(
            sprintf('$.included[?(@.id == %s and @.type == %s)]', json_encode($productSku), json_encode($priceIncludeType))
        );

        $I->amSure('included object contains currency data')->whenI()->seeResponseJsonPathContains(
            [
                'code' => $currencyCode,
                'name' => $currencyName,
                'symbol' => $currencySymbol,
            ],
            sprintf('$.included[?(@.id == %s and @.type == %s)]..currency', json_encode($productSku), json_encode($priceIncludeType))
        );

        $I->amSure('net price is proper integer')->whenI()->seeResponseJsonPathMatchesJsonType(
            [
                'netAmount' => 'integer:equal(38252)',
            ],
            sprintf('$.included[?(@.id == %s and @.type == %s)]..prices[*]', json_encode($productSku), json_encode($priceIncludeType))
        );
    }
}
