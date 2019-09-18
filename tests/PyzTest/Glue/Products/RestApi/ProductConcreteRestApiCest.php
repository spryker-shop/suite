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
    public function requestExistingProductConcreteWithProductLabelRelationship(ProductsApiTester $I): void
    {
        $productConcreteTransfer = $this->fixtures->getProductConcreteTransferWithLabel();
        $productLabelTransfer = $this->fixtures->getProductLabelTransfer();
        //act
        $I->sendGET(
            $I->formatUrl(
                'concrete-products/{ProductConcreteSku}?include=product-labels',
                [
                    'ProductConcreteSku' => $productConcreteTransfer->getSku(),
                ]
            )
        );

        //assert
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesOpenApiSchema();

        $I->amSure('Returned resource has include of type product-labels')
            ->whenI()
            ->seeSingleResourceHasRelationshipByTypeAndId(
                'product-labels',
                $productLabelTransfer->getIdProductLabel()
            );

        $I->amSure('Returned resource has include of type product-labels')
            ->whenI()
            ->seeIncludesContainsResourceByTypeAndId(
                'product-labels',
                $productLabelTransfer->getIdProductLabel()
            );
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\Products\ProductsApiTester $I
     *
     * @return void
     */
    public function requestExistingProductConcreteWithoutProductLabelRelationship(ProductsApiTester $I): void
    {
        $productConcreteTransfer = $this->fixtures->getProductConcreteTransfer();
        //act
        $I->sendGET(
            $I->formatUrl(
                'concrete-products/{ProductConcreteSku}?include=product-labels',
                [
                    'ProductConcreteSku' => $productConcreteTransfer->getSku(),
                ]
            )
        );

        //assert
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesOpenApiSchema();
        $I->dontSeeResponseMatchesJsonPath('$.included[*]');
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\Products\ProductsApiTester $I
     *
     * @return void
     */
    public function requestNotExistingProductConcreteWithProductLabelRelationship(ProductsApiTester $I): void
    {
        //act
        $I->sendGET(
            $I->formatUrl(
                'concrete-products/{ProductConcreteSku}?include=product-labels',
                [
                    'ProductConcreteSku' => 'NotExistingSku',
                ]
            )
        );

        //assert
        $I->seeResponseCodeIs(HttpCode::NOT_FOUND);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesOpenApiSchema();
        $I->dontSeeResponseMatchesJsonPath('$.data[*]');
        $I->dontSeeResponseMatchesJsonPath('$.included[*]');
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\Products\ProductsApiTester $I
     *
     * @return void
     */
    public function requestExistingProductConcreteWithProductLabelRelationshipByPost(ProductsApiTester $I): void
    {
        $productConcreteTransfer = $this->fixtures->getProductConcreteTransferWithLabel();
        //act
        $I->sendPost(
            $I->formatUrl(
                'concrete-products/{ProductConcreteSku}?include=product-labels',
                [
                    'ProductConcreteSku' => $productConcreteTransfer->getSku(),
                ]
            )
        );

        //assert
        $I->seeResponseCodeIs(HttpCode::NOT_FOUND);
        $I->seeResponseIsJson();
    }
}
