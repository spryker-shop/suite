<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Glue\Wishlists\RestApi;

use Codeception\Util\HttpCode;
use PyzTest\Glue\Wishlists\RestApi\Fixtures\WishlistsRestApiFixtures;
use PyzTest\Glue\Wishlists\WishlistsApiTester;
use Spryker\Glue\ProductLabelsRestApi\ProductLabelsRestApiConfig;
use Spryker\Glue\ProductsRestApi\ProductsRestApiConfig;
use Spryker\Glue\WishlistsRestApi\WishlistsRestApiConfig;

/**
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Glue
 * @group Wishlists
 * @group RestApi
 * @group WishlistRestApiCest
 * Add your own group annotations below this line
 * @group EndToEnd
 */
class WishlistRestApiCest
{
    /**
     * @var \PyzTest\Glue\Wishlists\RestApi\Fixtures\WishlistsRestApiFixtures
     */
    protected $fixtures;

    /**
     * @param \PyzTest\Glue\Wishlists\WishlistsApiTester $I
     *
     * @return void
     */
    public function loadFixtures(WishlistsApiTester $I): void
    {
        $this->fixtures = $I->loadFixtures(WishlistsRestApiFixtures::class);
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\Wishlists\WishlistsApiTester $I
     *
     * @return void
     */
    public function requestWishlistByUuid(WishlistsApiTester $I): void
    {
        // Arrange
        $wishlistUuid = $this->fixtures->getWishlistTransfer()->getUuid();
        $I->requestCustomerLogin($this->fixtures->getCustomerTransfer());
        $url = $I->buildWishlistUrl($wishlistUuid);

        // Act
        $I->sendGET($url);

        // Assert
        $I->assertResponse(HttpCode::OK);

        $I->amSureSeeResponseDataContainsSingleResourceOfType(WishlistsRestApiConfig::RESOURCE_WISHLISTS)
            ->whenI()
            ->seeResponseDataContainsSingleResourceOfType(WishlistsRestApiConfig::RESOURCE_WISHLISTS);

        $I->amSureSeeSingleResourceIdEqualTo($wishlistUuid)
            ->whenI()
            ->seeSingleResourceIdEqualTo($wishlistUuid);

        $I->amSureSeeSingleResourceHasSelfLink()
            ->whenI()
            ->seeSingleResourceHasSelfLink($url);
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\Wishlists\WishlistsApiTester $I
     *
     * @return void
     */
    public function requestWishlists(WishlistsApiTester $I): void
    {
        $I->requestCustomerLogin($this->fixtures->getCustomerTransfer());
        $wishlistUuid = $this->fixtures->getWishlistTransfer()->getUuid();

        // Act
        $I->sendGET($I->buildWishlistsUrl());

        // Assert
        $I->assertResponse(HttpCode::OK);

        $I->amSureSeeResponseDataContainsResourceCollectionOfType(WishlistsRestApiConfig::RESOURCE_WISHLISTS)
            ->whenI()
            ->seeResponseDataContainsResourceCollectionOfType(WishlistsRestApiConfig::RESOURCE_WISHLISTS);

        $I->amSureSeeResourceCollectionHasResourceWithId($wishlistUuid)
            ->whenI()
            ->seeResourceCollectionHasResourceWithId($wishlistUuid);

        $I->amSureSeeResourceByIdHasSelfLink($wishlistUuid)
            ->whenI()
            ->seeResourceByIdHasSelfLink($wishlistUuid, $I->buildWishlistUrl($wishlistUuid));
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\Wishlists\WishlistsApiTester $I
     *
     * @return void
     */
    public function requestWishlistByUuidWithWishlistItemsRelationship(WishlistsApiTester $I): void
    {
        // Arrange
        $productConcreteSku = $this->fixtures->getProductConcreteTransfer()->getSku();
        $I->requestCustomerLogin($this->fixtures->getCustomerTransfer());
        $url = $I->buildWishlistUrl(
            $this->fixtures->getWishlistTransfer()->getUuid(),
            [
                WishlistsRestApiConfig::RESOURCE_WISHLIST_ITEMS,
            ]
        );

        // Act
        $I->sendGET($url);

        // Assert
        $I->assertResponse(HttpCode::OK);

        $I->amSureSeeSingleResourceHasRelationshipByTypeAndId(
            WishlistsRestApiConfig::RESOURCE_WISHLIST_ITEMS,
            $productConcreteSku
        )
            ->whenI()
            ->seeSingleResourceHasRelationshipByTypeAndId(
                WishlistsRestApiConfig::RESOURCE_WISHLIST_ITEMS,
                $productConcreteSku
            );

        $I->amSureSeeIncludesContainsResourceByTypeAndId(
            WishlistsRestApiConfig::RESOURCE_WISHLIST_ITEMS,
            $productConcreteSku
        )
            ->whenI()
            ->seeIncludesContainsResourceByTypeAndId(
                WishlistsRestApiConfig::RESOURCE_WISHLIST_ITEMS,
                $productConcreteSku
            );
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\Wishlists\WishlistsApiTester $I
     *
     * @return void
     */
    public function requestWishlistByUuidWithProductConcreteRelationship(WishlistsApiTester $I): void
    {
        // Arrange
        $productConcreteSku = $this->fixtures->getProductConcreteTransfer()->getSku();
        $I->requestCustomerLogin($this->fixtures->getCustomerTransfer());
        $url = $I->buildWishlistUrl(
            $this->fixtures->getWishlistTransfer()->getUuid(),
            [
                WishlistsRestApiConfig::RESOURCE_WISHLIST_ITEMS,
                ProductsRestApiConfig::RESOURCE_CONCRETE_PRODUCTS,
            ]
        );

        // Act
        $I->sendGET($url);

        // Assert
        $I->assertResponse(HttpCode::OK);

        $I->amSureSeeIncludedResourceByTypeAndIdHasRelationshipByTypeAndId(
            WishlistsRestApiConfig::RESOURCE_WISHLIST_ITEMS,
            $productConcreteSku,
            ProductsRestApiConfig::RESOURCE_CONCRETE_PRODUCTS,
            $productConcreteSku
        )
            ->whenI()
            ->seeIncludedResourceByTypeAndIdHasRelationshipByTypeAndId(
                WishlistsRestApiConfig::RESOURCE_WISHLIST_ITEMS,
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
     * @param \PyzTest\Glue\Wishlists\WishlistsApiTester $I
     *
     * @return void
     */
    public function requestWishlistByUuidWithProductLabelsRelationship(WishlistsApiTester $I): void
    {
        // Arrange
        $productConcreteSku = $this->fixtures->getProductConcreteTransferWithLabel()->getSku();
        $idProductLabel = $this->fixtures->getProductLabelTransfer()->getIdProductLabel();
        $I->requestCustomerLogin($this->fixtures->getCustomerTransferWithLabel());
        $url = $I->buildWishlistUrl(
            $this->fixtures->getWishlistTransferWithLabel()->getUuid(),
            [
                WishlistsRestApiConfig::RESOURCE_WISHLIST_ITEMS,
                ProductsRestApiConfig::RESOURCE_CONCRETE_PRODUCTS,
                ProductLabelsRestApiConfig::RESOURCE_PRODUCT_LABELS,
            ]
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
     * @param \PyzTest\Glue\Wishlists\WishlistsApiTester $I
     *
     * @return void
     */
    public function requestWishlistByUuidWithoutProductLabelsRelationship(WishlistsApiTester $I): void
    {
        // Arrange
        $I->requestCustomerLogin($this->fixtures->getCustomerTransfer());
        $url = $I->buildWishlistUrl(
            $this->fixtures->getWishlistTransfer()->getUuid(),
            [
                WishlistsRestApiConfig::RESOURCE_WISHLIST_ITEMS,
                ProductsRestApiConfig::RESOURCE_CONCRETE_PRODUCTS,
                ProductLabelsRestApiConfig::RESOURCE_PRODUCT_LABELS,
            ]
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
     * @param \PyzTest\Glue\Wishlists\WishlistsApiTester $I
     *
     * @return void
     */
    public function requestWishlistByNotExistingWishlistUuid(WishlistsApiTester $I): void
    {
        // Arrange
        $I->requestCustomerLogin($this->fixtures->getCustomerTransfer());

        // Act
        $I->sendGET($I->buildWishlistUrl('NotExistingUuid'));

        // Assert
        $I->assertResponse(HttpCode::NOT_FOUND);
        $I->seeResponseIsJson();
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\Wishlists\WishlistsApiTester $I
     *
     * @return void
     */
    public function requestWishlistByUuidWithProductLabelsRelationshipByPost(WishlistsApiTester $I): void
    {
        // Arrange
        $I->requestCustomerLogin($this->fixtures->getCustomerTransferWithLabel());
        $url = $I->buildWishlistUrl(
            $this->fixtures->getWishlistTransferWithLabel()->getUuid(),
            [
                WishlistsRestApiConfig::RESOURCE_WISHLIST_ITEMS,
                ProductsRestApiConfig::RESOURCE_CONCRETE_PRODUCTS,
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
     * @param \PyzTest\Glue\Wishlists\WishlistsApiTester $I
     *
     * @return void
     */
    public function requestWishlistByUuidWithProductLabelsRelationshipByPatch(WishlistsApiTester $I): void
    {
        // Arrange
        $I->requestCustomerLogin($this->fixtures->getCustomerTransferWithLabel());
        $url = $I->buildWishlistUrl(
            $this->fixtures->getWishlistTransferWithLabel()->getUuid(),
            [
                WishlistsRestApiConfig::RESOURCE_WISHLIST_ITEMS,
                ProductsRestApiConfig::RESOURCE_CONCRETE_PRODUCTS,
                ProductLabelsRestApiConfig::RESOURCE_PRODUCT_LABELS,
            ]
        );

        // Act
        $I->sendPATCH($url);

        // Assert
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseIsJson();
    }
}
