<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Glue\Wishlists\RestApi;

use Codeception\Util\HttpCode;
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
     * @var \PyzTest\Glue\Wishlists\RestApi\WishlistsRestApiFixtures
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
    protected function requestCustomerLogin(WishlistsApiTester $I): void
    {
        $token = $I->haveAuth($this->fixtures->getCustomerTransfer())
            ->getAccessToken();
        $I->amBearerAuthenticated($token);
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\Wishlists\WishlistsApiTester $I
     *
     * @return void
     */
    public function requestExistingWishlist(WishlistsApiTester $I): void
    {
        // Arrange
        $wishlistTransfer = $this->fixtures->getWishlistTransfer();
        $this->requestCustomerLogin($I);

        // Act
        $I->sendGET(
            $I->formatUrl(
                '{resourceWishlists}/{wishlistUuid}',
                [
                    'resourceWishlists' => WishlistsRestApiConfig::RESOURCE_WISHLISTS,
                    'wishlistUuid' => $wishlistTransfer->getUuid(),
                ]
            )
        );

        // Assert
        $I->assertResponse(HttpCode::OK);

        $I->amSure('Returned resource is of type wishlists')
            ->whenI()
            ->seeResponseDataContainsSingleResourceOfType(WishlistsRestApiConfig::RESOURCE_WISHLISTS);

        $I->amSure('Returned resource has correct id')
            ->whenI()
            ->seeSingleResourceIdEqualTo($wishlistTransfer->getUuid());
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\Wishlists\WishlistsApiTester $I
     *
     * @return void
     */
    public function requestExistingWishlistItems(WishlistsApiTester $I): void
    {
        // Arrange
        $wishlistTransfer = $this->fixtures->getWishlistTransfer();
        $productConcreteTransfer = $this->fixtures->getProductConcreteTransfer();
        $this->requestCustomerLogin($I);

        // Act
        $I->sendGET(
            $I->formatUrl(
                '{resourceWishlists}/{wishlistUuid}?include={relationWishlistItems},{relationConcreteProducts}',
                [
                    'resourceWishlists' => WishlistsRestApiConfig::RESOURCE_WISHLISTS,
                    'relationWishlistItems' => WishlistsRestApiConfig::RESOURCE_WISHLIST_ITEMS,
                    'relationConcreteProducts' => ProductsRestApiConfig::RESOURCE_CONCRETE_PRODUCTS,
                    'wishlistUuid' => $wishlistTransfer->getUuid(),
                ]
            )
        );

        // Assert
        $I->assertResponse(HttpCode::OK);

        $I->amSure('Returned resource has include of type concrete-products')
            ->whenI()
            ->seeIncludesContainsResourceByTypeAndId(
                ProductsRestApiConfig::RESOURCE_CONCRETE_PRODUCTS,
                $productConcreteTransfer->getSku()
            );
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\Wishlists\WishlistsApiTester $I
     *
     * @return void
     */
    public function requestExistingWishlistItemsWithProductLabelRelationship(WishlistsApiTester $I): void
    {
        // Arrange
        $wishlistTransfer = $this->fixtures->getWishlistTransferWithLabel();
        $productConcreteTransfer = $this->fixtures->getProductConcreteTransferWithLabel();
        $productLabelTransfer = $this->fixtures->getProductLabelTransfer();
        $this->requestCustomerLogin($I);

        // Act
        $I->sendGET(
            $I->formatUrl(
                '{resourceWishlists}/{wishlistUuid}?include={relationWishlistItems},{relationConcreteProducts},{relationshipProductLabels}',
                [
                    'resourceWishlists' => WishlistsRestApiConfig::RESOURCE_WISHLISTS,
                    'relationWishlistItems' => WishlistsRestApiConfig::RESOURCE_WISHLIST_ITEMS,
                    'relationConcreteProducts' => ProductsRestApiConfig::RESOURCE_CONCRETE_PRODUCTS,
                    'relationshipProductLabels' => ProductLabelsRestApiConfig::RESOURCE_PRODUCT_LABELS,
                    'wishlistUuid' => $wishlistTransfer->getUuid(),
                ]
            )
        );

        // Assert
        $I->assertResponse(HttpCode::OK);

        $I->amSure('Returned resource has include of type concrete-products')
            ->whenI()
            ->seeIncludesContainsResourceByTypeAndId(
                ProductsRestApiConfig::RESOURCE_CONCRETE_PRODUCTS,
                $productConcreteTransfer->getSku()
            );

        $I->amSure('Returned resource has include of type product-labels')
            ->whenI()
            ->seeIncludesContainsResourceByTypeAndId(
                ProductLabelsRestApiConfig::RESOURCE_PRODUCT_LABELS,
                $productLabelTransfer->getIdProductLabel()
            );
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\Wishlists\WishlistsApiTester $I
     *
     * @return void
     */
    public function requestExistingWishlistItemsWithoutProductLabelRelationship(WishlistsApiTester $I): void
    {
        // Arrange
        $wishlistTransfer = $this->fixtures->getWishlistTransfer();
        $productConcreteTransfer = $this->fixtures->getProductConcreteTransfer();
        $this->requestCustomerLogin($I);

        // Act
        $I->sendGET(
            $I->formatUrl(
                '{resourceWishlists}/{wishlistUuid}?include={relationWishlistItems},{relationConcreteProducts},{relationshipProductLabels}',
                [
                    'resourceWishlists' => WishlistsRestApiConfig::RESOURCE_WISHLISTS,
                    'relationWishlistItems' => WishlistsRestApiConfig::RESOURCE_WISHLIST_ITEMS,
                    'relationConcreteProducts' => ProductsRestApiConfig::RESOURCE_CONCRETE_PRODUCTS,
                    'relationshipProductLabels' => ProductLabelsRestApiConfig::RESOURCE_PRODUCT_LABELS,
                    'wishlistUuid' => $wishlistTransfer->getUuid(),
                ]
            )
        );

        // Assert
        $I->assertResponse(HttpCode::OK);

        $I->amSure('Returned resource has include of type concrete-products')
            ->whenI()
            ->seeIncludesContainsResourceByTypeAndId(
                ProductsRestApiConfig::RESOURCE_CONCRETE_PRODUCTS,
                $productConcreteTransfer->getSku()
            );

        $I->dontSeeResponseContains('"' . ProductLabelsRestApiConfig::RESOURCE_PRODUCT_LABELS . '"');
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\Wishlists\WishlistsApiTester $I
     *
     * @return void
     */
    public function requestNotExistingWishlistItemsWithoutProductLabelRelationship(WishlistsApiTester $I): void
    {
        // Arrange
        $this->requestCustomerLogin($I);

        // Act
        $I->sendGET(
            $I->formatUrl(
                '{resourceWishlists}/{wishlistUuid}?include={relationWishlistItems},{relationConcreteProducts},{relationshipProductLabels}',
                [
                    'resourceWishlists' => WishlistsRestApiConfig::RESOURCE_WISHLISTS,
                    'relationWishlistItems' => WishlistsRestApiConfig::RESOURCE_WISHLIST_ITEMS,
                    'relationConcreteProducts' => ProductsRestApiConfig::RESOURCE_CONCRETE_PRODUCTS,
                    'relationshipProductLabels' => ProductLabelsRestApiConfig::RESOURCE_PRODUCT_LABELS,
                    'wishlistUuid' => 'NotExistingUuid',
                ]
            )
        );

        // Assert
        $I->assertResponse(HttpCode::NOT_FOUND);
        $I->dontSeeResponseMatchesJsonPath('$.data[*]');
        $I->dontSeeResponseMatchesJsonPath('$.included[*]');
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\Wishlists\WishlistsApiTester $I
     *
     * @return void
     */
    public function requestExistingWishlistItemsWithoutProductLabelRelationshipByPost(WishlistsApiTester $I): void
    {
        // Arrange
        $wishlistTransfer = $this->fixtures->getWishlistTransfer();
        $this->requestCustomerLogin($I);

        // Act
        $I->sendPOST(
            $I->formatUrl(
                '{resourceWishlists}/{wishlistUuid}?include={relationWishlistItems},{relationConcreteProducts},{relationshipProductLabels}',
                [
                    'resourceWishlists' => WishlistsRestApiConfig::RESOURCE_WISHLISTS,
                    'relationWishlistItems' => WishlistsRestApiConfig::RESOURCE_WISHLIST_ITEMS,
                    'relationConcreteProducts' => ProductsRestApiConfig::RESOURCE_CONCRETE_PRODUCTS,
                    'relationshipProductLabels' => ProductLabelsRestApiConfig::RESOURCE_PRODUCT_LABELS,
                    'wishlistUuid' => $wishlistTransfer->getUuid(),
                ]
            )
        );

        // Assert
        $I->seeResponseCodeIs(HttpCode::NOT_FOUND);
        $I->seeResponseIsJson();
    }
}
