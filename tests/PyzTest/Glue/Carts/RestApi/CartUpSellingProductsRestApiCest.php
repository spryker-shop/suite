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
    public function requestCartUpSellingProducts(CartsApiTester $I): void
    {
        // Arrange
        $quoteTransfer = $this->fixtures->getQuoteTransfer();
        $productAbstractSku = $this->fixtures->getProductConcreteTransfer()->getAbstractSku();
        $I->requestCustomerLogin($quoteTransfer->getCustomer());

        // Act
        $I->sendGET($I->buildCartUpSellingProductsUrl($quoteTransfer->getUuid()));

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
            ->seeResourceByIdHasSelfLink(
                $productAbstractSku,
                $I->buildProductAbstractUrl($productAbstractSku)
            );
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\Carts\CartsApiTester $I
     *
     * @return void
     */
    public function requestCartUpSellingProductsWithProductConcreteRelationship(CartsApiTester $I): void
    {
        // Arrange
        $quoteTransfer = $this->fixtures->getQuoteTransfer();
        $productConcreteTransfer = $this->fixtures->getProductConcreteTransfer();
        $productAbstractSku = $productConcreteTransfer->getAbstractSku();
        $productConcreteSku = $productConcreteTransfer->getSku();
        $url = $I->buildCartUpSellingProductsUrl($quoteTransfer->getUuid(), [ProductsRestApiConfig::RESOURCE_CONCRETE_PRODUCTS]);
        $I->requestCustomerLogin($quoteTransfer->getCustomer());

        // Act
        $I->sendGET($url);

        // Assert
        $I->assertResponse(HttpCode::OK);

        $I->amSureSeeResourceByIdHasRelationshipByTypeAndId(
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
     * @param \PyzTest\Glue\Carts\CartsApiTester $I
     *
     * @return void
     */
    public function requestCartUpSellingProductsWithProductLabelsRelationship(CartsApiTester $I): void
    {
        // Arrange
        $quoteTransfer = $this->fixtures->getQuoteTransferWithLabel();
        $productConcreteSku = $this->fixtures->getProductConcreteTransferWithLabel()->getSku();
        $idProductLabel = $this->fixtures->getProductLabelTransfer()->getIdProductLabel();
        $url = $I->buildCartUpSellingProductsUrl(
            $quoteTransfer->getUuid(),
            [
                ProductsRestApiConfig::RESOURCE_CONCRETE_PRODUCTS,
                ProductLabelsRestApiConfig::RESOURCE_PRODUCT_LABELS,
            ]
        );
        $I->requestCustomerLogin($quoteTransfer->getCustomer());

        // Act
        $I->sendGET($url);

        // Assert
        $I->assertResponse(HttpCode::OK);

        $I->amSureSeeIncludedResourceByTypeAndIdHasRelationshipByTypeAndId(
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
     * @param \PyzTest\Glue\Carts\CartsApiTester $I
     *
     * @return void
     */
    public function requestCartUpSellingProductsWithoutProductLabelsRelationship(CartsApiTester $I): void
    {
        // Arrange
        $quoteTransfer = $this->fixtures->getQuoteTransfer();
        $url = $I->buildCartUpSellingProductsUrl(
            $quoteTransfer->getUuid(),
            [
                ProductsRestApiConfig::RESOURCE_CONCRETE_PRODUCTS,
                ProductLabelsRestApiConfig::RESOURCE_PRODUCT_LABELS,
            ]
        );
        $I->requestCustomerLogin($quoteTransfer->getCustomer());

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
     * @param \PyzTest\Glue\Carts\CartsApiTester $I
     *
     * @return void
     */
    public function requestCartUpSellingProductsByNotExistingCartUuid(CartsApiTester $I): void
    {
        // Arrange
        $I->requestCustomerLogin($this->fixtures->getQuoteTransfer()->getCustomer());

        // Act
        $I->sendGET($I->buildCartUpSellingProductsUrl('NotExistingUuid'));

        // Assert
        $I->assertResponse(HttpCode::NOT_FOUND);
        $I->seeResponseIsJson();
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\Carts\CartsApiTester $I
     *
     * @return void
     */
    public function requestCartUpSellingProductsWithProductLabelsRelationshipByPost(CartsApiTester $I): void
    {
        // Arrange
        $quoteTransfer = $this->fixtures->getQuoteTransferWithLabel();
        $url = $I->buildCartUpSellingProductsUrl(
            $quoteTransfer->getUuid(),
            [
                ProductsRestApiConfig::RESOURCE_CONCRETE_PRODUCTS,
                ProductLabelsRestApiConfig::RESOURCE_PRODUCT_LABELS,
            ]
        );
        $I->requestCustomerLogin($quoteTransfer->getCustomer());

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
    public function requestCartUpSellingProductsWithProductLabelsRelationshipByPatch(CartsApiTester $I): void
    {
        // Arrange
        $quoteTransfer = $this->fixtures->getQuoteTransferWithLabel();
        $url = $I->buildCartUpSellingProductsUrl(
            $quoteTransfer->getUuid(),
            [
                ProductsRestApiConfig::RESOURCE_CONCRETE_PRODUCTS,
                ProductLabelsRestApiConfig::RESOURCE_PRODUCT_LABELS,
            ]
        );
        $I->requestCustomerLogin($quoteTransfer->getCustomer());

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
    public function requestCartUpSellingProductsWithProductLabelsRelationshipByDelete(CartsApiTester $I): void
    {
        // Arrange
        $quoteTransfer = $this->fixtures->getQuoteTransferWithLabel();
        $url = $I->buildCartUpSellingProductsUrl(
            $quoteTransfer->getUuid(),
            [
                ProductsRestApiConfig::RESOURCE_CONCRETE_PRODUCTS,
                ProductLabelsRestApiConfig::RESOURCE_PRODUCT_LABELS,
            ]
        );
        $I->requestCustomerLogin($quoteTransfer->getCustomer());

        // Act
        $I->sendDELETE($url);

        // Assert
        $I->seeResponseCodeIs(HttpCode::NOT_FOUND);
        $I->seeResponseIsJson();
    }
}
