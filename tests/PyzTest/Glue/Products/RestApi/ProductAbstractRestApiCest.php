<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Glue\Products\RestApi;

use Codeception\Util\HttpCode;
use Generated\Shared\Transfer\AbstractProductsRestAttributesTransfer;
use PyzTest\Glue\Products\ProductsApiTester;
use PyzTest\Glue\Products\RestApi\Fixtures\ProductsRestApiFixtures;
use Spryker\Glue\ProductLabelsRestApi\ProductLabelsRestApiConfig;
use Spryker\Glue\ProductPricesRestApi\ProductPricesRestApiConfig;
use Spryker\Glue\ProductsRestApi\ProductsRestApiConfig;

/**
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Glue
 * @group Products
 * @group RestApi
 * @group ProductAbstractRestApiCest
 * Add your own group annotations below this line
 * @group EndToEnd
 */
class ProductAbstractRestApiCest
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
    public function requestProductAbstractByNotExistingProductAbstractSku(ProductsApiTester $I): void
    {
        // Act
        $I->sendGET($I->buildProductAbstractUrl('NotExistingSku'));

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
    public function requestProductAbstractWithoutId(ProductsApiTester $I): void
    {
        // Act
        $I->sendGET($I->buildProductAbstractUrl(''));

        // Assert
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseIsJson();
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\Products\ProductsApiTester $I
     *
     * @return void
     */
    public function requestProductAbstract(ProductsApiTester $I): void
    {
        // Arrange
        $productAbstractSku = $this->fixtures->getProductConcreteTransfer()->getAbstractSku();
        $url = $I->buildProductAbstractUrl($productAbstractSku);

        // Act
        $I->sendGET($url);

        // Assert
        $I->assertResponse(HttpCode::OK);

        $I->amSureSeeResponseDataContainsSingleResourceOfType(ProductsRestApiConfig::RESOURCE_ABSTRACT_PRODUCTS)
            ->whenI()
            ->seeResponseDataContainsSingleResourceOfType(ProductsRestApiConfig::RESOURCE_ABSTRACT_PRODUCTS);

        $I->amSureSeeSingleResourceIdEqualTo($productAbstractSku)
            ->whenI()
            ->seeSingleResourceIdEqualTo($productAbstractSku);

        $I->amSureSeeSingleResourceHasSelfLink()
            ->whenI()
            ->seeSingleResourceHasSelfLink($url);

        $I->amSureSeeSingleResourceHasAttribute(AbstractProductsRestAttributesTransfer::URL)
            ->whenI()
            ->seeSingleResourceHasAttribute(AbstractProductsRestAttributesTransfer::URL);
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\Products\ProductsApiTester $I
     *
     * @return void
     */
    public function requestProductAbstractWithProductConcreteRelationship(ProductsApiTester $I): void
    {
        // Arrange
        $productConcreteTransfer = $this->fixtures->getProductConcreteTransferWithLabel();
        $productConcreteSku = $productConcreteTransfer->getSku();
        $url = $I->buildProductAbstractUrl(
            $productConcreteTransfer->getAbstractSku(),
            [
                ProductsRestApiConfig::RESOURCE_CONCRETE_PRODUCTS,
            ]
        );

        // Act
        $I->sendGET($url);

        // Assert
        $I->assertResponse(HttpCode::OK);

        $I->amSureSeeSingleResourceHasRelationshipByTypeAndId(
            ProductsRestApiConfig::RESOURCE_CONCRETE_PRODUCTS,
            $productConcreteSku
        )
            ->whenI()
            ->seeSingleResourceHasRelationshipByTypeAndId(
                ProductsRestApiConfig::RESOURCE_CONCRETE_PRODUCTS,
                $productConcreteSku
            );

        $I->amSureSeeIncludesContainsResourceByTypeAndId(
            ProductsRestApiConfig::RESOURCE_CONCRETE_PRODUCTS,
            $productConcreteSku
        )
            ->whenI()
            ->seeIncludesContainsResourceByTypeAndId(
                ProductsRestApiConfig::RESOURCE_CONCRETE_PRODUCTS,
                $productConcreteSku
            );

        $I->amSureSeeIncludedResourceByTypeAndIdHasSelfLink(
            ProductsRestApiConfig::RESOURCE_CONCRETE_PRODUCTS,
            $productConcreteSku
        )
            ->whenI()
            ->seeIncludedResourceByTypeAndIdHasSelfLink(
                ProductsRestApiConfig::RESOURCE_CONCRETE_PRODUCTS,
                $productConcreteSku,
                $I->buildProductConcreteUrl($productConcreteSku)
            );
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\Products\ProductsApiTester $I
     *
     * @return void
     */
    public function requestProductAbstractWithProductLabelsRelationship(ProductsApiTester $I): void
    {
        // Arrange
        $idProductLabel = $this->fixtures->getProductLabelTransfer()->getIdProductLabel();
        $url = $I->buildProductAbstractUrl(
            $this->fixtures->getProductConcreteTransferWithLabel()->getAbstractSku(),
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
    public function requestProductAbstractWithoutProductLabelsRelationship(ProductsApiTester $I): void
    {
        // Arrange
        $url = $I->buildProductAbstractUrl(
            $this->fixtures->getProductConcreteTransfer()->getAbstractSku(),
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
    public function requestProductAbstractWithProductLabelsRelationshipByPost(ProductsApiTester $I): void
    {
        // Arrange
        $url = $I->buildProductAbstractUrl(
            $this->fixtures->getProductConcreteTransferWithLabel()->getAbstractSku(),
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
    public function requestProductAbstractWithProductLabelRelationshipByPatch(ProductsApiTester $I): void
    {
        // Arrange
        $url = $I->buildProductAbstractUrl(
            $this->fixtures->getProductConcreteTransferWithLabel()->getAbstractSku(),
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
    public function requestProductAbstractWithProductPriceRelationship(ProductsApiTester $I): void
    {
        // Arrange
        $productAbstractSku = $this->fixtures->getProductConcreteTransfer()->getAbstractSku();
        $url = $I->buildProductAbstractUrl($productAbstractSku, [ProductPricesRestApiConfig::RESOURCE_ABSTRACT_PRODUCT_PRICES]);

        //act
        $I->sendGET($url);

        //assert
        $I->assertResponse(HttpCode::OK);

        $I->amSureSeeSingleResourceHasRelationshipByTypeAndId(
            ProductPricesRestApiConfig::RESOURCE_ABSTRACT_PRODUCT_PRICES,
            $productAbstractSku
        )
            ->whenI()
            ->seeSingleResourceHasRelationshipByTypeAndId(
                ProductPricesRestApiConfig::RESOURCE_ABSTRACT_PRODUCT_PRICES,
                $productAbstractSku
            );

        $I->amSureSeeIncludesContainsResourceByTypeAndId(
            ProductPricesRestApiConfig::RESOURCE_ABSTRACT_PRODUCT_PRICES,
            $productAbstractSku
        )
            ->whenI()
            ->seeIncludesContainsResourceByTypeAndId(
                ProductPricesRestApiConfig::RESOURCE_ABSTRACT_PRODUCT_PRICES,
                $productAbstractSku
            );
    }
}
