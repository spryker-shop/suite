<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Glue\Carts\RestApi;

use Codeception\Util\HttpCode;
use Pyz\Glue\ProductsRestApi\ProductsRestApiConfig;
use PyzTest\Glue\Carts\CartsApiTester;
use PyzTest\Glue\Carts\RestApi\Fixtures\GuestCartsRestApiFixtures;
use Spryker\Glue\CartsRestApi\CartsRestApiConfig;
use Spryker\Glue\ProductLabelsRestApi\ProductLabelsRestApiConfig;

/**
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Glue
 * @group Carts
 * @group RestApi
 * @group GuestCartsRestApiCest
 * Add your own group annotations below this line
 * @group EndToEnd
 */
class GuestCartsRestApiCest
{
    /**
     * @var \PyzTest\Glue\Carts\RestApi\Fixtures\GuestCartsRestApiFixtures
     */
    protected $fixtures;

    /**
     * @param \PyzTest\Glue\Carts\CartsApiTester $I
     *
     * @return void
     */
    public function loadFixtures(CartsApiTester $I): void
    {
        $this->fixtures = $I->loadFixtures(GuestCartsRestApiFixtures::class);
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\Carts\CartsApiTester $I
     *
     * @return void
     */
    public function requestGuestCartByUuid(CartsApiTester $I): void
    {
        // Arrange
        $quoteUuid = $this->fixtures->getGuestQuoteTransfer()->getUuid();
        $url = $I->buildGuestCartUrl($quoteUuid);
        $I->haveHttpHeader(
            CartsRestApiConfig::HEADER_ANONYMOUS_CUSTOMER_UNIQUE_ID,
            $this->fixtures->getGuestCustomerReference()
        );

        // Act
        $I->sendGET($url);

        // Assert
        $I->assertResponse(HttpCode::OK);

        $I->amSureSeeResponseDataContainsSingleResourceOfType(CartsRestApiConfig::RESOURCE_GUEST_CARTS)
            ->whenI()
            ->seeResponseDataContainsSingleResourceOfType(CartsRestApiConfig::RESOURCE_GUEST_CARTS);

        $I->amSureSeeSingleResourceIdEqualTo($quoteUuid)
            ->whenI()
            ->seeSingleResourceIdEqualTo($quoteUuid);

        $I->amSureSeeSingleResourceHasSelfLink()
            ->whenI()
            ->seeSingleResourceHasSelfLink($url);
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\Carts\CartsApiTester $I
     *
     * @return void
     */
    public function requestGuestCarts(CartsApiTester $I): void
    {
        // Arrange
        $quoteUuid = $this->fixtures->getGuestQuoteTransfer()->getUuid();
        $I->haveHttpHeader(
            CartsRestApiConfig::HEADER_ANONYMOUS_CUSTOMER_UNIQUE_ID,
            $this->fixtures->getGuestCustomerReference()
        );

        // Act
        $I->sendGET($I->buildGuestCartsUrl());

        // Assert
        $I->assertResponse(HttpCode::OK);

        $I->amSureSeeResponseDataContainsResourceCollectionOfType(CartsRestApiConfig::RESOURCE_GUEST_CARTS)
            ->whenI()
            ->seeResponseDataContainsResourceCollectionOfType(CartsRestApiConfig::RESOURCE_GUEST_CARTS);

        $I->amSureSeeResourceCollectionHasResourceWithId($quoteUuid)
            ->whenI()
            ->seeResourceCollectionHasResourceWithId($quoteUuid);

        $I->amSureSeeResourceByIdHasSelfLink($quoteUuid)
            ->whenI()
            ->seeResourceByIdHasSelfLink($quoteUuid, $I->buildGuestCartUrl($quoteUuid));
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\Carts\CartsApiTester $I
     *
     * @return void
     */
    public function requestGuestCartByUuidWithGuestCartItemsRelationship(CartsApiTester $I): void
    {
        // Arrange
        $productConcreteSku = $this->fixtures->getProductConcreteTransfer()->getSku();
        $I->haveHttpHeader(
            CartsRestApiConfig::HEADER_ANONYMOUS_CUSTOMER_UNIQUE_ID,
            $this->fixtures->getGuestCustomerReference()
        );
        $url = $I->buildGuestCartUrl(
            $this->fixtures->getGuestQuoteTransfer()->getUuid(),
            [
                CartsRestApiConfig::RESOURCE_GUEST_CARTS_ITEMS,
            ]
        );

        // Act
        $I->sendGET($url);

        // Assert
        $I->assertResponse(HttpCode::OK);

        $I->amSureSeeSingleResourceHasRelationshipByTypeAndId(
            CartsRestApiConfig::RESOURCE_GUEST_CARTS_ITEMS,
            $productConcreteSku
        )
            ->whenI()
            ->seeSingleResourceHasRelationshipByTypeAndId(
                CartsRestApiConfig::RESOURCE_GUEST_CARTS_ITEMS,
                $productConcreteSku
            );

        $I->amSureSeeIncludesContainsResourceByTypeAndId(
            CartsRestApiConfig::RESOURCE_GUEST_CARTS_ITEMS,
            $productConcreteSku
        )
            ->whenI()
            ->seeIncludesContainsResourceByTypeAndId(
                CartsRestApiConfig::RESOURCE_GUEST_CARTS_ITEMS,
                $productConcreteSku
            );
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\Carts\CartsApiTester $I
     *
     * @return void
     */
    public function requestGuestCartByUuidWithProductConcreteRelationship(CartsApiTester $I): void
    {
        // Arrange
        $productConcreteSku = $this->fixtures->getProductConcreteTransfer()->getSku();
        $url = $I->buildGuestCartUrl(
            $this->fixtures->getGuestQuoteTransfer()->getUuid(),
            [
                CartsRestApiConfig::RESOURCE_GUEST_CARTS_ITEMS,
                ProductsRestApiConfig::RESOURCE_CONCRETE_PRODUCTS,
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

        $I->amSureSeeIncludedResourceByTypeAndIdHasRelationshipByTypeAndId(
            CartsRestApiConfig::RESOURCE_GUEST_CARTS_ITEMS,
            $productConcreteSku,
            ProductsRestApiConfig::RESOURCE_CONCRETE_PRODUCTS,
            $productConcreteSku
        )
            ->whenI()
            ->seeIncludedResourceByTypeAndIdHasRelationshipByTypeAndId(
                CartsRestApiConfig::RESOURCE_GUEST_CARTS_ITEMS,
                $productConcreteSku,
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
    public function requestGuestCartByUuidWithProductLabelsRelationship(CartsApiTester $I): void
    {
        // Arrange
        $productConcreteSku = $this->fixtures->getProductConcreteTransferWithLabel()->getSku();
        $idProductLabel = $this->fixtures->getProductLabelTransfer()->getIdProductLabel();
        $url = $I->buildGuestCartUrl(
            $this->fixtures->getGuestQuoteTransferWithLabel()->getUuid(),
            [
                CartsRestApiConfig::RESOURCE_GUEST_CARTS_ITEMS,
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
    public function requestGuestCartByUuidWithoutProductLabelsRelationship(CartsApiTester $I): void
    {
        // Arrange
        $url = $I->buildGuestCartUrl(
            $this->fixtures->getGuestQuoteTransfer()->getUuid(),
            [
                CartsRestApiConfig::RESOURCE_GUEST_CARTS_ITEMS,
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
    public function requestGuestCartByNotExistingGuestCartUuid(CartsApiTester $I): void
    {
        // Arrange
        $I->haveHttpHeader(
            CartsRestApiConfig::HEADER_ANONYMOUS_CUSTOMER_UNIQUE_ID,
            'NotExistingReference'
        );

        // Act
        $I->sendGET($I->buildGuestCartUrl('NotExistingUuid'));

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
    public function requestGuestCartByUuidWithProductLabelsRelationshipByPost(CartsApiTester $I): void
    {
        // Arrange
        $url = $I->buildGuestCartUrl(
            $this->fixtures->getGuestQuoteTransferWithLabel()->getUuid(),
            [
                CartsRestApiConfig::RESOURCE_GUEST_CARTS_ITEMS,
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
    public function requestGuestCartByUuidWithProductLabelsRelationshipByPatch(CartsApiTester $I): void
    {
        // Arrange
        $url = $I->buildGuestCartUrl(
            $this->fixtures->getGuestQuoteTransferWithLabel()->getUuid(),
            [
                CartsRestApiConfig::RESOURCE_GUEST_CARTS_ITEMS,
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
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseIsJson();
    }
}
