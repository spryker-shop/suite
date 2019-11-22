<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Glue\Wishlists\RestApi;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\ProductLabelTransfer;
use Generated\Shared\Transfer\WishlistItemTransfer;
use Generated\Shared\Transfer\WishlistTransfer;
use PyzTest\Glue\Wishlists\WishlistsApiTester;
use SprykerTest\Shared\Testify\Fixtures\FixturesBuilderInterface;
use SprykerTest\Shared\Testify\Fixtures\FixturesContainerInterface;

/**
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Glue
 * @group Wishlists
 * @group RestApi
 * @group WishlistsRestApiFixtures
 * Add your own group annotations below this line
 * @group EndToEnd
 */
class WishlistsRestApiFixtures implements FixturesBuilderInterface, FixturesContainerInterface
{
    protected const TEST_USERNAME = 'UserWishlistsRestApiFixtures';
    protected const TEST_USERNAME_WITH_LABEL = 'UserWishlistsRestApiFixturesWithLabel';
    protected const TEST_PASSWORD = 'password';

    /**
     * @var \Generated\Shared\Transfer\WishlistTransfer
     */
    protected $wishlistTransfer;

    /**
     * @var \Generated\Shared\Transfer\WishlistTransfer
     */
    protected $wishlistTransferWithLabel;

    /**
     * @var \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    protected $productConcreteTransfer;

    /**
     * @var \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    protected $productConcreteTransferWithLabel;

    /**
     * @var \Generated\Shared\Transfer\ProductLabelTransfer
     */
    protected $productLabelTransfer;

    /**
     * @var \Generated\Shared\Transfer\CustomerTransfer
     */
    protected $customerTransfer;

    /**
     * @var \Generated\Shared\Transfer\CustomerTransfer
     */
    protected $customerTransferWithLabel;

    /**
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function getProductConcreteTransfer(): ProductConcreteTransfer
    {
        return $this->productConcreteTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function getProductConcreteTransferWithLabel(): ProductConcreteTransfer
    {
        return $this->productConcreteTransferWithLabel;
    }

    /**
     * @return \Generated\Shared\Transfer\ProductLabelTransfer
     */
    public function getProductLabelTransfer(): ProductLabelTransfer
    {
        return $this->productLabelTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\WishlistTransfer
     */
    public function getWishlistTransfer(): WishlistTransfer
    {
        return $this->wishlistTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\WishlistTransfer
     */
    public function getWishlistTransferWithLabel(): WishlistTransfer
    {
        return $this->wishlistTransferWithLabel;
    }

    /**
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function getCustomerTransfer(): CustomerTransfer
    {
        return $this->customerTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function getCustomerTransferWithLabel(): CustomerTransfer
    {
        return $this->customerTransferWithLabel;
    }

    /**
     * @param \PyzTest\Glue\Wishlists\WishlistsApiTester $I
     *
     * @return \SprykerTest\Shared\Testify\Fixtures\FixturesContainerInterface
     */
    public function buildFixtures(WishlistsApiTester $I): FixturesContainerInterface
    {
        $this->createWishlist($I);
        $this->createWishlistWithProductLabelRelationship($I);

        return $this;
    }

    /**
     * @param \PyzTest\Glue\Wishlists\WishlistsApiTester $I
     *
     * @return void
     */
    protected function createWishlist(WishlistsApiTester $I): void
    {
        $this->productConcreteTransfer = $I->haveFullProduct();
        $this->customerTransfer = $I->haveCustomer([
            CustomerTransfer::USERNAME => static::TEST_USERNAME,
            CustomerTransfer::PASSWORD => static::TEST_PASSWORD,
            CustomerTransfer::NEW_PASSWORD => static::TEST_PASSWORD,
        ]);
        $this->wishlistTransfer = $I->haveWishlist([
            WishlistTransfer::FK_CUSTOMER => $this->customerTransfer->getIdCustomer(),
        ]);
        $I->haveItemInWishlist([
            WishlistItemTransfer::FK_WISHLIST => $this->wishlistTransfer->getIdWishlist(),
            WishlistItemTransfer::WISHLIST_NAME => $this->wishlistTransfer->getName(),
            WishlistItemTransfer::SKU => $this->productConcreteTransfer->getSku(),
            WishlistItemTransfer::FK_CUSTOMER => $this->customerTransfer->getIdCustomer(),
        ]);
    }

    /**
     * @param \PyzTest\Glue\Wishlists\WishlistsApiTester $I
     *
     * @return void
     */
    protected function createWishlistWithProductLabelRelationship(WishlistsApiTester $I): void
    {
        $this->productConcreteTransferWithLabel = $I->haveFullProduct();

        $this->productLabelTransfer = $I->haveProductLabel();
        $I->haveProductLabelToAbstractProductRelation(
            $this->productLabelTransfer->getIdProductLabel(),
            $this->productConcreteTransferWithLabel->getFkProductAbstract()
        );

        $this->customerTransferWithLabel = $I->haveCustomer([
            CustomerTransfer::USERNAME => static::TEST_USERNAME_WITH_LABEL,
            CustomerTransfer::PASSWORD => static::TEST_PASSWORD,
            CustomerTransfer::NEW_PASSWORD => static::TEST_PASSWORD,
        ]);

        $this->wishlistTransferWithLabel = $I->haveWishlist([
            WishlistTransfer::FK_CUSTOMER => $this->customerTransferWithLabel->getIdCustomer(),
        ]);
        $I->haveItemInWishlist([
            WishlistItemTransfer::FK_WISHLIST => $this->wishlistTransferWithLabel->getIdWishlist(),
            WishlistItemTransfer::WISHLIST_NAME => $this->wishlistTransferWithLabel->getName(),
            WishlistItemTransfer::SKU => $this->productConcreteTransferWithLabel->getSku(),
            WishlistItemTransfer::FK_CUSTOMER => $this->customerTransferWithLabel->getIdCustomer(),
        ]);
    }
}
