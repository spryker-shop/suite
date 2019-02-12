<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Glue\CatalogSearch\RestApi;

use Codeception\Util\HttpCode;
use PyzTest\Glue\CatalogSearch\CatalogSearchApiTester;

/**
 * Auto-generated group annotations
 * @group PyzTest
 * @group Glue
 * @group CatalogSearch
 * @group RestApi
 * @group CatalogSearchRestApiCest
 * Add your own group annotations below this line
 * @group EndToEnd
 */
class CatalogSearchRestApiCest
{
    /**
     * @param \PyzTest\Glue\CatalogSearch\CatalogSearchApiTester $I
     *
     * @return void
     */
    public function requestSearchCatalogResourceWhichContainsPriceObjectForTheAbstractProduct(CatalogSearchApiTester $I): void
    {
        $expectedDataType = 'catalog-search';
        $abstractProductIncludeType = 'abstract-products';
        $priceIncludeType = 'abstract-product-prices';
        $productSku = '209';
        $currencyCode = 'EUR';
        $currencyName = 'Euro';
        $currencySymbol = '€';

        $I->sendGET('catalog-search', ['q' => $productSku]);
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();

        $I->seeResponseMatchesOpenApiSchema();

        $I->amSure('type is correct & id is null')->whenI()->seeResponseJsonPathMatchesJsonType(
            [
                'id' => 'null',
                'type' => sprintf('string:equal(%s)', json_encode($expectedDataType)),
            ],
            '$.data[*]'
        );

        $I->amSure('requested product found')->whenI()->seeResponseJsonPathContains(
            [
                'abstractSku' => $productSku,
            ],
            '$.data[*].attributes.abstractProducts[*]'
        );

        $I->amSure('product contains currency data in abstract price')->whenI()->seeResponseJsonPathContains(
            [
                'code' => $currencyCode,
                'name' => $currencyName,
                'symbol' => $currencySymbol,
            ],
            '$.data[*].attributes.abstractProducts[*].prices[*].currency'
        );

        $I->amSure('relationship exists')->whenI()->seeResponseMatchesJsonPath(
            sprintf('$.data[*].relationships[%s]', json_encode($abstractProductIncludeType))
        );

        $I->amSure('included object exists')->whenI()->seeResponseJsonPathContains(
            [
                'id' => $productSku,
            ],
            sprintf('$.included[?(@.type == %s)]', json_encode($priceIncludeType))
        );

        $I->amSure('included object contains currency data')->whenI()->seeResponseJsonPathContains(
            [
                'code' => $currencyCode,
                'name' => $currencyName,
                'symbol' => $currencySymbol,
            ],
            sprintf('$.included[?(@.type == %s)]..currency', json_encode($priceIncludeType))
        );
    }
}
