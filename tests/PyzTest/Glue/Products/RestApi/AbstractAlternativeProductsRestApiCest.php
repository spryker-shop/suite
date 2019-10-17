<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Glue\Products\RestApi;

use Codeception\Util\HttpCode;
use PyzTest\Glue\Products\ProductsApiTester;
use PyzTest\Glue\Products\RestApi\Fixtures\AbstractAlternativeProductsRestApiFixtures;
use Spryker\Glue\ProductLabelsRestApi\ProductLabelsRestApiConfig;
use Spryker\Glue\ProductsRestApi\ProductsRestApiConfig;

/**
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Glue
 * @group Products
 * @group RestApi
 * @group AbstractAlternativeProductsRestApiCest
 * Add your own group annotations below this line
 * @group EndToEnd
 */
class AbstractAlternativeProductsRestApiCest
{
    protected const INCLUDE_RESOURCES = [
        ProductLabelsRestApiConfig::RESOURCE_PRODUCT_LABELS,
    ];

    /**
     * @var \PyzTest\Glue\Products\RestApi\Fixtures\AbstractAlternativeProductsRestApiFixtures
     */
    protected $fixtures;

    /**
     * @param \PyzTest\Glue\Products\ProductsApiTester $I
     *
     * @return void
     */
    public function loadFixtures(ProductsApiTester $I): void
    {
        $this->fixtures = $I->loadFixtures(AbstractAlternativeProductsRestApiFixtures::class);
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\Products\ProductsApiTester $I
     *
     * @return void
     */
    public function requestAbstractAlternativeProductsWithProductLabelRelationship(ProductsApiTester $I): void
    {
        // Arrange
        $productConcreteSku = $this->fixtures->getProductConcreteTransfer()->getSku();
        $productAbstractSku = $this->fixtures->getProductConcreteTransferWithLabel()->getAbstractSku();
        $idProductLabel = $this->fixtures->getProductLabelTransfer()->getIdProductLabel();
        $url = $I->buildAbstractAlternativeProductsUrl($productConcreteSku, static::INCLUDE_RESOURCES);

        // Act
        $I->sendGET($url);

        // Assert
        $I->assertResponse(HttpCode::OK);

        $I->amSureResponseDataContainsResourceCollectionOfType(ProductsRestApiConfig::RESOURCE_ABSTRACT_PRODUCTS)
            ->whenI()
            ->seeResponseDataContainsResourceCollectionOfType(ProductsRestApiConfig::RESOURCE_ABSTRACT_PRODUCTS);

        $I->amSureResourceCollectionHasResourceWithId($productAbstractSku)
            ->whenI()
            ->seeResourceCollectionHasResourceWithId($productAbstractSku);

        $I->amSureResourceByIdHasSelfLink($productAbstractSku)
            ->whenI()
            ->seeResourceByIdHasSelfLink($productAbstractSku, $I->buildProductAbstractUrl($productAbstractSku));

        $I->amSureResourceByIdHasRelationshipByTypeAndId(
            $productAbstractSku,
            ProductLabelsRestApiConfig::RESOURCE_PRODUCT_LABELS,
            $idProductLabel
        )
            ->whenI()
            ->seeResourceByIdHasRelationshipByTypeAndId(
                $productAbstractSku,
                ProductLabelsRestApiConfig::RESOURCE_PRODUCT_LABELS,
                $idProductLabel
            );

        $I->amSureIncludesContainsResourceByTypeAndId(
            ProductLabelsRestApiConfig::RESOURCE_PRODUCT_LABELS,
            $idProductLabel
        )
            ->whenI()
            ->seeIncludesContainsResourceByTypeAndId(
                ProductLabelsRestApiConfig::RESOURCE_PRODUCT_LABELS,
                $idProductLabel
            );

        $I->amSureIncludedResourceByTypeAndIdHasSelfLink(
            ProductLabelsRestApiConfig::RESOURCE_PRODUCT_LABELS,
            $idProductLabel
        )
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
     * @param \PyzTest\Glue\Products\ProductsApiTester $I
     *
     * @return void
     */
    public function requestAbstractAlternativeProductsWithoutProductLabelRelationship(ProductsApiTester $I): void
    {
        // Arrange
        $productConcreteSku = $this->fixtures->getProductConcreteTransferWithLabel()->getSku();
        $url = $I->buildAbstractAlternativeProductsUrl($productConcreteSku, static::INCLUDE_RESOURCES);

        // Act
        $I->sendGET($url);

        // Assert
        $I->assertResponse(HttpCode::OK);

        $I->dontSeeResponseMatchesJsonPath(
            sprintf('$.included[?(@.type == %s$s)]', ProductLabelsRestApiConfig::RESOURCE_PRODUCT_LABELS)
        );
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\Products\ProductsApiTester $I
     *
     * @return void
     */
    public function requestAbstractAlternativeProductsByNotExistingProductConcreteSku(ProductsApiTester $I): void
    {
        // Arrange
        $url = $I->buildAbstractAlternativeProductsUrl('NotExistingSku');

        // Act
        $I->sendGET($url);

        // Assert
        $I->assertResponse(HttpCode::NOT_FOUND);
        $I->seeResponseDataContainsEmptyCollection();
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\Products\ProductsApiTester $I
     *
     * @return void
     */
    public function requestExistingAbstractAlternativeProductsWithProductLabelRelationshipByPost(ProductsApiTester $I): void
    {
        // Arrange
        $productConcreteSku = $this->fixtures->getProductConcreteTransfer()->getSku();
        $url = $I->buildAbstractAlternativeProductsUrl($productConcreteSku, static::INCLUDE_RESOURCES);

        // Act
        $I->sendPOST($url);

        // Assert
        $I->seeResponseCodeIs(HttpCode::NOT_FOUND);
        $I->seeResponseIsJson();
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\Products\ProductsApiTester $I
     *
     * @return void
     */
    public function requestExistingAbstractAlternativeProductsWithProductLabelRelationshipByPatch(ProductsApiTester $I): void
    {
        // Arrange
        $productConcreteSku = $this->fixtures->getProductConcreteTransfer()->getSku();
        $url = $I->buildAbstractAlternativeProductsUrl($productConcreteSku, static::INCLUDE_RESOURCES);

        // Act
        $I->sendPATCH($url);

        // Assert
        $I->seeResponseCodeIs(HttpCode::NOT_FOUND);
        $I->seeResponseIsJson();
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\Products\ProductsApiTester $I
     *
     * @return void
     */
    public function requestExistingAbstractAlternativeProductsWithProductLabelRelationshipByDelete(ProductsApiTester $I): void
    {
        // Arrange
        $productConcreteSku = $this->fixtures->getProductConcreteTransfer()->getSku();
        $url = $I->buildAbstractAlternativeProductsUrl($productConcreteSku, static::INCLUDE_RESOURCES);

        // Act
        $I->sendDELETE($url);

        // Assert
        $I->seeResponseCodeIs(HttpCode::NOT_FOUND);
        $I->seeResponseIsJson();
    }
}
