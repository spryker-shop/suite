<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Glue\Carts\RestApi;

use Codeception\Util\HttpCode;
use Pyz\Glue\ProductsRestApi\ProductsRestApiConfig;
use PyzTest\Glue\Carts\CartsApiTester;
use PyzTest\Glue\Carts\RestApi\Fixtures\CartUpSellingProductsRestApiFixtures;
use Spryker\Glue\ProductLabelsRestApi\ProductLabelsRestApiConfig;

/**
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Glue
 * @group Carts
 * @group RestApi
 * @group CartUpSellingProductsRestApiCest
 * Add your own group annotations below this line
 * @group EndToEnd
 */
class CartUpSellingProductsRestApiCest
{
    protected const INCLUDE_RESOURCES = [
        ProductsRestApiConfig::RESOURCE_CONCRETE_PRODUCTS,
        ProductLabelsRestApiConfig::RESOURCE_PRODUCT_LABELS,
    ];

    /**
     * @var \PyzTest\Glue\Carts\RestApi\Fixtures\CartUpSellingProductsRestApiFixtures
     */
    protected $fixtures;

    /**
     * @param \PyzTest\Glue\Carts\CartsApiTester $I
     *
     * @return void
     */
    public function loadFixtures(CartsApiTester $I): void
    {
        $this->fixtures = $I->loadFixtures(CartUpSellingProductsRestApiFixtures::class);
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\Carts\CartsApiTester $I
     *
     * @return void
     */
    public function requestCartUpSellingProductsWithProductLabelRelationship(CartsApiTester $I): void
    {
        // Arrange
        $quoteUuid = $this->fixtures->getQuoteTransferWithLabel()->getUuid();
        $productConcreteTransfer = $this->fixtures->getProductConcreteTransferWithLabel();
        $productAbstractSku = $productConcreteTransfer->getAbstractSku();
        $productConcreteSku = $productConcreteTransfer->getSku();
        $idProductLabel = $this->fixtures->getProductLabelTransfer()->getIdProductLabel();
        $I->requestCustomerLogin($this->fixtures->getCustomerTransferWithLabel());
        $url = $I->buildCartUpSellingProductsUrl($quoteUuid, static::INCLUDE_RESOURCES);

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
            ->seeResourceByIdHasSelfLink(
                $productAbstractSku,
                $I->buildProductAbstractUrl($productAbstractSku)
            );

        $I->amSureResourceByIdHasRelationshipByTypeAndId(
            $productAbstractSku,
            ProductsRestApiConfig::RESOURCE_CONCRETE_PRODUCTS,
            $productConcreteSku
        )
            ->whenI()
            ->seeResourceByIdHasRelationshipByTypeAndId(
                $productAbstractSku,
                ProductsRestApiConfig::RESOURCE_CONCRETE_PRODUCTS,
                $productConcreteSku
            );

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
            ProductsRestApiConfig::RESOURCE_CONCRETE_PRODUCTS,
            $productConcreteSku
        )
            ->whenI()
            ->seeIncludesContainsResourceByTypeAndId(
                ProductsRestApiConfig::RESOURCE_CONCRETE_PRODUCTS,
                $productConcreteSku
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

        $I->amSureIncludedResourceByTypeAndIdHasRelationshipByTypeAndId(
            ProductsRestApiConfig::RESOURCE_CONCRETE_PRODUCTS,
            $productConcreteSku,
            ProductLabelsRestApiConfig::RESOURCE_PRODUCT_LABELS,
            $idProductLabel
        )
            ->whenI()
            ->seeIncludedResourceByTypeAndIdHasRelationshipByTypeAndId(
                ProductsRestApiConfig::RESOURCE_CONCRETE_PRODUCTS,
                $productConcreteSku,
                ProductLabelsRestApiConfig::RESOURCE_PRODUCT_LABELS,
                $idProductLabel
            );
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\Carts\CartsApiTester $I
     *
     * @return void
     */
    public function requestCartUpSellingProductsWithoutProductLabelRelationship(CartsApiTester $I): void
    {
        // Arrange
        $quoteUuid = $this->fixtures->getQuoteTransfer()->getUuid();
        $I->requestCustomerLogin($this->fixtures->getCustomerTransfer());
        $url = $I->buildCartUpSellingProductsUrl($quoteUuid, static::INCLUDE_RESOURCES);

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
     * @param \PyzTest\Glue\Carts\CartsApiTester $I
     *
     * @return void
     */
    public function requestCartUpSellingProductsByNotExistingCartUuid(CartsApiTester $I): void
    {
        // Arrange
        $I->requestCustomerLogin($this->fixtures->getCustomerTransfer());
        $url = $I->buildCartUpSellingProductsUrl('NotExistingUuid');

        // Act
        $I->sendGET($url);

        // Assert
        $I->assertResponse(HttpCode::NOT_FOUND);
        $I->seeResponseDataContainsEmptyCollection();
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\Carts\CartsApiTester $I
     *
     * @return void
     */
    public function requestCartUpSellingProductsWithProductLabelRelationshipByPost(CartsApiTester $I): void
    {
        // Arrange
        $quoteUuid = $this->fixtures->getQuoteTransferWithLabel()->getUuid();
        $I->requestCustomerLogin($this->fixtures->getCustomerTransferWithLabel());
        $url = $I->buildCartUpSellingProductsUrl($quoteUuid, static::INCLUDE_RESOURCES);

        // Act
        $I->sendPOST($url);

        // Assert
        $I->seeResponseCodeIs(HttpCode::NOT_FOUND);
        $I->seeResponseIsJson();
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\Carts\CartsApiTester $I
     *
     * @return void
     */
    public function requestCartUpSellingProductsLabelRelationshipByPatch(CartsApiTester $I): void
    {
        // Arrange
        $quoteUuid = $this->fixtures->getQuoteTransferWithLabel()->getUuid();
        $I->requestCustomerLogin($this->fixtures->getCustomerTransferWithLabel());
        $url = $I->buildCartUpSellingProductsUrl($quoteUuid, static::INCLUDE_RESOURCES);

        // Act
        $I->sendPATCH($url);

        // Assert
        $I->seeResponseCodeIs(HttpCode::NOT_FOUND);
        $I->seeResponseIsJson();
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\Carts\CartsApiTester $I
     *
     * @return void
     */
    public function requestCartUpSellingProductsLabelRelationshipByDelete(CartsApiTester $I): void
    {
        // Arrange
        $quoteUuid = $this->fixtures->getQuoteTransferWithLabel()->getUuid();
        $I->requestCustomerLogin($this->fixtures->getCustomerTransferWithLabel());
        $url = $I->buildCartUpSellingProductsUrl($quoteUuid, static::INCLUDE_RESOURCES);

        // Act
        $I->sendDELETE($url);

        // Assert
        $I->seeResponseCodeIs(HttpCode::NOT_FOUND);
        $I->seeResponseIsJson();
    }
}
