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
     * @param \PyzTest\Glue\Products\ProductsApiTester $I
     *
     * @return void
     */
    public function requestTheNonExistedProductAbstract(ProductsApiTester $I): void
    {
        $I->sendGET('abstract-products/non-exist');
        $I->seeResponseCodeIs(HttpCode::NOT_FOUND);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesOpenApiSchema();
    }

    /**
     * @param \PyzTest\Glue\Products\ProductsApiTester $I
     *
     * @return void
     */
    public function requestProductAbstractWithoutId(ProductsApiTester $I): void
    {
        $I->sendGET('abstract-products');

        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesOpenApiSchema();
    }

    /**
     * @param \PyzTest\Glue\Products\ProductsApiTester $I
     *
     * @return void
     */
    public function requestExistingProductAbstract(ProductsApiTester $I): void
    {
//        properties:
//                abstract-product-image-sets:
//                    items:
//                        $ref: '#/components/schemas/RestRelationships'
//                abstract-product-availabilities:
//                    items:
//                        $ref: '#/components/schemas/RestRelationships'
//                abstract-product-prices:
//                    items:
//                        $ref: '#/components/schemas/RestRelationships'
//                category-nodes:
//                    items:
//                        $ref: '#/components/schemas/RestRelationships'
//                product-tax-sets:
//                    items:
//                        $ref: '#/components/schemas/RestRelationships'
//                product-labels:
//                    items:
//                        $ref: '#/components/schemas/RestRelationships'
    }
}
