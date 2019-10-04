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
use Spryker\Glue\RelatedProductsRestApi\RelatedProductsRestApiConfig;

/**
 * Auto-generated group annotations
 *
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
        // Act
        $I->sendGET(
            $I->formatUrl(
                '{resourceAbstractProducts}/{productAbstractSku}/{resourceRelatedProducts}',
                [
                    'resourceAbstractProducts' => ProductsRestApiConfig::RESOURCE_ABSTRACT_PRODUCTS,
                    'resourceRelatedProducts' => RelatedProductsRestApiConfig::CONTROLLER_RELATED_PRODUCTS,
                    'productAbstractSku' => $this->fixtures->getProductConcreteTransfer()->getAbstractSku(),
                ]
            )
        );

        // Assert
        $I->assertResponse(HttpCode::OK);

        $I->amSure('Returned resource is of type abstract-products')
            ->whenI()
            ->seeResponseDataContainsResourceCollectionOfType(ProductsRestApiConfig::RESOURCE_ABSTRACT_PRODUCTS);

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
        // Arrange
        $productConcreteTransfer = $this->fixtures->getProductConcreteTransfer();
        $productConcreteTransferWithLabel = $this->fixtures->getProductConcreteTransferWithLabel();
        $productLabelTransfer = $this->fixtures->getProductLabelTransfer();

        // Act
        $I->sendGET(
            $I->formatUrl(
                '{resourceAbstractProducts}/{productAbstractSku}/{resourceRelatedProducts}?include={relationshipProductLabels}',
                [
                    'resourceAbstractProducts' => ProductsRestApiConfig::RESOURCE_ABSTRACT_PRODUCTS,
                    'resourceRelatedProducts' => RelatedProductsRestApiConfig::CONTROLLER_RELATED_PRODUCTS,
                    'relationshipProductLabels' => ProductLabelsRestApiConfig::RESOURCE_PRODUCT_LABELS,
                    'productAbstractSku' => $productConcreteTransfer->getAbstractSku(),
                ]
            )
        );

        // Assert
        $I->assertResponse(HttpCode::OK);

        $I->amSure('Returned resource has relation of type product-labels')
            ->whenI()
            ->seeResourceByIdHasRelationshipByTypeAndId(
                $productConcreteTransferWithLabel->getAbstractSku(),
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
    public function requestExistingProductRelatedWithoutProductLabelRelationship(ProductsApiTester $I): void
    {
        // Arrange
        $productConcreteTransfer = $this->fixtures->getProductConcreteTransferWithLabel();

        // Act
        $I->sendGET(
            $I->formatUrl(
                '{resourceAbstractProducts}/{productAbstractSku}/{resourceRelatedProducts}?include={relationshipProductLabels}',
                [
                    'resourceAbstractProducts' => ProductsRestApiConfig::RESOURCE_ABSTRACT_PRODUCTS,
                    'resourceRelatedProducts' => RelatedProductsRestApiConfig::CONTROLLER_RELATED_PRODUCTS,
                    'relationshipProductLabels' => ProductLabelsRestApiConfig::RESOURCE_PRODUCT_LABELS,
                    'productAbstractSku' => $productConcreteTransfer->getAbstractSku(),
                ]
            )
        );

        // Assert
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
    public function requestNotExistingProductRelatedWithProductLabelRelationship(ProductsApiTester $I): void
    {
        // Act
        $I->sendGET(
            $I->formatUrl(
                '{resourceAbstractProducts}/{productAbstractSku}/{resourceRelatedProducts}?include={relationshipProductLabels}',
                [
                    'resourceAbstractProducts' => ProductsRestApiConfig::RESOURCE_ABSTRACT_PRODUCTS,
                    'resourceRelatedProducts' => RelatedProductsRestApiConfig::CONTROLLER_RELATED_PRODUCTS,
                    'relationshipProductLabels' => ProductLabelsRestApiConfig::RESOURCE_PRODUCT_LABELS,
                    'productAbstractSku' => 'NotExistingSku',
                ]
            )
        );

        // Assert
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
    public function requestExistingProductRelatedWithProductLabelRelationshipByPost(ProductsApiTester $I): void
    {
        // Arrange
        $productConcreteTransfer = $this->fixtures->getProductConcreteTransferWithLabel();

        // Act
        $I->sendPOST(
            $I->formatUrl(
                '{resourceAbstractProducts}/{productAbstractSku}/{resourceRelatedProducts}?include={relationshipProductLabels}',
                [
                    'resourceAbstractProducts' => ProductsRestApiConfig::RESOURCE_ABSTRACT_PRODUCTS,
                    'resourceRelatedProducts' => RelatedProductsRestApiConfig::CONTROLLER_RELATED_PRODUCTS,
                    'relationshipProductLabels' => ProductLabelsRestApiConfig::RESOURCE_PRODUCT_LABELS,
                    'productAbstractSku' => $productConcreteTransfer->getAbstractSku(),
                ]
            )
        );

        // Assert
        $I->seeResponseCodeIs(HttpCode::NOT_FOUND);
        $I->seeResponseIsJson();
    }
}
