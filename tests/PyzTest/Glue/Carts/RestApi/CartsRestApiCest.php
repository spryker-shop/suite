<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Glue\Carts\RestApi;

use Codeception\Util\HttpCode;
use PyzTest\Glue\Carts\CartsApiTester;
use Spryker\Glue\CartsRestApi\CartsRestApiConfig;

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
     * @var \PyzTest\Glue\Carts\RestApi\CartsRestApiFixtures
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
    public function requestCreateCart(CartsApiTester $I): void
    {
        // Arrange
        $this->authorizeCustomer($I);

        // Act
        $I->sendPOST(
            CartsRestApiConfig::RESOURCE_CARTS,
            [
                'data' => [
                    'type' => CartsRestApiConfig::RESOURCE_CARTS,
                    'attributes' => [
                        'priceMode' => $I::GROSS_MODE,
                        'currency' => $I::CURRENCY_EUR,
                        'store' => $I::STORE_DE,
                        'name' => $I::TEST_CART_NAME,
                    ],
                ],
            ]
        );

        //assert
        $I->seeResponseCodeIs(HttpCode::CREATED);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesOpenApiSchema();

        $I->amSure(sprintf('Returned resource is of type %s', CartsRestApiConfig::RESOURCE_CARTS))
            ->whenI()
            ->seeResponseDataContainsSingleResourceOfType(CartsRestApiConfig::RESOURCE_CARTS);
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\Carts\CartsApiTester $I
     *
     * @return void
     */
    public function requestCreateCartWithoutAuthorizationToken(CartsApiTester $I): void
    {
        // Act
        $I->sendPOST(
            CartsRestApiConfig::RESOURCE_CARTS,
            [
                'data' => [
                    'type' => CartsRestApiConfig::RESOURCE_CARTS,
                    'attributes' => [
                        'priceMode' => $I::GROSS_MODE,
                        'currency' => $I::CURRENCY_EUR,
                        'store' => $I::STORE_DE,
                        'name' => $I::TEST_CART_NAME,
                    ],
                ],
            ]
        );

        //assert
        $I->seeResponseCodeIs(HttpCode::FORBIDDEN);
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
    public function requestCreateCartWithoutPriceMode(CartsApiTester $I): void
    {
        // Arrange
        $this->authorizeCustomer($I);

        // Act
        $I->sendPOST(
            CartsRestApiConfig::RESOURCE_CARTS,
            [
                'data' => [
                    'type' => CartsRestApiConfig::RESOURCE_CARTS,
                    'attributes' => [
                        'currency' => $I::CURRENCY_EUR,
                        'store' => $I::STORE_DE,
                        'name' => $I::TEST_CART_NAME,
                    ],
                ],
            ]
        );

        //assert
        $I->seeResponseCodeIs(HttpCode::UNPROCESSABLE_ENTITY);
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
    public function requestCreateCartWithoutPriceCurrency(CartsApiTester $I): void
    {
        // Arrange
        $this->authorizeCustomer($I);

        // Act
        $I->sendPOST(
            CartsRestApiConfig::RESOURCE_CARTS,
            [
                'data' => [
                    'type' => CartsRestApiConfig::RESOURCE_CARTS,
                    'attributes' => [
                        'priceMode' => $I::GROSS_MODE,
                        'store' => $I::STORE_DE,
                        'name' => $I::TEST_CART_NAME,
                    ],
                ],
            ]
        );

        //assert
        $I->seeResponseCodeIs(HttpCode::UNPROCESSABLE_ENTITY);
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
    public function requestFindCarts(CartsApiTester $I): void
    {
        // Arrange
        $this->authorizeCustomer($I);

        // Act
        $I->sendGET(
            $I->formatUrl(
                '{resourceCarts}',
                [
                    'resourceCarts' => CartsRestApiConfig::RESOURCE_CARTS,
                ]
            )
        );

        //assert
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesOpenApiSchema();
        $I->seeResponseDataContainsNonEmptyCollection();
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\Carts\CartsApiTester $I
     *
     * @return void
     */
    public function requestFindCart(CartsApiTester $I): void
    {
        // Arrange
        $this->authorizeCustomer($I);

        // Act
        $I->sendGET(
            $I->formatUrl(
                '{resourceCarts}/{cartUuid}',
                [
                    'resourceCarts' => CartsRestApiConfig::RESOURCE_CARTS,
                    'cartUuid' => $this->fixtures->getQuoteTransfer()->getUuid(),
                ]
            )
        );

        //assert
        $I->seeResponseCodeIs(HttpCode::OK);
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
    public function requestFindCartWithNonExistingUuid(CartsApiTester $I): void
    {
        // Arrange
        $this->authorizeCustomer($I);

        // Act
        $I->sendGET(
            $I->formatUrl(
                '{resourceCarts}/{cartUuid}',
                [
                    'resourceCarts' => CartsRestApiConfig::RESOURCE_CARTS,
                    'cartUuid' => 'test wrong uuid',
                ]
            )
        );

        //assert
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
    public function requestFindCartWithoutAuthorizationToken(CartsApiTester $I): void
    {
        // Act
        $I->sendGET(
            $I->formatUrl(
                '{resourceCarts}/{cartUuid}',
                [
                    'resourceCarts' => CartsRestApiConfig::RESOURCE_CARTS,
                    'cartUuid' => $this->fixtures->getQuoteTransfer()->getUuid(),
                ]
            )
        );

        //assert
        $I->seeResponseCodeIs(HttpCode::FORBIDDEN);
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\Carts\CartsApiTester $I
     *
     * @return void
     */
    public function requestFindCartWithItemRelationship(CartsApiTester $I): void
    {
        // Arrange
        $this->authorizeCustomer($I);

        // Act
        $I->sendGET(
            $I->formatUrl(
                '{resourceCarts}/{cartUuid}?include=items',
                [
                    'resourceCarts' => CartsRestApiConfig::RESOURCE_CARTS,
                    'cartUuid' => $this->fixtures->getQuoteTransfer()->getUuid(),
                ]
            )
        );

        //assert
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesOpenApiSchema();

        $I->amSure('Returned resource has include of type items')
            ->whenI()
            ->seeSingleResourceHasRelationshipByTypeAndId('items', $this->fixtures->getProductConcreteTransfer1()->getSku());

        $I->amSure('Returned resource has include of type items')
            ->whenI()
            ->seeIncludesContainsResourceByTypeAndId('items', $this->fixtures->getProductConcreteTransfer1()->getSku());
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\Carts\CartsApiTester $I
     *
     * @return void
     */
    public function requestUpdateCart(CartsApiTester $I): void
    {
        // Arrange
        $this->authorizeCustomer($I);
        $emptyQuoteTransfer = $this->fixtures->getEmptyQuoteTransfer();
        $cartUuid = $emptyQuoteTransfer->getUuid();
        $entityTag = $I->haveEntityTag(CartsRestApiConfig::RESOURCE_CARTS, $cartUuid, $emptyQuoteTransfer->toArray());
        $I->haveHttpHeader('If-Match', $entityTag);

        // Act
        $I->sendPATCH(
            $I->formatUrl(
                '{resourceCarts}/{cartUuid}',
                [
                    'resourceCarts' => CartsRestApiConfig::RESOURCE_CARTS,
                    'cartUuid' => $cartUuid,
                ]
            ),
            [
                'data' => [
                    'type' => CartsRestApiConfig::RESOURCE_CARTS,
                    'attributes' => [
                        'name' => $I::TEST_CART_NAME,
                        'currency' => $I::CURRENCY_EUR,
                        'priceMode' => $I::GROSS_MODE,
                    ],
                ],
            ]
        );

        //assert
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\Carts\CartsApiTester $I
     *
     * @return void
     */
    public function requestUpdatePriceModeOfNonEmptyCart(CartsApiTester $I): void
    {
        // Arrange
        $this->authorizeCustomer($I);
        $quoteTransfer = $this->fixtures->getQuoteTransfer();
        $cartUuid = $quoteTransfer->getUuid();
        $entityTag = $I->findResourceEntityTag(CartsRestApiConfig::RESOURCE_CARTS, $cartUuid);

        $I->haveHttpHeader('If-Match', $entityTag);

        // Act
        $I->sendPATCH(
            $I->formatUrl(
                '{resourceCarts}/{cartUuid}',
                [
                    'resourceCarts' => CartsRestApiConfig::RESOURCE_CARTS,
                    'cartUuid' => $cartUuid,
                ]
            ),
            [
                'data' => [
                    'type' => CartsRestApiConfig::RESOURCE_CARTS,
                    'attributes' => [
                        'name' => $I::TEST_CART_NAME,
                        'currency' => $I::CURRENCY_EUR,
                        'priceMode' => $I::GROSS_MODE,
                    ],
                ],
            ]
        );

        //assert
        $I->seeResponseCodeIs(HttpCode::UNPROCESSABLE_ENTITY);
        $I->seeResponseIsJson();
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\Carts\CartsApiTester $I
     *
     * @return void
     */
    public function requestUpdateCartWithoutCartUuid(CartsApiTester $I): void
    {
        // Arrange
        $this->authorizeCustomer($I);
        $entityTag = $I->findResourceEntityTag(
            CartsRestApiConfig::RESOURCE_CARTS,
            $this->fixtures->getEmptyQuoteTransfer()->getUuid()
        );
        $I->haveHttpHeader('If-Match', $entityTag);

        // Act
        $I->sendPATCH(
            CartsRestApiConfig::RESOURCE_CARTS,
            [
                'data' => [
                    'type' => CartsRestApiConfig::RESOURCE_CARTS,
                    'attributes' => [
                        'name' => $I::TEST_CART_NAME,
                        'currency' => $I::CURRENCY_EUR,
                        'priceMode' => $I::GROSS_MODE,
                    ],
                ],
            ]
        );

        //assert
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseIsJson();
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\Carts\CartsApiTester $I
     *
     * @return void
     */
    public function requestUpdateCartWithoutAuthorizationToken(CartsApiTester $I): void
    {
        // Act
        $I->sendPATCH(
            $I->formatUrl(
                '{resourceCarts}/{cartUuid}',
                [
                    'resourceCarts' => CartsRestApiConfig::RESOURCE_CARTS,
                    'cartUuid' => $this->fixtures->getQuoteTransfer()->getUuid(),
                ]
            ),
            [
                'data' => [
                    'type' => CartsRestApiConfig::RESOURCE_CARTS,
                    'attributes' => [
                        'name' => $I::TEST_CART_NAME,
                        'currency' => $I::CURRENCY_EUR,
                        'priceMode' => $I::GROSS_MODE,
                    ],
                ],
            ]
        );

        //assert
        $I->seeResponseCodeIs(HttpCode::FORBIDDEN);
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
    public function requestAddItemsToCart(CartsApiTester $I): void
    {
        // Arrange
        $this->authorizeCustomer($I);

        // Act
        $I->sendPOST(
            $I->formatUrl(
                '{resourceCarts}/{cartUuid}/{resourceCartItems}',
                [
                    'resourceCarts' => CartsRestApiConfig::RESOURCE_CARTS,
                    'cartUuid' => $this->fixtures->getQuoteTransfer()->getUuid(),
                    'resourceCartItems' => CartsRestApiConfig::RESOURCE_CART_ITEMS,
                ]
            ),
            [
                'data' => [
                    'type' => CartsRestApiConfig::RESOURCE_CART_ITEMS,
                    'attributes' => [
                        'sku' => $this->fixtures->getProductConcreteTransfer1()->getSku(),
                        'quantity' => 1,
                    ],
                ],
            ]
        );

        //assert
        $I->seeResponseCodeIs(HttpCode::CREATED);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesOpenApiSchema();

        $I->amSure('Returned resource is of type carts')
            ->whenI()
            ->seeResponseDataContainsSingleResourceOfType('carts');
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\Carts\CartsApiTester $I
     *
     * @return void
     */
    public function requestAddItemsToCartWithoutAuthorizationToken(CartsApiTester $I): void
    {
        // Act
        $I->sendPOST(
            $I->formatUrl(
                '{resourceCarts}/{cartUuid}/{resourceCartItems}',
                [
                    'resourceCarts' => CartsRestApiConfig::RESOURCE_CARTS,
                    'cartUuid' => $this->fixtures->getQuoteTransfer()->getUuid(),
                    'resourceCartItems' => CartsRestApiConfig::RESOURCE_CART_ITEMS,
                ]
            ),
            [
                'data' => [
                    'type' => CartsRestApiConfig::RESOURCE_CART_ITEMS,
                    'attributes' => [
                        'sku' => $this->fixtures->getProductConcreteTransfer2()->getSku(),
                        'quantity' => 1,
                    ],
                ],
            ]
        );

        //assert
        $I->seeResponseCodeIs(HttpCode::FORBIDDEN);
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
    public function requestAddItemsToCartWithoutItemSku(CartsApiTester $I): void
    {
        // Arrange
        $this->authorizeCustomer($I);

        // Act
        $I->sendPOST(
            $I->formatUrl(
                '{resourceCarts}/{cartUuid}/{resourceCartItems}',
                [
                    'resourceCarts' => CartsRestApiConfig::RESOURCE_CARTS,
                    'cartUuid' => $this->fixtures->getQuoteTransfer()->getUuid(),
                    'resourceCartItems' => CartsRestApiConfig::RESOURCE_CART_ITEMS,
                ]
            ),
            [
                'data' => [
                    'type' => CartsRestApiConfig::RESOURCE_CART_ITEMS,
                    'attributes' => [
                        'quantity' => 1,
                    ],
                ],
            ]
        );

        //assert
        $I->seeResponseCodeIs(HttpCode::UNPROCESSABLE_ENTITY);
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
    public function requestAddItemsToCartWithoutItemQuantity(CartsApiTester $I): void
    {
        // Arrange
        $this->authorizeCustomer($I);

        // Act
        $I->sendPOST(
            $I->formatUrl(
                '{resourceCarts}/{cartUuid}/{resourceCartItems}',
                [
                    'resourceCarts' => CartsRestApiConfig::RESOURCE_CARTS,
                    'cartUuid' => $this->fixtures->getQuoteTransfer()->getUuid(),
                    'resourceCartItems' => CartsRestApiConfig::RESOURCE_CART_ITEMS,
                ]
            ),
            [
                'data' => [
                    'type' => CartsRestApiConfig::RESOURCE_CART_ITEMS,
                    'attributes' => [
                        'sku' => $this->fixtures->getProductConcreteTransfer2()->getSku(),
                    ],
                ],
            ]
        );

        //assert
        $I->seeResponseCodeIs(HttpCode::UNPROCESSABLE_ENTITY);
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
    public function requestUpdateItemsInCart(CartsApiTester $I): void
    {
        // Arrange
        $this->authorizeCustomer($I);

        // Act
        $I->sendPATCH(
            $I->formatUrl(
                '{resourceCarts}/{cartUuid}/{resourceCartItems}/{itemSku}',
                [
                    'resourceCarts' => CartsRestApiConfig::RESOURCE_CARTS,
                    'cartUuid' => $this->fixtures->getQuoteTransfer()->getUuid(),
                    'resourceCartItems' => CartsRestApiConfig::RESOURCE_CART_ITEMS,
                    'itemSku' => $this->fixtures->getProductConcreteTransfer1()->getSku(),
                ]
            ),
            [
                'data' => [
                    'type' => CartsRestApiConfig::RESOURCE_CART_ITEMS,
                    'attributes' => [
                        'quantity' => $I::QUANTITY_FOR_ITEM_UPDATE,
                    ],
                ],
            ]
        );

        //assert
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesOpenApiSchema();

        $I->amSure('Returned resource is of type carts')
            ->whenI()
            ->seeResponseDataContainsSingleResourceOfType('carts');

        $I->seeCartItemQuantityEqualsToQuantityInRequest(
            $I::QUANTITY_FOR_ITEM_UPDATE,
            CartsRestApiConfig::RESOURCE_CART_ITEMS,
            $this->fixtures->getProductConcreteTransfer1()->getSku()
        );
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\Carts\CartsApiTester $I
     *
     * @return void
     */
    public function requestUpdateItemsInCartWithoutCartUuid(CartsApiTester $I): void
    {
        // Arrange
        $this->authorizeCustomer($I);

        // Act
        $I->sendPATCH(
            $I->formatUrl(
                '{resourceCarts}//{resourceCartItems}/{cartItemSku}',
                [
                    'resourceCarts' => CartsRestApiConfig::RESOURCE_CARTS,
                    'resourceCartItems' => CartsRestApiConfig::RESOURCE_CART_ITEMS,
                    'cartItemSku' => $this->fixtures->getProductConcreteTransfer2()->getSku(),
                ]
            ),
            [
                'data' => [
                    'type' => CartsRestApiConfig::RESOURCE_CART_ITEMS,
                    'attributes' => [
                        'quantity' => $I::QUANTITY_FOR_ITEM_UPDATE,
                    ],
                ],
            ]
        );

        //assert
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
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
    public function requestUpdateItemsInCartWithoutAuthorizationToken(CartsApiTester $I): void
    {
        // Act
        $I->sendPATCH(
            $I->formatUrl(
                '{resourceCarts}/{cartUuid}/{resourceCartItems}/{itemSku}',
                [
                    'resourceCarts' => CartsRestApiConfig::RESOURCE_CARTS,
                    'cartUuid' => $this->fixtures->getQuoteTransfer()->getUuid(),
                    'resourceCartItems' => CartsRestApiConfig::RESOURCE_CART_ITEMS,
                    'itemSku' => $this->fixtures->getProductConcreteTransfer1()->getSku(),
                ]
            ),
            [
                'data' => [
                    'type' => CartsRestApiConfig::RESOURCE_CART_ITEMS,
                    'attributes' => [
                        'quantity' => $I::QUANTITY_FOR_ITEM_UPDATE,
                    ],
                ],
            ]
        );

        //assert
        $I->seeResponseCodeIs(HttpCode::FORBIDDEN);
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
    public function requestUpdateItemsInCartWithoutQuantity(CartsApiTester $I): void
    {
        // Arrange
        $this->authorizeCustomer($I);

        // Act
        $I->sendPATCH(
            $I->formatUrl(
                '{resourceCarts}/{cartUuid}/{resourceCartItems}/{itemSku}',
                [
                    'resourceCarts' => CartsRestApiConfig::RESOURCE_CARTS,
                    'cartUuid' => $this->fixtures->getQuoteTransfer()->getUuid(),
                    'resourceCartItems' => CartsRestApiConfig::RESOURCE_CART_ITEMS,
                    'itemSku' => $this->fixtures->getProductConcreteTransfer1()->getSku(),
                ]
            ),
            [
                'data' => [
                    'type' => CartsRestApiConfig::RESOURCE_CART_ITEMS,
                    'attributes' => [
                    ],
                ],
            ]
        );

        //assert
        $I->seeResponseCodeIs(HttpCode::UNPROCESSABLE_ENTITY);
        $I->seeResponseIsJson();
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\Carts\CartsApiTester $I
     *
     * @return void
     */
    public function requestUpdateItemsInCartWithoutItemSku(CartsApiTester $I): void
    {
        // Arrange
        $this->authorizeCustomer($I);

        // Act
        $I->sendPATCH(
            $I->formatUrl(
                '{resourceCarts}/{cartUuid}/{resourceCartItems}/',
                [
                    'resourceCarts' => CartsRestApiConfig::RESOURCE_CARTS,
                    'cartUuid' => $this->fixtures->getQuoteTransfer()->getUuid(),
                    'resourceCartItems' => CartsRestApiConfig::RESOURCE_CART_ITEMS,
                ]
            ),
            [
                'data' => [
                    'type' => CartsRestApiConfig::RESOURCE_CART_ITEMS,
                    'attributes' => [
                        'quantity' => $I::QUANTITY_FOR_ITEM_UPDATE,
                    ],
                ],
            ]
        );

        //assert
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseIsJson();
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\Carts\CartsApiTester $I
     *
     * @return void
     */
    public function requestDeleteItemsFromCart(CartsApiTester $I): void
    {
        // Arrange
        $this->authorizeCustomer($I);

        // Act
        $I->sendDelete(
            $I->formatUrl(
                '{resourceCarts}/{cartUuid}/{resourceCartItems}/{itemSku}',
                [
                    'resourceCarts' => CartsRestApiConfig::RESOURCE_CARTS,
                    'cartUuid' => $this->fixtures->getQuoteTransfer()->getUuid(),
                    'resourceCartItems' => CartsRestApiConfig::RESOURCE_CART_ITEMS,
                    'itemSku' => $this->fixtures->getProductConcreteTransfer1()->getSku(),
                ]
            )
        );

        //assert
        $I->seeResponseCodeIs(HttpCode::NO_CONTENT);
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\Carts\CartsApiTester $I
     *
     * @return void
     */
    public function requestDeleteItemsFromCartWithoutCartUuid(CartsApiTester $I): void
    {
        // Arrange
        $this->authorizeCustomer($I);

        // Act
        $I->sendDelete(
            $I->formatUrl(
                '{resourceCarts}//{resourceCartItems}/{itemSku}',
                [
                    'resourceCarts' => CartsRestApiConfig::RESOURCE_CARTS,
                    'resourceCartItems' => CartsRestApiConfig::RESOURCE_CART_ITEMS,
                    'itemSku' => $this->fixtures->getProductConcreteTransfer1()->getSku(),
                ]
            )
        );

        //assert
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseIsJson();
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\Carts\CartsApiTester $I
     *
     * @return void
     */
    public function requestDeleteItemsFromCartWithoutAuthorizationToken(CartsApiTester $I): void
    {
        // Act
        $I->sendDelete(
            $I->formatUrl(
                '{resourceCarts}/{cartUuid}/{resourceCartItems}/{itemSku}',
                [
                    'resourceCarts' => CartsRestApiConfig::RESOURCE_CARTS,
                    'cartUuid' => $this->fixtures->getQuoteTransfer()->getUuid(),
                    'resourceCartItems' => CartsRestApiConfig::RESOURCE_CART_ITEMS,
                    'itemSku' => $this->fixtures->getProductConcreteTransfer1()->getSku(),
                ]
            )
        );

        //assert
        $I->seeResponseCodeIs(HttpCode::FORBIDDEN);
        $I->seeResponseIsJson();
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\Carts\CartsApiTester $I
     *
     * @return void
     */
    public function requestDeleteItemsFromCartWithoutItemSku(CartsApiTester $I): void
    {
        // Arrange
        $this->authorizeCustomer($I);

        // Act
        $I->sendDelete(
            $I->formatUrl(
                '{resourceCarts}/{cartUuid}/{resourceCartItems}/',
                [
                    'resourceCarts' => CartsRestApiConfig::RESOURCE_CARTS,
                    'cartUuid' => $this->fixtures->getQuoteTransfer()->getUuid(),
                    'resourceCartItems' => CartsRestApiConfig::RESOURCE_CART_ITEMS,
                ]
            )
        );

        //assert
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseIsJson();
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\Carts\CartsApiTester $I
     *
     * @return void
     */
    public function requestDeleteCart(CartsApiTester $I): void
    {
        // Arrange
        $this->authorizeCustomer($I);

        // Act
        $I->sendDelete(
            $I->formatUrl(
                '{resourceCarts}/{cartUuid}',
                [
                    'resourceCarts' => CartsRestApiConfig::RESOURCE_CARTS,
                    'cartUuid' => $this->fixtures->getQuoteTransfer()->getUuid(),
                ]
            )
        );

        //assert
        $I->seeResponseCodeIs(HttpCode::NO_CONTENT);
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\Carts\CartsApiTester $I
     *
     * @return void
     */
    public function requestDeleteCartWithoutCartUuid(CartsApiTester $I): void
    {
        // Arrange
        $this->authorizeCustomer($I);

        // Act
        $I->sendDelete(
            $I->formatUrl(
                '{resourceCarts}/',
                [
                    'resourceCarts' => CartsRestApiConfig::RESOURCE_CARTS,
                ]
            )
        );

        //assert
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\Carts\CartsApiTester $I
     *
     * @return void
     */
    public function requestDeleteCartWithoutAuthorizationToken(CartsApiTester $I): void
    {
        // Act
        $I->sendDelete(
            $I->formatUrl(
                '{resourceCarts}/{cartUuid}',
                [
                    'resourceCarts' => CartsRestApiConfig::RESOURCE_CARTS,
                    'cartUuid' => $this->fixtures->getQuoteTransfer()->getUuid(),
                ]
            )
        );

        //assert
        $I->seeResponseCodeIs(HttpCode::FORBIDDEN);
    }

    /**
     * @param \PyzTest\Glue\Carts\CartsApiTester $I
     *
     * @return void
     */
    protected function authorizeCustomer(CartsApiTester $I): void
    {
        $token = $I->haveAuthorizationToGlue($this->fixtures->getCustomerTransfer())->getAccessToken();

        $I->amBearerAuthenticated($token);
    }
}
