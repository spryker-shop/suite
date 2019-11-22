<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Glue\RelatedProducts\RestApi;

use Codeception\Util\HttpCode;
use PyzTest\Glue\RelatedProducts\RelatedProductsApiTester;
use Spryker\Glue\ProductLabelsRestApi\ProductLabelsRestApiConfig;
use Spryker\Glue\ProductsRestApi\ProductsRestApiConfig;

/**
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Glue
 * @group RelatedProducts
 * @group RestApi
 * @group RelatedProductsRestApiCest
 * Add your own group annotations below this line
 * @group EndToEnd
 */
class RelatedProductsRestApiCest
{
    /**
     * @var \PyzTest\Glue\RelatedProducts\RestApi\RelatedProductsRestApiFixtures
     */
    protected $fixtures;

    /**
     * @param \PyzTest\Glue\RelatedProducts\RelatedProductsApiTester $I
     *
     * @return void
     */
    public function loadFixtures(RelatedProductsApiTester $I): void
    {
        $this->fixtures = $I->loadFixtures(RelatedProductsRestApiFixtures::class);
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\RelatedProducts\RelatedProductsApiTester $I
     *
     * @return void
     */
    public function requestRelatedProducts(RelatedProductsApiTester $I): void
    {
        // Arrange
        $productAbstractSku = $this->fixtures->getProductConcreteTransfer()->getAbstractSku();
        $url = $I->buildRelatedProductsUrl($this->fixtures->getProductConcreteTransferWithLabel()->getAbstractSku());

        // Act
        $I->sendGET($url);

        // Assert
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesOpenApiSchema();

        $I->amSure('Response data contains resource collection')
            ->whenI()
            ->seeResponseDataContainsResourceCollectionOfType(ProductsRestApiConfig::RESOURCE_ABSTRACT_PRODUCTS);

        $I->amSure('Resource collection has resource')
            ->whenI()
            ->seeResourceCollectionHasResourceWithId($productAbstractSku);

        $I->amSure('Resource has correct self-link')
            ->whenI()
            ->seeResourceByIdHasSelfLink($productAbstractSku, $I->buildProductAbstractUrl($productAbstractSku));
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\RelatedProducts\RelatedProductsApiTester $I
     *
     * @return void
     */
    public function requestRelatedProductsWithProductLabelsRelationship(RelatedProductsApiTester $I): void
    {
        // Arrange
        $productAbstractSku = $this->fixtures->getProductConcreteTransferWithLabel()->getAbstractSku();
        $idProductLabel = $this->fixtures->getProductLabelTransfer()->getIdProductLabel();
        $url = $I->buildRelatedProductsUrl(
            $this->fixtures->getProductConcreteTransfer()->getAbstractSku(),
            [
                ProductLabelsRestApiConfig::RESOURCE_PRODUCT_LABELS,
            ]
        );

        // Act
        $I->sendGET($url);

        // Assert
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesOpenApiSchema();

        $I->amSure('Resource has a relationship')
            ->whenI()
            ->seeResourceByIdHasRelationshipByTypeAndId(
                $productAbstractSku,
                ProductLabelsRestApiConfig::RESOURCE_PRODUCT_LABELS,
                $idProductLabel
            );

        $I->amSure('The returned resource has include')
            ->whenI()
            ->seeIncludesContainsResourceByTypeAndId(
                ProductLabelsRestApiConfig::RESOURCE_PRODUCT_LABELS,
                $idProductLabel
            );

        $I->amSure('The include has correct self-link')
            ->whenI()
            ->seeIncludedResourceByTypeAndIdHasSelfLink(
                ProductLabelsRestApiConfig::RESOURCE_PRODUCT_LABELS,
                $idProductLabel,
                $I->buildProductLabelUrl($idProductLabel)
            );
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\RelatedProducts\RelatedProductsApiTester $I
     *
     * @return void
     */
    public function requestRelatedProductsWithoutProductLabelRelationship(RelatedProductsApiTester $I): void
    {
        // Arrange
        $url = $I->buildRelatedProductsUrl(
            $this->fixtures->getProductConcreteTransferWithLabel()->getAbstractSku(),
            [
                ProductLabelsRestApiConfig::RESOURCE_PRODUCT_LABELS,
            ]
        );

        // Act
        $I->sendGET($url);

        // Assert
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesOpenApiSchema();

        $I->amSure('The returned resource does not have includes')
            ->whenI()
            ->dontSeeIncludesContainResourceOfType(ProductLabelsRestApiConfig::RESOURCE_PRODUCT_LABELS);
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\RelatedProducts\RelatedProductsApiTester $I
     *
     * @return void
     */
    public function requestRelatedProductsByNotExistingProductAbstractSku(RelatedProductsApiTester $I): void
    {
        // Act
        $I->sendGET($I->buildRelatedProductsUrl('NotExistingSku'));

        // Assert
        $I->seeResponseCodeIs(HttpCode::NOT_FOUND);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesOpenApiSchema();
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\RelatedProducts\RelatedProductsApiTester $I
     *
     * @return void
     */
    public function requestProductRelatedWithProductLabelsRelationshipByPost(RelatedProductsApiTester $I): void
    {
        // Arrange
        $url = $I->buildRelatedProductsUrl(
            $this->fixtures->getProductConcreteTransfer()->getAbstractSku(),
            [
                ProductLabelsRestApiConfig::RESOURCE_PRODUCT_LABELS,
            ]
        );

        // Act
        $I->sendPOST($url);

        // Assert
        $I->seeResponseCodeIs(HttpCode::NOT_FOUND);
        $I->seeResponseIsJson();
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\RelatedProducts\RelatedProductsApiTester $I
     *
     * @return void
     */
    public function requestExistingProductRelatedWithProductLabelRelationshipByPatch(RelatedProductsApiTester $I): void
    {
        // Arrange
        $url = $I->buildRelatedProductsUrl(
            $this->fixtures->getProductConcreteTransfer()->getAbstractSku(),
            [
                ProductLabelsRestApiConfig::RESOURCE_PRODUCT_LABELS,
            ]
        );

        // Act
        $I->sendPATCH($url);

        // Assert
        $I->seeResponseCodeIs(HttpCode::NOT_FOUND);
        $I->seeResponseIsJson();
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\RelatedProducts\RelatedProductsApiTester $I
     *
     * @return void
     */
    public function requestExistingProductRelatedWithProductLabelRelationshipByDelete(RelatedProductsApiTester $I): void
    {
        // Arrange
        $url = $I->buildRelatedProductsUrl(
            $this->fixtures->getProductConcreteTransfer()->getAbstractSku(),
            [
                ProductLabelsRestApiConfig::RESOURCE_PRODUCT_LABELS,
            ]
        );

        // Act
        $I->sendDELETE($url);

        // Assert
        $I->seeResponseCodeIs(HttpCode::NOT_FOUND);
        $I->seeResponseIsJson();
    }
}
