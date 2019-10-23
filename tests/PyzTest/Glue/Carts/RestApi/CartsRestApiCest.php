<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Glue\Carts\RestApi;

use Codeception\Util\HttpCode;
use Pyz\Glue\ProductsRestApi\ProductsRestApiConfig;
use PyzTest\Glue\Carts\CartsApiTester;
use PyzTest\Glue\Carts\RestApi\Fixtures\CartsRestApiFixtures;
use Spryker\Glue\CartsRestApi\CartsRestApiConfig;
use Spryker\Glue\ProductLabelsRestApi\ProductLabelsRestApiConfig;

/**
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Glue
 * @group Carts
 * @group RestApi
 * @group CartsRestApiCest
 * Add your own group annotations below this line
 * @group EndToEnd
 */
class CartsRestApiCest
{
    /**
     * @var \PyzTest\Glue\Carts\RestApi\Fixtures\CartsRestApiFixtures
     */
    protected $fixtures;

    /**
     * @param \PyzTest\Glue\Carts\CartsApiTester $I
     *
     * @return void
     */
    public function loadFixtures(CartsApiTester $I): void
    {
        $this->fixtures = $I->loadFixtures(CartsRestApiFixtures::class);
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\Carts\CartsApiTester $I
     *
     * @return void
     */
    public function requestCartByUuid(CartsApiTester $I): void
    {
        // Arrange
        $quoteTransfer = $this->fixtures->getQuoteTransfer();
        $quoteUuid = $quoteTransfer->getUuid();
        $url = $I->buildCartUrl($quoteUuid);
        $I->requestCustomerLogin($quoteTransfer->getCustomer());

        // Act
        $I->sendGET($url);

        // Assert
        $I->assertResponse(HttpCode::OK);

        $I->amSureSeeResponseDataContainsSingleResourceOfType(CartsRestApiConfig::RESOURCE_CARTS)
            ->whenI()
            ->seeResponseDataContainsSingleResourceOfType(CartsRestApiConfig::RESOURCE_CARTS);

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
    public function requestCarts(CartsApiTester $I): void
    {
        // Arrange
        $quoteTransfer = $this->fixtures->getQuoteTransfer();
        $quoteUuid = $quoteTransfer->getUuid();
        $I->requestCustomerLogin($quoteTransfer->getCustomer());

        // Act
        $I->sendGET($I->buildCartsUrl());

        // Assert
        $I->assertResponse(HttpCode::OK);

        $I->amSureSeeResponseDataContainsResourceCollectionOfType(CartsRestApiConfig::RESOURCE_CARTS)
            ->whenI()
            ->seeResponseDataContainsResourceCollectionOfType(CartsRestApiConfig::RESOURCE_CARTS);

        $I->amSureSeeResourceCollectionHasResourceWithId($quoteUuid)
            ->whenI()
            ->seeResourceCollectionHasResourceWithId($quoteUuid);

        $I->amSureSeeResourceByIdHasSelfLink($quoteUuid)
            ->whenI()
            ->seeResourceByIdHasSelfLink($quoteUuid, $I->buildCartUrl($quoteUuid));
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\Carts\CartsApiTester $I
     *
     * @return void
     */
    public function requestCartByUuidWithCartItemsRelationship(CartsApiTester $I): void
    {
        // Arrange
        $quoteTransfer = $this->fixtures->getQuoteTransfer();
        $quoteUuid = $quoteTransfer->getUuid();
        $cartItemGroupKey = $quoteTransfer->getItems()->offsetGet(0)->getGroupKey();
        $I->requestCustomerLogin($quoteTransfer->getCustomer());

        // Act
        $I->sendGET($I->buildCartUrl($quoteUuid, [CartsRestApiConfig::RESOURCE_CART_ITEMS]));

        // Assert
        $I->assertResponse(HttpCode::OK);

        $I->amSureSeeSingleResourceHasRelationshipByTypeAndId(
            CartsRestApiConfig::RESOURCE_CART_ITEMS,
            $cartItemGroupKey
        )
            ->whenI()
            ->seeSingleResourceHasRelationshipByTypeAndId(
                CartsRestApiConfig::RESOURCE_CART_ITEMS,
                $cartItemGroupKey
            );

        $I->amSureSeeIncludesContainsResourceByTypeAndId(
            CartsRestApiConfig::RESOURCE_CART_ITEMS,
            $cartItemGroupKey
        )
            ->whenI()
            ->seeIncludesContainsResourceByTypeAndId(
                CartsRestApiConfig::RESOURCE_CART_ITEMS,
                $cartItemGroupKey
            );

        $I->amSureSeeIncludedResourceByTypeAndIdHasSelfLink(
            CartsRestApiConfig::RESOURCE_CART_ITEMS,
            $cartItemGroupKey
        )
            ->whenI()
            ->seeIncludedResourceByTypeAndIdHasSelfLink(
                CartsRestApiConfig::RESOURCE_CART_ITEMS,
                $cartItemGroupKey,
                $I->buildCartItemUrl($quoteUuid, $cartItemGroupKey)
            );
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\Carts\CartsApiTester $I
     *
     * @return void
     */
    public function requestCartByUuidWithProductConcreteRelationship(CartsApiTester $I): void
    {
        // Arrange
        $quoteTransfer = $this->fixtures->getQuoteTransfer();
        $cartItemGroupKey = $quoteTransfer->getItems()->offsetGet(0)->getGroupKey();
        $productConcreteSku = $this->fixtures->getProductConcreteTransfer()->getSku();
        $url = $I->buildCartUrl(
            $quoteTransfer->getUuid(),
            [
                CartsRestApiConfig::RESOURCE_CART_ITEMS,
                ProductsRestApiConfig::RESOURCE_CONCRETE_PRODUCTS,
            ]
        );
        $I->requestCustomerLogin($quoteTransfer->getCustomer());

        // Act
        $I->sendGET($url);

        // Assert
        $I->assertResponse(HttpCode::OK);

        $I->amSureSeeIncludedResourceByTypeAndIdHasRelationshipByTypeAndId(
            CartsRestApiConfig::RESOURCE_CART_ITEMS,
            $cartItemGroupKey,
            ProductsRestApiConfig::RESOURCE_CONCRETE_PRODUCTS,
            $productConcreteSku
        )
            ->whenI()
            ->seeIncludedResourceByTypeAndIdHasRelationshipByTypeAndId(
                CartsRestApiConfig::RESOURCE_CART_ITEMS,
                $cartItemGroupKey,
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
    public function requestCartByUuidWithProductLabelsRelationship(CartsApiTester $I): void
    {
        // Arrange
        $quoteTransfer = $this->fixtures->getQuoteTransferWithLabel();
        $productConcreteSku = $this->fixtures->getProductConcreteTransferWithLabel()->getSku();
        $idProductLabel = $this->fixtures->getProductLabelTransfer()->getIdProductLabel();
        $url = $I->buildCartUrl(
            $quoteTransfer->getUuid(),
            [
                CartsRestApiConfig::RESOURCE_CART_ITEMS,
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
    public function requestCartByUuidWithoutProductLabelsRelationship(CartsApiTester $I): void
    {
        // Arrange
        $quoteTransfer = $this->fixtures->getQuoteTransfer();
        $url = $I->buildCartUrl(
            $quoteTransfer->getUuid(),
            [
                CartsRestApiConfig::RESOURCE_CART_ITEMS,
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
    public function requestCartByNotExistingCartUuid(CartsApiTester $I): void
    {
        // Arrange
        $I->requestCustomerLogin($this->fixtures->getQuoteTransferWithLabel()->getCustomer());

        // Act
        $I->sendGET($I->buildCartUrl('NotExistingUuid'));

        // Assert
        $I->assertResponse(HttpCode::NOT_FOUND);
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\Carts\CartsApiTester $I
     *
     * @return void
     */
    public function requestCartByUuidWithProductLabelsRelationshipByPost(CartsApiTester $I): void
    {
        // Arrange
        $quoteTransfer = $this->fixtures->getQuoteTransferWithLabel();
        $url = $I->buildCartUrl(
            $quoteTransfer->getUuid(),
            [
                CartsRestApiConfig::RESOURCE_CART_ITEMS,
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
    public function requestCartByUuidWithProductLabelsRelationshipByPatch(CartsApiTester $I): void
    {
        // Arrange
        $quoteTransfer = $this->fixtures->getQuoteTransferWithLabel();
        $url = $I->buildCartUrl(
            $quoteTransfer->getUuid(),
            [
                CartsRestApiConfig::RESOURCE_CART_ITEMS,
                ProductsRestApiConfig::RESOURCE_CONCRETE_PRODUCTS,
                ProductLabelsRestApiConfig::RESOURCE_PRODUCT_LABELS,
            ]
        );
        $I->requestCustomerLogin($quoteTransfer->getCustomer());

        // Act
        $I->sendPATCH($url);

        // Assert
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseIsJson();
    }
}
