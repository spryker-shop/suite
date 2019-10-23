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
    public function requestAbstractAlternativeProducts(ProductsApiTester $I): void
    {
        // Arrange
        $productAbstractSku = $this->fixtures->getProductConcreteTransfer()->getAbstractSku();
        $url = $I->buildAbstractAlternativeProductsUrl(
            $this->fixtures->getProductConcreteTransferWithLabel()->getSku()
        );

        // Act
        $I->sendGET($url);

        // Assert
        $I->assertResponse(HttpCode::OK);

        $I->amSureSeeResponseDataContainsResourceCollectionOfType(ProductsRestApiConfig::RESOURCE_ABSTRACT_PRODUCTS)
            ->whenI()
            ->seeResponseDataContainsResourceCollectionOfType(ProductsRestApiConfig::RESOURCE_ABSTRACT_PRODUCTS);

        $I->amSureSeeResourceCollectionHasResourceWithId($productAbstractSku)
            ->whenI()
            ->seeResourceCollectionHasResourceWithId($productAbstractSku);

        $I->amSureSeeResourceByIdHasSelfLink($productAbstractSku)
            ->whenI()
            ->seeResourceByIdHasSelfLink($productAbstractSku, $I->buildProductAbstractUrl($productAbstractSku));
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\Products\ProductsApiTester $I
     *
     * @return void
     */
    public function requestAbstractAlternativeProductsWithProductLabelsRelationship(ProductsApiTester $I): void
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
        $I->assertResponse(HttpCode::OK);

        $I->amSureSeeResourceByIdHasRelationshipByTypeAndId(
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

        $I->amSureSeeIncludesContainsResourceByTypeAndId(
            ProductLabelsRestApiConfig::RESOURCE_PRODUCT_LABELS,
            $idProductLabel
        )
            ->whenI()
            ->seeIncludesContainsResourceByTypeAndId(
                ProductLabelsRestApiConfig::RESOURCE_PRODUCT_LABELS,
                $idProductLabel
            );

        $I->amSureSeeIncludedResourceByTypeAndIdHasSelfLink(
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
    public function requestAbstractAlternativeProductsWithoutProductLabelsRelationship(ProductsApiTester $I): void
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
        $I->assertResponse(HttpCode::OK);

        $I->amSureDontSeeIncludesContainResourceOfType(ProductLabelsRestApiConfig::RESOURCE_PRODUCT_LABELS)
            ->whenI()
            ->dontSeeIncludesContainResourceOfType(ProductLabelsRestApiConfig::RESOURCE_PRODUCT_LABELS);
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
        // Act
        $I->sendGET($I->buildAbstractAlternativeProductsUrl('NotExistingSku'));

        // Assert
        $I->assertResponse(HttpCode::NOT_FOUND);
        $I->seeResponseIsJson();
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\Products\ProductsApiTester $I
     *
     * @return void
     */
    public function requestAbstractAlternativeProductsWithProductLabelsRelationshipByPost(ProductsApiTester $I): void
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
     * @param \PyzTest\Glue\Products\ProductsApiTester $I
     *
     * @return void
     */
    public function requestAbstractAlternativeProductsWithProductLabelsRelationshipByPatch(ProductsApiTester $I): void
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
     * @param \PyzTest\Glue\Products\ProductsApiTester $I
     *
     * @return void
     */
    public function requestAbstractAlternativeProductsWithProductLabelsRelationshipByDelete(ProductsApiTester $I): void
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
