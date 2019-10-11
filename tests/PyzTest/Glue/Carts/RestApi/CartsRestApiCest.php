<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Glue\Carts\RestApi;

use Codeception\Util\HttpCode;
use PyzTest\Glue\Carts\CartsApiTester;
use Spryker\Glue\CartsRestApi\CartsRestApiConfig;
use Spryker\Glue\GlueApplication\Rest\RequestConstantsInterface;
use Spryker\Shared\Calculation\CalculationPriceMode;

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
                        'priceMode' => CalculationPriceMode::PRICE_MODE_GROSS,
                        'currency' => $I::CURRENCY_EUR,
                        'store' => $I::STORE_DE,
                        'name' => $I::TEST_CART_NAME,
                    ],
                ],
            ]
        );

        // Assert
        $I->assertResponse(HttpCode::CREATED);

        $I->seeSingleResourceHasSelfLink(
            $I->formatFullUrl(
                '{resourceCarts}/{cartUuid}',
                [
                    'resourceCarts' => CartsRestApiConfig::RESOURCE_CARTS,
                    'cartUuid' => $I->findResourceIdFromResponseByJsonPath(),
                ]
            )
        );

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
                        'priceMode' => CalculationPriceMode::PRICE_MODE_GROSS,
                        'currency' => $I::CURRENCY_EUR,
                        'store' => $I::STORE_DE,
                        'name' => $I::TEST_CART_NAME,
                    ],
                ],
            ]
        );

        // Assert
        $I->assertResponse(HttpCode::FORBIDDEN);
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

        // Assert
        $I->assertResponse(HttpCode::UNPROCESSABLE_ENTITY);
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
                        'priceMode' => CalculationPriceMode::PRICE_MODE_GROSS,
                        'store' => $I::STORE_DE,
                        'name' => $I::TEST_CART_NAME,
                    ],
                ],
            ]
        );

        // Assert
        $I->assertResponse(HttpCode::UNPROCESSABLE_ENTITY);
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\Carts\CartsApiTester $I
     *
     * @return void
     */
    public function requestGetCarts(CartsApiTester $I): void
    {
        // Arrange
        $this->authorizeCustomer($I);

        // Act
        $I->sendGET(CartsRestApiConfig::RESOURCE_CARTS);

        // Assert
        $I->assertResponse(HttpCode::OK);
        $I->seeResponseDataContainsNonEmptyCollection();
        $I->seeResourceCollectionHasResourceWithId($this->fixtures->getQuoteTransfer()->getUuid());
        $I->canSeeResponseLinksContainsSelfLink($I->formatFullUrl(CartsRestApiConfig::RESOURCE_CARTS));
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\Carts\CartsApiTester $I
     *
     * @return void
     */
    public function requestGetCart(CartsApiTester $I): void
    {
        // Arrange
        $this->authorizeCustomer($I);
        $quoteUuid = $this->fixtures->getQuoteTransfer()->getUuid();

        // Act
        $I->sendGET(
            $I->formatUrl(
                '{resourceCarts}/{cartUuid}',
                [
                    'resourceCarts' => CartsRestApiConfig::RESOURCE_CARTS,
                    'cartUuid' => $quoteUuid,
                ]
            )
        );

        // Assert
        $I->assertResponse(HttpCode::OK);

        $I->amSure(sprintf('Returned resource is of type %s', CartsRestApiConfig::RESOURCE_CARTS))
            ->whenI()
            ->seeResponseDataContainsSingleResourceOfType(CartsRestApiConfig::RESOURCE_CARTS);

        $I->amSure('Returned resource has correct id')
            ->whenI()
            ->seeSingleResourceIdEqualTo($quoteUuid);

        $I->seeSingleResourceHasSelfLink(
            $I->formatFullUrl(
                '{resourceCarts}/{cartUuid}',
                [
                    'resourceCarts' => CartsRestApiConfig::RESOURCE_CARTS,
                    'cartUuid' => $quoteUuid,
                ]
            )
        );
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\Carts\CartsApiTester $I
     *
     * @return void
     */
    public function requestGetCartWithNonExistingUuid(CartsApiTester $I): void
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
    public function requestGetCartWithoutAuthorizationToken(CartsApiTester $I): void
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

        // Assert
        $I->assertResponse(HttpCode::FORBIDDEN);
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\Carts\CartsApiTester $I
     *
     * @return void
     */
    public function requestGetCartWithItemRelationship(CartsApiTester $I): void
    {
        // Arrange
        $this->authorizeCustomer($I);
        $quoteUuid = $this->fixtures->getQuoteTransfer()->getUuid();

        // Act
        $I->sendGET(
            $I->formatUrl(
                '{resourceCarts}/{cartUuid}?include={items}',
                [
                    'resourceCarts' => CartsRestApiConfig::RESOURCE_CARTS,
                    'cartUuid' => $quoteUuid,
                    'items' => CartsRestApiConfig::RESOURCE_CART_ITEMS,
                ]
            )
        );

        // Assert
        $I->assertResponse(HttpCode::OK);

        $I->amSure('Returned resource has correct id')
            ->whenI()
            ->seeSingleResourceIdEqualTo($quoteUuid);

        $I->amSure(sprintf('Returned resource is of type %s', CartsRestApiConfig::RESOURCE_CARTS))
            ->whenI()
            ->seeResponseDataContainsSingleResourceOfType(CartsRestApiConfig::RESOURCE_CARTS);

        $I->amSure('Returned resource has include of type items')
            ->whenI()
            ->seeSingleResourceHasRelationshipByTypeAndId(
                CartsRestApiConfig::RESOURCE_CART_ITEMS,
                $this->fixtures->getProductConcreteTransfer1()->getSku()
            );

        $I->amSure('Returned resource has include of type items')
            ->whenI()
            ->seeIncludesContainsResourceByTypeAndId(
                CartsRestApiConfig::RESOURCE_CART_ITEMS,
                $this->fixtures->getProductConcreteTransfer1()->getSku()
            );

        $I->seeSingleResourceHasSelfLink(
            $I->formatFullUrl(
                '{resourceCarts}/{cartUuid}?include={items}',
                [
                    'resourceCarts' => CartsRestApiConfig::RESOURCE_CARTS,
                    'cartUuid' => $quoteUuid,
                    'items' => CartsRestApiConfig::RESOURCE_CART_ITEMS,
                ]
            )
        );
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
        $entityTag = $this->fixtures->getEmptyCartResourceEntityTag();
        $I->haveHttpHeader(RequestConstantsInterface::HEADER_IF_MATCH, $entityTag);
        $emptyQuoteUuid = $emptyQuoteTransfer->getUuid();

        // Act
        $I->sendPATCH(
            $I->formatUrl(
                '{resourceCarts}/{cartUuid}',
                [
                    'resourceCarts' => CartsRestApiConfig::RESOURCE_CARTS,
                    'cartUuid' => $emptyQuoteUuid,
                ]
            ),
            [
                'data' => [
                    'type' => CartsRestApiConfig::RESOURCE_CARTS,
                    'attributes' => [
                        'name' => $I::TEST_CART_NAME,
                        'currency' => $I::CURRENCY_EUR,
                        'priceMode' => CalculationPriceMode::PRICE_MODE_GROSS,
                    ],
                ],
            ]
        );

        // Assert
        $I->assertResponse(HttpCode::OK);

        $I->amSure('Returned resource has correct id')
            ->whenI()
            ->seeSingleResourceIdEqualTo($emptyQuoteUuid);

        $I->amSure(sprintf('Returned resource is of type %s', CartsRestApiConfig::RESOURCE_CARTS))
            ->whenI()
            ->seeResponseDataContainsSingleResourceOfType(CartsRestApiConfig::RESOURCE_CARTS);

        $I->seeSingleResourceHasSelfLink(
            $I->formatFullUrl(
                '{resourceCarts}/{cartUuid}',
                [
                    'resourceCarts' => CartsRestApiConfig::RESOURCE_CARTS,
                    'cartUuid' => $emptyQuoteUuid,
                ]
            )
        );
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
        $entityTag = $this->fixtures->getCartResourceEntityTag();

        $I->haveHttpHeader(RequestConstantsInterface::HEADER_IF_MATCH, $entityTag);

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
                        'priceMode' => CalculationPriceMode::PRICE_MODE_GROSS,
                    ],
                ],
            ]
        );

        // Assert
        $I->assertResponse(HttpCode::UNPROCESSABLE_ENTITY);
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
        $entityTag = $this->fixtures->getCartResourceEntityTag();
        $I->haveHttpHeader(RequestConstantsInterface::HEADER_IF_MATCH, $entityTag);

        // Act
        $I->sendPATCH(
            CartsRestApiConfig::RESOURCE_CARTS,
            [
                'data' => [
                    'type' => CartsRestApiConfig::RESOURCE_CARTS,
                    'attributes' => [
                        'name' => $I::TEST_CART_NAME,
                        'currency' => $I::CURRENCY_EUR,
                        'priceMode' => CalculationPriceMode::PRICE_MODE_GROSS,
                    ],
                ],
            ]
        );

        // Assert
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
                        'priceMode' => CalculationPriceMode::PRICE_MODE_GROSS,
                    ],
                ],
            ]
        );

        // Assert
        $I->assertResponse(HttpCode::FORBIDDEN);
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
        $quoteUuid = $this->fixtures->getQuoteTransfer()->getUuid();

        // Act
        $I->sendPOST(
            $I->formatUrl(
                '{resourceCarts}/{cartUuid}/{resourceCartItems}',
                [
                    'resourceCarts' => CartsRestApiConfig::RESOURCE_CARTS,
                    'cartUuid' => $quoteUuid,
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

        // Assert
        $I->assertResponse(HttpCode::CREATED);

        $I->amSure('Returned resource has correct id')
            ->whenI()
            ->seeSingleResourceIdEqualTo($quoteUuid);

        $I->amSure(sprintf('Returned resource is of type %s', CartsRestApiConfig::RESOURCE_CARTS))
            ->whenI()
            ->seeResponseDataContainsSingleResourceOfType(CartsRestApiConfig::RESOURCE_CARTS);

        $I->amSure('Returned resource has include of type items')
            ->whenI()
            ->seeIncludesContainsResourceByTypeAndId(
                CartsRestApiConfig::RESOURCE_CART_ITEMS,
                $this->fixtures->getProductConcreteTransfer1()->getSku()
            );

        $I->seeSingleResourceHasSelfLink(
            $I->formatFullUrl(
                '{resourceCarts}/{cartUuid}',
                [
                    'resourceCarts' => CartsRestApiConfig::RESOURCE_CARTS,
                    'cartUuid' => $I->findResourceIdFromResponseByJsonPath(),
                ]
            )
        );
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

        // Assert
        $I->assertResponse(HttpCode::FORBIDDEN);
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

        // Assert
        $I->assertResponse(HttpCode::UNPROCESSABLE_ENTITY);
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

        // Assert
        $I->assertResponse(HttpCode::UNPROCESSABLE_ENTITY);
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

        // Assert
        $I->assertResponse(HttpCode::OK);

        $I->amSure(sprintf('Returned resource is of type %s', CartsRestApiConfig::RESOURCE_CARTS))
            ->whenI()
            ->seeResponseDataContainsSingleResourceOfType(CartsRestApiConfig::RESOURCE_CARTS);

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

        // Assert
        $I->assertResponse(HttpCode::BAD_REQUEST);
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

        // Assert
        $I->assertResponse(HttpCode::FORBIDDEN);
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

        // Assert
        $I->assertResponse(HttpCode::UNPROCESSABLE_ENTITY);
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
                        'quantity' => $I::QUANTITY_FOR_ITEM_UPDATE,
                    ],
                ],
            ]
        );

        // Assert
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

        // Assert
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

        // Assert
        $I->assertResponse(HttpCode::BAD_REQUEST);
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

        // Assert
        $I->assertResponse(HttpCode::FORBIDDEN);
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
                '{resourceCarts}/{cartUuid}/{resourceCartItems}',
                [
                    'resourceCarts' => CartsRestApiConfig::RESOURCE_CARTS,
                    'cartUuid' => $this->fixtures->getQuoteTransfer()->getUuid(),
                    'resourceCartItems' => CartsRestApiConfig::RESOURCE_CART_ITEMS,
                ]
            )
        );

        // Assert
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

        // Assert
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
                '{resourceCarts}',
                [
                    'resourceCarts' => CartsRestApiConfig::RESOURCE_CARTS,
                ]
            )
        );

        // Assert
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

        // Assert
        $I->assertResponse(HttpCode::FORBIDDEN);
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
