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
        $oauthResponseTransfer = $I->haveAuthorizationToGlue($quoteTransfer->getCustomer());
        $I->amBearerAuthenticated($oauthResponseTransfer->getAccessToken());

        // Act
        $I->sendGET($url);

        // Assert
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesOpenApiSchema();

        $I->amSure('The returned resource is of correct type')
            ->whenI()
            ->seeResponseDataContainsSingleResourceOfType(CartsRestApiConfig::RESOURCE_CARTS);

        $I->amSure('The returned resource has correct id')
            ->whenI()
            ->seeSingleResourceIdEqualTo($quoteUuid);

        $I->amSure('The returned resource has correct self-link')
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
        $oauthResponseTransfer = $I->haveAuthorizationToGlue($quoteTransfer->getCustomer());
        $I->amBearerAuthenticated($oauthResponseTransfer->getAccessToken());

        // Act
        $I->sendGET($I->buildCartsUrl());

        // Assert
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesOpenApiSchema();

        $I->amSure('Response data contains resource collection')
            ->whenI()
            ->seeResponseDataContainsResourceCollectionOfType(CartsRestApiConfig::RESOURCE_CARTS);

        $I->amSure('Resource collection has resource')
            ->whenI()
            ->seeResourceCollectionHasResourceWithId($quoteUuid);

        $I->amSure('Resource has correct self-link')
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
        $oauthResponseTransfer = $I->haveAuthorizationToGlue($quoteTransfer->getCustomer());
        $I->amBearerAuthenticated($oauthResponseTransfer->getAccessToken());

        // Act
        $I->sendGET($I->buildCartUrl($quoteUuid, [CartsRestApiConfig::RESOURCE_CART_ITEMS]));

        // Assert
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesOpenApiSchema();

        $I->amSure('The returned resource has relationship')
            ->whenI()
            ->seeSingleResourceHasRelationshipByTypeAndId(
                CartsRestApiConfig::RESOURCE_CART_ITEMS,
                $cartItemGroupKey
            );

        $I->amSure('The returned resource has include')
            ->whenI()
            ->seeIncludesContainsResourceByTypeAndId(
                CartsRestApiConfig::RESOURCE_CART_ITEMS,
                $cartItemGroupKey
            );

        $I->amSure('The include has correct self-link')
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
        $oauthResponseTransfer = $I->haveAuthorizationToGlue($quoteTransfer->getCustomer());
        $I->amBearerAuthenticated($oauthResponseTransfer->getAccessToken());

        // Act
        $I->sendGET($url);

        // Assert
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesOpenApiSchema();

        $I->amSure('The included resource has a relationship')
            ->whenI()
            ->seeIncludedResourceByTypeAndIdHasRelationshipByTypeAndId(
                CartsRestApiConfig::RESOURCE_CART_ITEMS,
                $cartItemGroupKey,
                ProductsRestApiConfig::RESOURCE_CONCRETE_PRODUCTS,
                $productConcreteSku
            );

        $I->amSure('The returned resource has include')
            ->whenI()
            ->seeIncludesContainsResourceByTypeAndId(
                ProductsRestApiConfig::RESOURCE_CONCRETE_PRODUCTS,
                $productConcreteSku
            );

        $I->amSure('The include has correct self-link')
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
        $oauthResponseTransfer = $I->haveAuthorizationToGlue($quoteTransfer->getCustomer());
        $I->amBearerAuthenticated($oauthResponseTransfer->getAccessToken());

        // Act
        $I->sendGET($url);

        // Assert
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesOpenApiSchema();

        $I->amSure('The included resource has a relationship')
            ->whenI()
            ->seeIncludedResourceByTypeAndIdHasRelationshipByTypeAndId(
                ProductsRestApiConfig::RESOURCE_CONCRETE_PRODUCTS,
                $productConcreteSku,
                ProductLabelsRestApiConfig::RESOURCE_PRODUCT_LABELS,
                $idProductLabel
            );

        $I->amSure('The returned resource has include')
            ->whenI()
            ->seeIncludesContainsResourceByTypeAndId(
                ProductLabelsRestApiConfig::RESOURCE_PRODUCT_LABELS,
                $idProductLabel
            );

        $I->amSure('The include has correct self-link')
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
        $oauthResponseTransfer = $I->haveAuthorizationToGlue($quoteTransfer->getCustomer());
        $I->amBearerAuthenticated($oauthResponseTransfer->getAccessToken());

        // Act
        $I->sendGET($url);

        // Assert
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesOpenApiSchema();

        $I->amSure('The returned resource does not have includes')
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
        $oauthResponseTransfer = $I->haveAuthorizationToGlue($this->fixtures->getQuoteTransferWithLabel()->getCustomer());
        $I->amBearerAuthenticated($oauthResponseTransfer->getAccessToken());

        // Act
        $I->sendGET($I->buildCartUrl('NotExistingUuid'));

        // Assert
        $I->seeResponseCodeIs(HttpCode::NOT_FOUND);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesOpenApiSchema();
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
        $oauthResponseTransfer = $I->haveAuthorizationToGlue($quoteTransfer->getCustomer());
        $I->amBearerAuthenticated($oauthResponseTransfer->getAccessToken());

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
        $oauthResponseTransfer = $I->haveAuthorizationToGlue($quoteTransfer->getCustomer());
        $I->amBearerAuthenticated($oauthResponseTransfer->getAccessToken());

        // Act
        $I->sendPATCH($url);

        // Assert
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseIsJson();
    }
}
