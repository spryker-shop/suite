<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Glue\Carts\RestApi;

use Codeception\Util\HttpCode;
use Pyz\Glue\ProductsRestApi\ProductsRestApiConfig;
use PyzTest\Glue\Carts\CartsApiTester;
use PyzTest\Glue\Carts\RestApi\Fixtures\GuestCartUpSellingProductsRestApiFixtures;
use Spryker\Glue\CartsRestApi\CartsRestApiConfig;
use Spryker\Glue\ProductLabelsRestApi\ProductLabelsRestApiConfig;

/**
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Glue
 * @group Carts
 * @group RestApi
 * @group GuestCartUpSellingProductsRestApiCest
 * Add your own group annotations below this line
 * @group EndToEnd
 */
class GuestCartUpSellingProductsRestApiCest
{
    /**
     * @var \PyzTest\Glue\Carts\RestApi\Fixtures\GuestCartUpSellingProductsRestApiFixtures
     */
    protected $fixtures;

    /**
     * @param \PyzTest\Glue\Carts\CartsApiTester $I
     *
     * @return void
     */
    public function loadFixtures(CartsApiTester $I): void
    {
        $this->fixtures = $I->loadFixtures(GuestCartUpSellingProductsRestApiFixtures::class);
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\Carts\CartsApiTester $I
     *
     * @return void
     */
    public function requestGuestCartUpSellingProducts(CartsApiTester $I): void
    {
        // Arrange
        $productAbstractSku = $this->fixtures->getProductConcreteTransfer()->getAbstractSku();
        $I->haveHttpHeader(
            CartsRestApiConfig::HEADER_ANONYMOUS_CUSTOMER_UNIQUE_ID,
            $this->fixtures->getGuestCustomerReference()
        );
        $url = $I->buildGuestCartUpSellingProductsUrl($this->fixtures->getGuestQuoteTransfer()->getUuid());

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
    public function requestGuestCartUpSellingProductsWithProductConcreteRelationship(CartsApiTester $I): void
    {
        // Arrange
        $productConcreteTransfer = $this->fixtures->getProductConcreteTransfer();
        $productAbstractSku = $productConcreteTransfer->getAbstractSku();
        $productConcreteSku = $productConcreteTransfer->getSku();
        $I->haveHttpHeader(
            CartsRestApiConfig::HEADER_ANONYMOUS_CUSTOMER_UNIQUE_ID,
            $this->fixtures->getGuestCustomerReference()
        );
        $url = $I->buildGuestCartUpSellingProductsUrl(
            $this->fixtures->getGuestQuoteTransfer()->getUuid(),
            [
                ProductsRestApiConfig::RESOURCE_CONCRETE_PRODUCTS,
            ]
        );

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
    public function requestGuestCartUpSellingProductsWithProductLabelsRelationship(CartsApiTester $I): void
    {
        // Arrange
        $productConcreteSku = $this->fixtures->getProductConcreteTransferWithLabel()->getSku();
        $idProductLabel = $this->fixtures->getProductLabelTransfer()->getIdProductLabel();
        $url = $I->buildGuestCartUpSellingProductsUrl(
            $this->fixtures->getGuestQuoteTransferWithLabel()->getUuid(),
            [
                ProductsRestApiConfig::RESOURCE_CONCRETE_PRODUCTS,
                ProductLabelsRestApiConfig::RESOURCE_PRODUCT_LABELS,
            ]
        );
        $I->haveHttpHeader(
            CartsRestApiConfig::HEADER_ANONYMOUS_CUSTOMER_UNIQUE_ID,
            $this->fixtures->getGuestCustomerReferenceWithLabel()
        );

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
    public function requestGuestCartUpSellingProductsWithoutProductLabelsRelationship(CartsApiTester $I): void
    {
        // Arrange
        $url = $I->buildGuestCartUpSellingProductsUrl(
            $this->fixtures->getGuestQuoteTransfer()->getUuid(),
            [
                ProductsRestApiConfig::RESOURCE_CONCRETE_PRODUCTS,
                ProductLabelsRestApiConfig::RESOURCE_PRODUCT_LABELS,
            ]
        );
        $I->haveHttpHeader(
            CartsRestApiConfig::HEADER_ANONYMOUS_CUSTOMER_UNIQUE_ID,
            $this->fixtures->getGuestCustomerReference()
        );

        // Act
        $I->sendGET($url);

        // Assert
        $I->assertResponse(HttpCode::OK);

        $I->amSureDontSeeIncludesContainsResourcesOfType(ProductLabelsRestApiConfig::RESOURCE_PRODUCT_LABELS)
            ->whenI()
            ->dontSeeIncludesContainsResourcesOfType(ProductLabelsRestApiConfig::RESOURCE_PRODUCT_LABELS);
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\Carts\CartsApiTester $I
     *
     * @return void
     */
    public function requestGuestCartUpSellingProductsByNotExistingCartUuid(CartsApiTester $I): void
    {
        // Arrange
        $I->haveHttpHeader(
            CartsRestApiConfig::HEADER_ANONYMOUS_CUSTOMER_UNIQUE_ID,
            'NotExistingReference'
        );

        // Act
        $I->sendGET($I->buildGuestCartUpSellingProductsUrl('NotExistingUuid'));

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
    public function requestGuestCartUpSellingProductsWithProductLabelsRelationshipPost(CartsApiTester $I): void
    {
        // Arrange
        $url = $I->buildGuestCartUpSellingProductsUrl(
            $this->fixtures->getGuestQuoteTransferWithLabel()->getUuid(),
            [
                ProductsRestApiConfig::RESOURCE_CONCRETE_PRODUCTS,
                ProductLabelsRestApiConfig::RESOURCE_PRODUCT_LABELS,
            ]
        );
        $I->haveHttpHeader(
            CartsRestApiConfig::HEADER_ANONYMOUS_CUSTOMER_UNIQUE_ID,
            $this->fixtures->getGuestCustomerReferenceWithLabel()
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
     * @param \PyzTest\Glue\Carts\CartsApiTester $I
     *
     * @return void
     */
    public function requestGuestCartUpSellingProductsWithProductLabelsRelationshipByPatch(CartsApiTester $I): void
    {
        // Arrange
        $url = $I->buildGuestCartUpSellingProductsUrl(
            $this->fixtures->getGuestQuoteTransferWithLabel()->getUuid(),
            [
                ProductsRestApiConfig::RESOURCE_CONCRETE_PRODUCTS,
                ProductLabelsRestApiConfig::RESOURCE_PRODUCT_LABELS,
            ]
        );
        $I->haveHttpHeader(
            CartsRestApiConfig::HEADER_ANONYMOUS_CUSTOMER_UNIQUE_ID,
            $this->fixtures->getGuestCustomerReferenceWithLabel()
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
     * @param \PyzTest\Glue\Carts\CartsApiTester $I
     *
     * @return void
     */
    public function requestGuestCartUpSellingProductsWithProductLabelsRelationshipByDelete(CartsApiTester $I): void
    {
        // Arrange
        $url = $I->buildGuestCartUpSellingProductsUrl(
            $this->fixtures->getGuestQuoteTransferWithLabel()->getUuid(),
            [
                ProductsRestApiConfig::RESOURCE_CONCRETE_PRODUCTS,
                ProductLabelsRestApiConfig::RESOURCE_PRODUCT_LABELS,
            ]
        );
        $I->haveHttpHeader(
            CartsRestApiConfig::HEADER_ANONYMOUS_CUSTOMER_UNIQUE_ID,
            $this->fixtures->getGuestCustomerReferenceWithLabel()
        );

        // Act
        $I->sendDELETE($url);

        // Assert
        $I->seeResponseCodeIs(HttpCode::NOT_FOUND);
        $I->seeResponseIsJson();
    }
}
