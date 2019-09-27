<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Glue\Wishlists\RestApi;

use Codeception\Util\HttpCode;
use PyzTest\Glue\Wishlists\WishlistsApiTester;

/**
 * Auto-generated group annotations
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
        $token = $I->haveAuth(
            $this->fixtures->getCustomerTransfer()
        )
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
        $wishlistTransfer = $this->fixtures->getWishlistTransfer();
        //act
        $this->requestCustomerLogin($I);
        $I->sendGET(
            $I->formatUrl(
                'wishlists/{IdWishlist}',
                [
                    'IdWishlist' => $wishlistTransfer->getUuid(),
                ]
            )
        );

        //assert
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesOpenApiSchema();

        $I->amSure('Returned resource is of type wishlists')
            ->whenI()
            ->seeResponseDataContainsSingleResourceOfType('wishlists');
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
        $wishlistTransfer = $this->fixtures->getWishlistTransfer();
        $productConcreteTransfer = $this->fixtures->getProductConcreteTransfer();
        //act
        $this->requestCustomerLogin($I);
        $I->sendGET(
            $I->formatUrl(
                'wishlists/{IdWishlist}?include=wishlist-items,concrete-products',
                [
                    'IdWishlist' => $wishlistTransfer->getUuid(),
                ]
            )
        );

        //assert
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesOpenApiSchema();

        $I->amSure('Returned resource has include of type concrete-products')
            ->whenI()
            ->seeIncludesContainsResourceByTypeAndId(
                'concrete-products',
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
        $wishlistTransfer = $this->fixtures->getWishlistTransferWithLabel();
        $productConcreteTransfer = $this->fixtures->getProductConcreteTransferWithLabel();
        $productLabelTransfer = $this->fixtures->getProductLabelTransfer();
        //act
        $this->requestCustomerLogin($I);
        $I->sendGET(
            $I->formatUrl(
                'wishlists/{IdWishlist}?include=wishlist-items,concrete-products,product-labels',
                [
                    'IdWishlist' => $wishlistTransfer->getUuid(),
                ]
            )
        );

        //assert
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesOpenApiSchema();

        $I->amSure('Returned resource has include of type concrete-products')
            ->whenI()
            ->seeIncludesContainsResourceByTypeAndId(
                'concrete-products',
                $productConcreteTransfer->getSku()
            );

        $I->amSure('Returned resource has include of type product-labels')
            ->whenI()
            ->seeIncludesContainsResourceByTypeAndId(
                'product-labels',
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
        $wishlistTransfer = $this->fixtures->getWishlistTransfer();
        $productConcreteTransfer = $this->fixtures->getProductConcreteTransfer();
        //act
        $this->requestCustomerLogin($I);
        $I->sendGET(
            $I->formatUrl(
                'wishlists/{IdWishlist}?include=wishlist-items,concrete-products,product-labels',
                [
                    'IdWishlist' => $wishlistTransfer->getUuid(),
                ]
            )
        );

        //assert
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesOpenApiSchema();

        $I->amSure('Returned resource has include of type concrete-products')
            ->whenI()
            ->seeIncludesContainsResourceByTypeAndId(
                'concrete-products',
                $productConcreteTransfer->getSku()
            );
        $I->dontSeeResponseContains('"product-labels"');
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
        //act
        $this->requestCustomerLogin($I);
        $I->sendGET(
            $I->formatUrl(
                'wishlists/{IdWishlist}?include=wishlist-items,concrete-products,product-labels',
                [
                    'IdWishlist' => 'NotExistingUuid',
                ]
            )
        );

        //assert
        $I->seeResponseCodeIs(HttpCode::NOT_FOUND);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesOpenApiSchema();
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
        $wishlistTransfer = $this->fixtures->getWishlistTransfer();
        $productConcreteTransfer = $this->fixtures->getProductConcreteTransfer();
        //act
        $this->requestCustomerLogin($I);
        $I->sendPOST(
            $I->formatUrl(
                'wishlists/{IdWishlist}?include=wishlist-items,concrete-products,product-labels',
                [
                    'IdWishlist' => $wishlistTransfer->getUuid(),
                ]
            )
        );

        //assert
        $I->seeResponseCodeIs(HttpCode::NOT_FOUND);
        $I->seeResponseIsJson();
    }
}
