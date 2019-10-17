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
    protected const INCLUDE_RESOURCES = [
        WishlistsRestApiConfig::RESOURCE_WISHLIST_ITEMS,
        ProductsRestApiConfig::RESOURCE_CONCRETE_PRODUCTS,
        ProductLabelsRestApiConfig::RESOURCE_PRODUCT_LABELS,
    ];

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
    public function requestWishlist(WishlistsApiTester $I): void
    {
        // Arrange
        $wishlistUuid = $this->fixtures->getWishlistTransfer()->getUuid();
        $I->requestCustomerLogin($this->fixtures->getCustomerTransfer());
        $url = $I->buildWishlistUrl($wishlistUuid);

        // Act
        $I->sendGET($url);

        // Assert
        $I->assertResponse(HttpCode::OK);

        $I->amSureResponseDataContainsSingleResourceOfType(WishlistsRestApiConfig::RESOURCE_WISHLISTS)
            ->whenI()
            ->seeResponseDataContainsSingleResourceOfType(WishlistsRestApiConfig::RESOURCE_WISHLISTS);

        $I->amSureSingleResourceIdEqualTo()
            ->whenI()
            ->seeSingleResourceIdEqualTo($wishlistUuid);

        $I->amSureSingleResourceHasSelfLink()
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
    public function requestWishlistItemsWithProductLabelRelationship(WishlistsApiTester $I): void
    {
        // Arrange
        $wishlistUuid = $this->fixtures->getWishlistTransferWithLabel()->getUuid();
        $productConcreteSku = $this->fixtures->getProductConcreteTransferWithLabel()->getSku();
        $idProductLabel = $this->fixtures->getProductLabelTransfer()->getIdProductLabel();
        $I->requestCustomerLogin($this->fixtures->getCustomerTransfer());
        $url = $I->buildWishlistUrl($wishlistUuid, static::INCLUDE_RESOURCES);

        // Act
        $I->sendGET($url);

        // Assert
        $I->assertResponse(HttpCode::OK);

        $I->amSureResponseDataContainsSingleResourceOfType(WishlistsRestApiConfig::RESOURCE_WISHLISTS)
            ->whenI()
            ->seeResponseDataContainsSingleResourceOfType(WishlistsRestApiConfig::RESOURCE_WISHLISTS);

        $I->amSureSingleResourceIdEqualTo()
            ->whenI()
            ->seeSingleResourceIdEqualTo($wishlistUuid);

        $I->amSureSingleResourceHasSelfLink()
            ->whenI()
            ->seeSingleResourceHasSelfLink($url);

        $I->amSureSingleResourceHasRelationshipByTypeAndId(
            WishlistsRestApiConfig::RESOURCE_WISHLIST_ITEMS,
            $productConcreteSku
        )
            ->whenI()
            ->seeSingleResourceHasRelationshipByTypeAndId(
                WishlistsRestApiConfig::RESOURCE_WISHLIST_ITEMS,
                $productConcreteSku
            );

        $I->amSureIncludedResourceByTypeAndIdHasRelationshipByTypeAndId(
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

        $I->amSureIncludesContainsResourceByTypeAndId(
            ProductsRestApiConfig::RESOURCE_CONCRETE_PRODUCTS,
            $productConcreteSku
        )
            ->whenI()
            ->seeIncludesContainsResourceByTypeAndId(
                ProductsRestApiConfig::RESOURCE_CONCRETE_PRODUCTS,
                $productConcreteSku
            );

        $I->amSureIncludedResourceByTypeAndIdHasSelfLink(
            ProductsRestApiConfig::RESOURCE_CONCRETE_PRODUCTS,
            $productConcreteSku
        )
            ->whenI()
            ->seeIncludedResourceByTypeAndIdHasSelfLink(
                ProductsRestApiConfig::RESOURCE_CONCRETE_PRODUCTS,
                $productConcreteSku,
                $I->buildProductConcreteUrl($productConcreteSku)
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
     * @param \PyzTest\Glue\Wishlists\WishlistsApiTester $I
     *
     * @return void
     */
    public function requestWishlistItemsWithoutProductLabelRelationship(WishlistsApiTester $I): void
    {
        // Arrange
        $wishlistUuid = $this->fixtures->getWishlistTransfer()->getUuid();
        $I->requestCustomerLogin($this->fixtures->getCustomerTransfer());
        $url = $I->buildWishlistUrl($wishlistUuid, static::INCLUDE_RESOURCES);

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
     * @param \PyzTest\Glue\Wishlists\WishlistsApiTester $I
     *
     * @return void
     */
    public function requestgWishlistByNotExistingWishlistUuid(WishlistsApiTester $I): void
    {
        // Arrange
        $I->requestCustomerLogin($this->fixtures->getCustomerTransfer());
        $url = $I->buildWishlistUrl('NotExistingUuid');

        // Act
        $I->sendGET($url);

        // Assert
        $I->assertResponse(HttpCode::NOT_FOUND);
        $I->seeResponseDataContainsEmptyCollection();
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\Wishlists\WishlistsApiTester $I
     *
     * @return void
     */
    public function requestWishlistItemsWithProductLabelRelationshipByPost(WishlistsApiTester $I): void
    {
        // Arrange
        $wishlistUuid = $this->fixtures->getWishlistTransferWithLabel()->getUuid();
        $I->requestCustomerLogin($this->fixtures->getCustomerTransfer());
        $url = $I->buildWishlistUrl($wishlistUuid, static::INCLUDE_RESOURCES);

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
    public function requestWishlistItemsWithProductLabelRelationshipByPatch(WishlistsApiTester $I): void
    {
        // Arrange
        $wishlistUuid = $this->fixtures->getWishlistTransferWithLabel()->getUuid();
        $I->requestCustomerLogin($this->fixtures->getCustomerTransfer());
        $url = $I->buildWishlistUrl($wishlistUuid, static::INCLUDE_RESOURCES);

        // Act
        $I->sendPATCH($url);

        // Assert
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseIsJson();
    }
}
