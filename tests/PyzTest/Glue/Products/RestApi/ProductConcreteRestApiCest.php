<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Glue\Products\RestApi;

use Codeception\Util\HttpCode;
use Pyz\Glue\ProductPricesRestApi\ProductPricesRestApiConfig;
use PyzTest\Glue\Products\ProductsApiTester;
use PyzTest\Glue\Products\RestApi\Fixtures\ProductsRestApiFixtures;
use Spryker\Glue\ProductLabelsRestApi\ProductLabelsRestApiConfig;
use Spryker\Glue\ProductsRestApi\ProductsRestApiConfig;

/**
 * Auto-generated group annotations
 *
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
     * @var \PyzTest\Glue\Products\RestApi\Fixtures\ProductsRestApiFixtures
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
    public function requestProductConcrete(ProductsApiTester $I): void
    {
        // Arrange
        $productConcreteSku = $this->fixtures->getProductConcreteTransfer()->getSku();
        $url = $I->buildProductConcreteUrl($productConcreteSku);

        // Act
        $I->sendGET($url);

        // Assert
        $I->assertResponse(HttpCode::OK);

        $I->amSureSeeResponseDataContainsSingleResourceOfType(ProductsRestApiConfig::RESOURCE_CONCRETE_PRODUCTS)
            ->whenI()
            ->seeResponseDataContainsSingleResourceOfType(ProductsRestApiConfig::RESOURCE_CONCRETE_PRODUCTS);

        $I->amSureSeeSingleResourceIdEqualTo($productConcreteSku)
            ->whenI()
            ->seeSingleResourceIdEqualTo($productConcreteSku);

        $I->amSureSeeSingleResourceHasSelfLink()
            ->whenI()
            ->seeSingleResourceHasSelfLink($url);
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\Products\ProductsApiTester $I
     *
     * @return void
     */
    public function requestProductConcreteWithProductPriceRelationship(ProductsApiTester $I): void
    {
        // Arrange
        $productConcreteSku = $this->fixtures->getProductConcreteTransfer()->getSku();
        $url = $I->buildProductConcreteUrl($productConcreteSku, [ProductPricesRestApiConfig::RESOURCE_CONCRETE_PRODUCT_PRICES]);

        //act
        $I->sendGET($url);

        //assert
        $I->assertResponse(HttpCode::OK);

        $I->amSureSeeSingleResourceHasRelationshipByTypeAndId(
            ProductPricesRestApiConfig::RESOURCE_CONCRETE_PRODUCT_PRICES,
            $productConcreteSku
        )
            ->whenI()
            ->seeSingleResourceHasRelationshipByTypeAndId(
                ProductPricesRestApiConfig::RESOURCE_CONCRETE_PRODUCT_PRICES,
                $productConcreteSku
            );

        $I->amSureSeeIncludesContainsResourceByTypeAndId(
            ProductPricesRestApiConfig::RESOURCE_CONCRETE_PRODUCT_PRICES,
            $productConcreteSku
        )
            ->whenI()
            ->seeIncludesContainsResourceByTypeAndId(
                ProductPricesRestApiConfig::RESOURCE_CONCRETE_PRODUCT_PRICES,
                $productConcreteSku
            );

        $I->amSureSeeIncludedResourceByTypeAndIdHasSelfLink(
            ProductPricesRestApiConfig::RESOURCE_CONCRETE_PRODUCT_PRICES,
            $productConcreteSku
        )
            ->whenI()
            ->seeIncludedResourceByTypeAndIdHasSelfLink(
                ProductPricesRestApiConfig::RESOURCE_CONCRETE_PRODUCT_PRICES,
                $productConcreteSku,
                $I->buildProductConcretePricesUrl($productConcreteSku)
            );
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\Products\ProductsApiTester $I
     *
     * @return void
     */
    public function requestProductConcreteWithProductLabelsRelationship(ProductsApiTester $I): void
    {
        // Arrange
        $idProductLabel = $this->fixtures->getProductLabelTransfer()->getIdProductLabel();
        $url = $I->buildProductConcreteUrl(
            $this->fixtures->getProductConcreteTransferWithLabel()->getSku(),
            [
                ProductLabelsRestApiConfig::RESOURCE_PRODUCT_LABELS,
            ]
        );

        // Act
        $I->sendGET($url);

        // Assert
        $I->assertResponse(HttpCode::OK);

        $I->amSureSeeSingleResourceHasRelationshipByTypeAndId(
            ProductLabelsRestApiConfig::RESOURCE_PRODUCT_LABELS,
            $idProductLabel
        )
            ->whenI()
            ->seeSingleResourceHasRelationshipByTypeAndId(
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
    public function requestProductConcreteWithoutProductLabelRelationship(ProductsApiTester $I): void
    {
        // Arrange
        $url = $I->buildProductConcreteUrl(
            $this->fixtures->getProductConcreteTransfer()->getSku(),
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
    public function requestProductConcreteByNotExistingProductConcreteSku(ProductsApiTester $I): void
    {
        // Act
        $I->sendGET($I->buildProductConcreteUrl('NotExistingSku'));

        // Assert
        $I->assertResponse(HttpCode::NOT_FOUND);
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\Products\ProductsApiTester $I
     *
     * @return void
     */
    public function requestProductConcreteWithProductLabelRelationshipByPost(ProductsApiTester $I): void
    {
        // Arrange
        $url = $I->buildProductConcreteUrl(
            $this->fixtures->getProductConcreteTransferWithLabel()->getSku(),
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
    public function requestProductConcreteWithProductLabelRelationshipByPatch(ProductsApiTester $I): void
    {
        // Arrange
        $url = $I->buildProductConcreteUrl(
            $this->fixtures->getProductConcreteTransferWithLabel()->getSku(),
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
}
