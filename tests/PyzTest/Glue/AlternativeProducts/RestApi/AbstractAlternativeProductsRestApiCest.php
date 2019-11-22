<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Glue\AlternativeProducts\RestApi;

use Codeception\Util\HttpCode;
use PyzTest\Glue\AlternativeProducts\AlternativeProductsRestApiTester;
use Spryker\Glue\ProductLabelsRestApi\ProductLabelsRestApiConfig;
use Spryker\Glue\ProductsRestApi\ProductsRestApiConfig;

/**
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Glue
 * @group AlternativeProducts
 * @group RestApi
 * @group AbstractAlternativeProductsRestApiCest
 * Add your own group annotations below this line
 * @group EndToEnd
 */
class AbstractAlternativeProductsRestApiCest
{
    /**
     * @var \PyzTest\Glue\AlternativeProducts\RestApi\AbstractAlternativeProductsRestApiFixtures
     */
    protected $fixtures;

    /**
     * @param \PyzTest\Glue\AlternativeProducts\AlternativeProductsRestApiTester $I
     *
     * @return void
     */
    public function loadFixtures(AlternativeProductsRestApiTester $I): void
    {
        $this->fixtures = $I->loadFixtures(AbstractAlternativeProductsRestApiFixtures::class);
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\AlternativeProducts\AlternativeProductsRestApiTester $I
     *
     * @return void
     */
    public function requestAbstractAlternativeProducts(AlternativeProductsRestApiTester $I): void
    {
        // Arrange
        $productAbstractSku = $this->fixtures->getProductConcreteTransfer()->getAbstractSku();
        $url = $I->buildAbstractAlternativeProductsUrl(
            $this->fixtures->getProductConcreteTransferWithLabel()->getSku()
        );

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
     * @param \PyzTest\Glue\AlternativeProducts\AlternativeProductsRestApiTester $I
     *
     * @return void
     */
    public function requestAbstractAlternativeProductsWithProductLabelsRelationship(AlternativeProductsRestApiTester $I): void
    {
        // Arrange
        $productAbstractSku = $this->fixtures->getProductConcreteTransferWithLabel()->getAbstractSku();
        $idProductLabel = $this->fixtures->getProductLabelTransfer()->getIdProductLabel();
        $url = $I->buildAbstractAlternativeProductsUrl(
            $this->fixtures->getProductConcreteTransfer()->getSku(),
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
     * @param \PyzTest\Glue\AlternativeProducts\AlternativeProductsRestApiTester $I
     *
     * @return void
     */
    public function requestAbstractAlternativeProductsWithoutProductLabelsRelationship(AlternativeProductsRestApiTester $I): void
    {
        // Arrange
        $url = $I->buildAbstractAlternativeProductsUrl(
            $this->fixtures->getProductConcreteTransferWithLabel()->getSku(),
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
     * @param \PyzTest\Glue\AlternativeProducts\AlternativeProductsRestApiTester $I
     *
     * @return void
     */
    public function requestAbstractAlternativeProductsByNotExistingProductConcreteSku(AlternativeProductsRestApiTester $I): void
    {
        // Act
        $I->sendGET($I->buildAbstractAlternativeProductsUrl('NotExistingSku'));

        // Assert
        $I->seeResponseCodeIs(HttpCode::NOT_FOUND);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesOpenApiSchema();
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\AlternativeProducts\AlternativeProductsRestApiTester $I
     *
     * @return void
     */
    public function requestAbstractAlternativeProductsWithProductLabelsRelationshipByPost(AlternativeProductsRestApiTester $I): void
    {
        // Arrange
        $url = $I->buildAbstractAlternativeProductsUrl(
            $this->fixtures->getProductConcreteTransfer()->getSku(),
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
     * @param \PyzTest\Glue\AlternativeProducts\AlternativeProductsRestApiTester $I
     *
     * @return void
     */
    public function requestAbstractAlternativeProductsWithProductLabelsRelationshipByPatch(AlternativeProductsRestApiTester $I): void
    {
        // Arrange
        $url = $I->buildAbstractAlternativeProductsUrl(
            $this->fixtures->getProductConcreteTransfer()->getSku(),
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
     * @param \PyzTest\Glue\AlternativeProducts\AlternativeProductsRestApiTester $I
     *
     * @return void
     */
    public function requestAbstractAlternativeProductsWithProductLabelsRelationshipByDelete(AlternativeProductsRestApiTester $I): void
    {
        // Arrange
        $url = $I->buildAbstractAlternativeProductsUrl(
            $this->fixtures->getProductConcreteTransfer()->getSku(),
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
