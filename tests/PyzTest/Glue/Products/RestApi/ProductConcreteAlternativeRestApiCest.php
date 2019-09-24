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
 * @group ProductConcreteAlternativeRestApiCest
 * Add your own group annotations below this line
 * @group EndToEnd
 */
class ProductConcreteAlternativeRestApiCest
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
    public function requestExistingConcreteAlternativeProduct(ProductsApiTester $I): void
    {
        //act
        $I->sendGET(
            $I->formatUrl(
                'concrete-products/{ProductConcreteSku}/concrete-alternative-products',
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
            ->seeResponseDataContainsResourceCollectionOfType('concrete-products');

        $I->amSure('Returned resource has correct id')
            ->whenI()
            ->seeResourceCollectionHasResourceWithId($this->fixtures->getProductConcreteTransferWithLabel()->getSku());
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\Products\ProductsApiTester $I
     *
     * @return void
     */
    public function requestExistingConcreteAlternativeProductWithProductLabelRelationship(ProductsApiTester $I): void
    {
        $productConcreteTransfer = $this->fixtures->getProductConcreteTransfer();
        $productConcreteTransferWithLabel = $this->fixtures->getProductConcreteTransferWithLabel();
        $productLabelTransfer = $this->fixtures->getProductLabelTransfer();
        //act
        $I->sendGET(
            $I->formatUrl(
                'concrete-products/{ProductConcreteSku}/concrete-alternative-products?include=product-labels',
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
            ->seeResourceByIdHasRelationshipByTypeAndId(
                $productConcreteTransferWithLabel->getSku(),
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
    public function requestExistingConcreteAlternativeProductWithoutProductLabelRelationship(ProductsApiTester $I): void
    {
        $productConcreteTransfer = $this->fixtures->getProductConcreteTransferWithLabel();
        //act
        $I->sendGET(
            $I->formatUrl(
                'concrete-products/{ProductConcreteSku}/concrete-alternative-products?include=product-labels',
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
    public function requestNotExistingConcreteAlternativeProductWithProductLabelRelationship(ProductsApiTester $I): void
    {
        //act
        $I->sendGET(
            $I->formatUrl(
                'concrete-products/{ProductConcreteSku}/concrete-alternative-products?include=product-labels',
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
    public function requestExistingConcreteAlternativeProductWithProductLabelRelationshipByPost(ProductsApiTester $I): void
    {
        $productConcreteTransfer = $this->fixtures->getProductConcreteTransferWithLabel();
        //act
        $I->sendPOST(
            $I->formatUrl(
                'concrete-products/{ProductConcreteSku}/concrete-alternative-products?include=product-labels',
                [
                    'ProductConcreteSku' => $productConcreteTransfer->getAbstractSku(),
                ]
            )
        );

        //assert
        $I->seeResponseCodeIs(HttpCode::NOT_FOUND);
        $I->seeResponseIsJson();
    }
}
