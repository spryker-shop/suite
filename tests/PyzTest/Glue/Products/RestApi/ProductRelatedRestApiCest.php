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
 * @group ProductRelatedRestApiCest
 * Add your own group annotations below this line
 * @group EndToEnd
 */
class ProductRelatedRestApiCest
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
    public function requestExistingProductRelated(ProductsApiTester $I): void
    {
        //act
        $I->sendGET(
            $I->formatUrl(
                'abstract-products/{ProductAbstractSku}/related-products',
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
            ->seeResponseDataContainsResourceCollectionOfType('abstract-products');

        $I->amSure('Returned resource has correct id')
            ->whenI()
            ->seeResourceCollectionHasResourceWithId($this->fixtures->getProductConcreteTransferWithLabel()->getAbstractSku());
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\Products\ProductsApiTester $I
     *
     * @return void
     */
    public function requestExistingProductRelatedWithProductLabelRelationship(ProductsApiTester $I): void
    {
        $productConcreteTransfer = $this->fixtures->getProductConcreteTransfer();
        $productConcreteTransferWithLabel = $this->fixtures->getProductConcreteTransferWithLabel();
        $productLabelTransfer = $this->fixtures->getProductLabelTransfer();
        //act
        $I->sendGET(
            $I->formatUrl(
                'abstract-products/{ProductAbstractSku}/related-products?include=product-labels/',
                [
                    'ProductAbstractSku' => $productConcreteTransfer->getAbstractSku(),
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
                $productConcreteTransferWithLabel->getAbstractSku(),
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
    public function requestExistingProductRelatedWithoutProductLabelRelationship(ProductsApiTester $I): void
    {
        $productConcreteTransfer = $this->fixtures->getProductConcreteTransferWithLabel();
        //act
        $I->sendGET(
            $I->formatUrl(
                'abstract-products/{ProductAbstractSku}/related-products?include=product-labels',
                [
                    'ProductAbstractSku' => $productConcreteTransfer->getAbstractSku(),
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
    public function requestNotExistingProductRelatedWithProductLabelRelationship(ProductsApiTester $I): void
    {
        //act
        $I->sendGET(
            $I->formatUrl(
                'abstract-products/{ProductAbstractSku}/related-products?include=product-labels',
                [
                    'ProductAbstractSku' => 'NotExistingSku',
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
    public function requestExistingProductRelatedWithProductLabelRelationshipByPost(ProductsApiTester $I): void
    {
        $productConcreteTransfer = $this->fixtures->getProductConcreteTransferWithLabel();
        //act
        $I->sendPOST(
            $I->formatUrl(
                'abstract-products/{ProductAbstractSku}?include=product-labels',
                [
                    'ProductAbstractSku' => $productConcreteTransfer->getAbstractSku(),
                ]
            )
        );

        //assert
        $I->seeResponseCodeIs(HttpCode::NOT_FOUND);
        $I->seeResponseIsJson();
    }
}
