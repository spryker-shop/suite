<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Glue\Products\RestApi;

use Codeception\Util\HttpCode;
use PyzTest\Glue\Products\ProductsApiTester;
use Spryker\Glue\ProductPricesRestApi\ProductPricesRestApiConfig;

/**
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Glue
 * @group Products
 * @group RestApi
 * @group ProductConcreteRestApiCest
 * Add your own group annotations below this line
 * @group EndToEnd
 */
class ProductConcreteRestApiCest
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
    public function requestExistingProductConcrete(ProductsApiTester $I): void
    {
        //act
        $I->sendGET(
            $I->formatUrl(
                'concrete-products/{ProductConcreteSku}',
                [
                    'ProductConcreteSku' => $this->fixtures->getProductConcreteTransfer()->getSku(),
                ]
            )
        );

        //assert
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesOpenApiSchema();

        $I->amSure('Returned resource is of type concrete-products')
            ->whenI()
            ->seeResponseDataContainsSingleResourceOfType('concrete-products');

        $I->amSure('Returned resource has correct id')
            ->whenI()
            ->seeSingleResourceIdEqualTo($this->fixtures->getProductConcreteTransfer()->getSku());
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\Products\ProductsApiTester $I
     *
     * @return void
     */
    public function requestExistingProductConcreteWithPrices(ProductsApiTester $I): void
    {
        //act
        $I->sendGET(
            $I->formatUrl(
                'concrete-products/{ProductConcreteSku}?include={ConcreteProducePrices}',
                [
                    'ProductConcreteSku' => $this->fixtures->getProductConcreteTransfer()->getSku(),
                    'ConcreteProducePrices' => ProductPricesRestApiConfig::RESOURCE_CONCRETE_PRODUCT_PRICES,
                ]
            )
        );

        //assert
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesOpenApiSchema();

        $I->amSure('Returned resource is of type concrete-products')
            ->whenI()
            ->seeSingleResourceHasRelationshipByTypeAndId(
                ProductPricesRestApiConfig::RESOURCE_CONCRETE_PRODUCT_PRICES,
                $this->fixtures->getProductConcreteTransfer()->getSku()
            );

        $I->amSure('Returned resource has correct id')
            ->whenI()
            ->seeIncludesContainsResourceByTypeAndId(
                ProductPricesRestApiConfig::RESOURCE_CONCRETE_PRODUCT_PRICES,
                $this->fixtures->getProductConcreteTransfer()->getSku()
            );
    }
}
