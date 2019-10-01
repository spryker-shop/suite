<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Glue\Products\RestApi;

use Codeception\Util\HttpCode;
use PyzTest\Glue\Products\ProductsApiTester;
use Spryker\Glue\ProductLabelsRestApi\ProductLabelsRestApiConfig;
use Spryker\Glue\ProductsRestApi\ProductsRestApiConfig;

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

        //Act
        $I->sendGET(
            $I->formatUrl(
                '{resourceConcreteProducts}/{productConcreteSku}?include={relationshipProductLabels}',
                [
                    'resourceConcreteProducts' => ProductsRestApiConfig::RESOURCE_CONCRETE_PRODUCTS,
                    'relationshipProductLabels' => ProductLabelsRestApiConfig::RESOURCE_PRODUCT_LABELS,
                    'productConcreteSku' => $productConcreteTransfer->getSku(),
                ]
            )
        );

        //Assert
        $I->assertResponse(HttpCode::OK);

        $I->amSure('Returned resource has relation of type product-labels')
            ->whenI()
            ->seeSingleResourceHasRelationshipByTypeAndId(
                ProductLabelsRestApiConfig::RESOURCE_PRODUCT_LABELS,
                $productLabelTransfer->getIdProductLabel()
            );

        $I->amSure('Returned resource has include of type product-labels')
            ->whenI()
            ->seeIncludesContainsResourceByTypeAndId(
                ProductLabelsRestApiConfig::RESOURCE_PRODUCT_LABELS,
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

        //Act
        $I->sendGET(
            $I->formatUrl(
                '{resourceConcreteProducts}/{productConcreteSku}?include={relationshipProductLabels}',
                [
                    'resourceConcreteProducts' => ProductsRestApiConfig::RESOURCE_CONCRETE_PRODUCTS,
                    'relationshipProductLabels' => ProductLabelsRestApiConfig::RESOURCE_PRODUCT_LABELS,
                    'productConcreteSku' => $productConcreteTransfer->getSku(),
                ]
            )
        );

        //Assert
        $I->assertResponse(HttpCode::OK);
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
        //Act
        $I->sendGET(
            $I->formatUrl(
                '{resourceConcreteProducts}/{productConcreteSku}?include={relationshipProductLabels}',
                [
                    'resourceConcreteProducts' => ProductsRestApiConfig::RESOURCE_CONCRETE_PRODUCTS,
                    'relationshipProductLabels' => ProductLabelsRestApiConfig::RESOURCE_PRODUCT_LABELS,
                    'productConcreteSku' => 'NotExistingSku',
                ]
            )
        );

        //Assert
        $I->assertResponse(HttpCode::NOT_FOUND);
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

        //Act
        $I->sendPost(
            $I->formatUrl(
                '{resourceConcreteProducts}/{productConcreteSku}?include={relationshipProductLabels}',
                [
                    'resourceConcreteProducts' => ProductsRestApiConfig::RESOURCE_CONCRETE_PRODUCTS,
                    'relationshipProductLabels' => ProductLabelsRestApiConfig::RESOURCE_PRODUCT_LABELS,
                    'productConcreteSku' => $productConcreteTransfer->getSku(),
                ]
            )
        );

        //Assert
        $I->seeResponseCodeIs(HttpCode::NOT_FOUND);
        $I->seeResponseIsJson();
    }
}
