<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Glue\Products\RestApi;

use Codeception\Util\HttpCode;
use PyzTest\Glue\Products\ProductsApiTester;
use PyzTest\Glue\Products\RestApi\Fixtures\ConcreteAlternativeProductsRestApiFixtures;
use Spryker\Glue\ProductLabelsRestApi\ProductLabelsRestApiConfig;
use Spryker\Glue\ProductsRestApi\ProductsRestApiConfig;

/**
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Glue
 * @group Products
 * @group RestApi
 * @group ConcreteAlternativeProductsRestApiCest
 * Add your own group annotations below this line
 * @group EndToEnd
 */
class ConcreteAlternativeProductsRestApiCest
{
    /**
     * @var \PyzTest\Glue\Products\RestApi\Fixtures\ConcreteAlternativeProductsRestApiFixtures
     */
    protected $fixtures;

    /**
     * @param \PyzTest\Glue\Products\ProductsApiTester $I
     *
     * @return void
     */
    public function loadFixtures(ProductsApiTester $I): void
    {
        $this->fixtures = $I->loadFixtures(ConcreteAlternativeProductsRestApiFixtures::class);
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\Products\ProductsApiTester $I
     *
     * @return void
     */
    public function requestConcreteAlternativeProducts(ProductsApiTester $I): void
    {
        // Arrange
        $productConcreteSku = $this->fixtures->getProductConcreteTransferWithLabel()->getSku();
        $url = $I->buildConcreteAlternativeProductsUrl($this->fixtures->getProductConcreteTransfer()->getSku());

        // Act
        $I->sendGET($url);

        $I->assertResponse(HttpCode::OK);

        $I->amSureSeeResponseDataContainsResourceCollectionOfType(ProductsRestApiConfig::RESOURCE_CONCRETE_PRODUCTS)
            ->whenI()
            ->seeResponseDataContainsResourceCollectionOfType(ProductsRestApiConfig::RESOURCE_CONCRETE_PRODUCTS);

        $I->amSureSeeResourceCollectionHasResourceWithId($productConcreteSku)
            ->whenI()
            ->seeResourceCollectionHasResourceWithId($productConcreteSku);

        $I->amSureSeeResourceByIdHasSelfLink($productConcreteSku)
            ->whenI()
            ->seeResourceByIdHasSelfLink($productConcreteSku, $I->buildProductConcreteUrl($productConcreteSku));
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\Products\ProductsApiTester $I
     *
     * @return void
     */
    public function requestConcreteAlternativeProductsWithProductLabelsRelationship(ProductsApiTester $I): void
    {
        // Arrange
        $productConcreteSku = $this->fixtures->getProductConcreteTransferWithLabel()->getSku();
        $idProductLabel = $this->fixtures->getProductLabelTransfer()->getIdProductLabel();
        $url = $I->buildConcreteAlternativeProductsUrl(
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
            $productConcreteSku,
            ProductLabelsRestApiConfig::RESOURCE_PRODUCT_LABELS,
            $idProductLabel
        )
            ->whenI()
            ->seeResourceByIdHasRelationshipByTypeAndId(
                $productConcreteSku,
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
    public function requestConcreteAlternativeProductsWithoutProductLabelsRelationship(ProductsApiTester $I): void
    {
        // Arrange
        $url = $I->buildConcreteAlternativeProductsUrl(
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
    public function requestConcreteAlternativeProductsByNotExistingProductConcreteSku(ProductsApiTester $I): void
    {
        // Act
        $I->sendGET($I->buildConcreteAlternativeProductsUrl('NotExistingSku'));

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
    public function requestConcreteAlternativeProductsWithProductLabelsRelationshipByPost(ProductsApiTester $I): void
    {
        // Arrange
        $url = $I->buildConcreteAlternativeProductsUrl(
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
    public function requestConcreteAlternativeProductsWithProductLabelsRelationshipByPatch(ProductsApiTester $I): void
    {
        // Arrange
        $url = $I->buildConcreteAlternativeProductsUrl(
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
    public function requestConcreteAlternativeProductsWithProductLabelsRelationshipByDelete(ProductsApiTester $I): void
    {
        // Arrange
        $url = $I->buildConcreteAlternativeProductsUrl(
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
