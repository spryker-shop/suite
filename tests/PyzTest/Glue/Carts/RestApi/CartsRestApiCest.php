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
    protected const VALUE_FOR_ANONYMOUS = '666';

    protected const GROSS_MODE = 'GROSS_MODE';

    protected const CART_NAME = 'Test Cart Name';

    protected const CURRENCY = 'EUR';

    protected const STORE_DE = 'DE';

    protected const TEST_QUANTITY_FOR_ITEM_UPDATE = 33;

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
    public function requestCreateGuestCart(CartsApiTester $I): void
    {
        // Arrange
        $I->haveHttpHeader('X-Anonymous-Customer-Unique-Id', static::VALUE_FOR_ANONYMOUS);

        // Act
        $I->sendPOST(
            CartsRestApiConfig::RESOURCE_GUEST_CARTS_ITEMS,
            [
                'data' => [
                    'type' => CartsRestApiConfig::RESOURCE_GUEST_CARTS_ITEMS,
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

        $I->amSure('Returned resource is of type guest-carts')
            ->whenI()
            ->seeResponseDataContainsSingleResourceOfType('guest-carts');
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\Carts\CartsApiTester $I
     *
     * @return void
     */
    public function requestCreateGuestCartWhenGuestCartAlreadyExists(CartsApiTester $I): void
    {
        $this->requestCreateGuestCart($I);
    }


    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\Carts\CartsApiTester $I
     *
     * @return void
     */
    public function requestCreateGuestCartWithoutAnonymousCustomerUniqueId(CartsApiTester $I): void
    {
        // Act
        $I->sendPOST(
            CartsRestApiConfig::RESOURCE_GUEST_CARTS_ITEMS,
            [
                'data' => [
                    'type' => CartsRestApiConfig::RESOURCE_GUEST_CARTS_ITEMS,
                    'attributes' => [
                        'sku' => $this->fixtures->getProductConcreteTransfer1()->getSku(),
                        'quantity' => 1,
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
    public function requestCreateGuestCartWithoutSku(CartsApiTester $I): void
    {
        $I->haveHttpHeader('X-Anonymous-Customer-Unique-Id', static::VALUE_FOR_ANONYMOUS);

        // Act
        $I->sendPOST(
            CartsRestApiConfig::RESOURCE_GUEST_CARTS_ITEMS,
            [
                'data' => [
                    'type' => CartsRestApiConfig::RESOURCE_GUEST_CARTS_ITEMS,
                    'attributes' => [
                        'quantity' => 1,
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
    public function requestCreateGuestCartWithoutQuantity(CartsApiTester $I): void
    {
        $I->haveHttpHeader('X-Anonymous-Customer-Unique-Id', static::VALUE_FOR_ANONYMOUS);

        // Act
        $I->sendPOST(
            CartsRestApiConfig::RESOURCE_GUEST_CARTS_ITEMS,
            [
                'data' => [
                    'type' => CartsRestApiConfig::RESOURCE_GUEST_CARTS_ITEMS,
                    'attributes' => [
                        'sku' => $this->fixtures->getProductConcreteTransfer1()->getSku(),
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
    public function requestAddItemsToGuestCart(CartsApiTester $I): void
    {
        // Arrange
        $I->haveHttpHeader('X-Anonymous-Customer-Unique-Id', static::VALUE_FOR_ANONYMOUS);
        $I->sendGET(CartsRestApiConfig::RESOURCE_GUEST_CARTS);
        $guestCartUuid = $I->grabDataFromResponseByJsonPath('$.data[0]')[0]['id'];

        // Act
        $I->sendPOST($I->formatUrl(
            '{resourceGuestCarts}/{guestCartUuid}/{resourceGuestCartItems}',
            [
                'resourceGuestCarts' => CartsRestApiConfig::RESOURCE_GUEST_CARTS,
                'guestCartUuid' => $guestCartUuid,
                'resourceGuestCartItems' => CartsRestApiConfig::RESOURCE_GUEST_CARTS_ITEMS,
            ]
        ),
            [
                'data' => [
                    'type' => CartsRestApiConfig::RESOURCE_GUEST_CARTS_ITEMS,
                    'attributes' => [
                        'sku' => $this->fixtures->getProductConcreteTransfer2()->getSku(),
                        'quantity' => 1,
                    ],
                ],
            ]
        );

        //assert
        $I->seeResponseCodeIs(HttpCode::CREATED);
        $I->seeResponseIsJson();

        $I->amSure('Returned resource is of type guest-carts')
            ->whenI()
            ->seeResponseDataContainsSingleResourceOfType('guest-carts');
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\Carts\CartsApiTester $I
     *
     * @return void
     */
    public function requestAddItemsToGuestCartWithoutAnonymousCustomerUniqueId(CartsApiTester $I): void
    {
        // Arrange
        $I->sendGET(CartsRestApiConfig::RESOURCE_GUEST_CARTS);
        $guestCartUuid = $I->grabDataFromResponseByJsonPath('$.data[0]')[0]['id'];

        // Act
        $I->sendPOST($I->formatUrl(
            '{resourceGuestCarts}/{guestCartUuid}/{resourceGuestCartItems}',
            [
                'resourceGuestCarts' => CartsRestApiConfig::RESOURCE_GUEST_CARTS,
                'guestCartUuid' => $guestCartUuid,
                'resourceGuestCartItems' => CartsRestApiConfig::RESOURCE_GUEST_CARTS_ITEMS,
            ]
        ),
            [
                'data' => [
                    'type' => CartsRestApiConfig::RESOURCE_GUEST_CARTS_ITEMS,
                    'attributes' => [
                        'sku' => $this->fixtures->getProductConcreteTransfer2()->getSku(),
                        'quantity' => 1,
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
    public function requestAddItemsToGuestCartWithoutItemSku(CartsApiTester $I): void
    {
        // Arrange
        $I->haveHttpHeader('X-Anonymous-Customer-Unique-Id', static::VALUE_FOR_ANONYMOUS);
        $I->sendGET(CartsRestApiConfig::RESOURCE_GUEST_CARTS);
        $guestCartUuid = $I->grabDataFromResponseByJsonPath('$.data[0]')[0]['id'];

        // Act
        $I->sendPOST($I->formatUrl(
            '{resourceGuestCarts}/{guestCartUuid}/{resourceGuestCartItems}',
            [
                'resourceGuestCarts' => CartsRestApiConfig::RESOURCE_GUEST_CARTS,
                'guestCartUuid' => $guestCartUuid,
                'resourceGuestCartItems' => CartsRestApiConfig::RESOURCE_GUEST_CARTS_ITEMS,
            ]
        ),
            [
                'data' => [
                    'type' => CartsRestApiConfig::RESOURCE_GUEST_CARTS_ITEMS,
                    'attributes' => [
                        'quantity' => 1,
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
    public function requestAddItemsToGuestCartWithoutItemQuantity(CartsApiTester $I): void
    {
        // Arrange
        $I->haveHttpHeader('X-Anonymous-Customer-Unique-Id', static::VALUE_FOR_ANONYMOUS);
        $I->sendGET(CartsRestApiConfig::RESOURCE_GUEST_CARTS);
        $guestCartUuid = $I->grabDataFromResponseByJsonPath('$.data[0]')[0]['id'];

        // Act
        $I->sendPOST($I->formatUrl(
            '{resourceGuestCarts}/{guestCartUuid}/{resourceGuestCartItems}',
            [
                'resourceGuestCarts' => CartsRestApiConfig::RESOURCE_GUEST_CARTS,
                'guestCartUuid' => $guestCartUuid,
                'resourceGuestCartItems' => CartsRestApiConfig::RESOURCE_GUEST_CARTS_ITEMS,
            ]
        ),
            [
                'data' => [
                    'type' => CartsRestApiConfig::RESOURCE_GUEST_CARTS_ITEMS,
                    'attributes' => [
                        'sku' => $this->fixtures->getProductConcreteTransfer2()->getSku(),
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
    public function requestUpdateItemsInGuestCart(CartsApiTester $I): void
    {
        // Arrange
        $I->haveHttpHeader('X-Anonymous-Customer-Unique-Id', static::VALUE_FOR_ANONYMOUS);
        $I->sendGET(CartsRestApiConfig::RESOURCE_GUEST_CARTS);
        $guestCartUuid = $I->grabDataFromResponseByJsonPath('$.data[0]')[0]['id'];

        // Act
        $I->sendPATCH($I->formatUrl(
            '{resourceGuestCarts}/{guestCartUuid}/{resourceGuestCartItems}/{guestCartItemSku}',
            [
                'resourceGuestCarts' => CartsRestApiConfig::RESOURCE_GUEST_CARTS,
                'guestCartUuid' => $guestCartUuid,
                'resourceGuestCartItems' => CartsRestApiConfig::RESOURCE_GUEST_CARTS_ITEMS,
                'guestCartItemSku' => $this->fixtures->getProductConcreteTransfer2()->getSku(),
            ]
        ),
            [
                'data' => [
                    'type' => CartsRestApiConfig::RESOURCE_GUEST_CARTS_ITEMS,
                    'attributes' => [
                        'quantity' => static::TEST_QUANTITY_FOR_ITEM_UPDATE,
                    ],
                ],
            ]
        );

        //assert
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();

        $I->amSure('Returned resource is of type guest-carts')
            ->whenI()
            ->seeResponseDataContainsSingleResourceOfType('guest-carts');
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\Carts\CartsApiTester $I
     *
     * @return void
     */
    public function requestUpdateItemsInGuestCartWithoutAnonymousCustomerUniqueId(CartsApiTester $I): void
    {
        // Arrange
        $I->sendGET(CartsRestApiConfig::RESOURCE_GUEST_CARTS);
        $guestCartUuid = $I->grabDataFromResponseByJsonPath('$.data[0]')[0]['id'];

        // Act
        $I->sendPATCH($I->formatUrl(
            '{resourceGuestCarts}/{guestCartUuid}/{resourceGuestCartItems}/{guestCartItemSku}',
            [
                'resourceGuestCarts' => CartsRestApiConfig::RESOURCE_GUEST_CARTS,
                'guestCartUuid' => $guestCartUuid,
                'resourceGuestCartItems' => CartsRestApiConfig::RESOURCE_GUEST_CARTS_ITEMS,
                'guestCartItemSku' => $this->fixtures->getProductConcreteTransfer2()->getSku(),
            ]
        ),
            [
                'data' => [
                    'type' => CartsRestApiConfig::RESOURCE_GUEST_CARTS_ITEMS,
                    'attributes' => [
                        'quantity' => static::TEST_QUANTITY_FOR_ITEM_UPDATE,
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
    public function requestUpdateItemsInGuestCartWithoutQuantity(CartsApiTester $I): void
    {
        // Arrange
        $I->haveHttpHeader('X-Anonymous-Customer-Unique-Id', static::VALUE_FOR_ANONYMOUS);
        $I->sendGET(CartsRestApiConfig::RESOURCE_GUEST_CARTS);
        $guestCartUuid = $I->grabDataFromResponseByJsonPath('$.data[0]')[0]['id'];

        // Act
        $I->sendPATCH($I->formatUrl(
            '{resourceGuestCarts}/{guestCartUuid}/{resourceGuestCartItems}/{guestCartItemSku}',
            [
                'resourceGuestCarts' => CartsRestApiConfig::RESOURCE_GUEST_CARTS,
                'guestCartUuid' => $guestCartUuid,
                'resourceGuestCartItems' => CartsRestApiConfig::RESOURCE_GUEST_CARTS_ITEMS,
                'guestCartItemSku' => $this->fixtures->getProductConcreteTransfer2()->getSku(),
            ]
        ),
            [
                'data' => [
                    'type' => CartsRestApiConfig::RESOURCE_GUEST_CARTS_ITEMS,
                    'attributes' => [
                    ],
                ],
            ]
        );

        //assert
        $I->seeResponseCodeIs(HttpCode::UNPROCESSABLE_ENTITY);
        $I->seeResponseIsJson();

        $I->amSure('Returned resource is of type guest-carts')
            ->whenI()
            ->seeResponseDataContainsSingleResourceOfType('guest-carts');
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\Carts\CartsApiTester $I
     *
     * @return void
     */
    public function requestUpdateItemsInGuestCartWithoutCartUuid(CartsApiTester $I): void
    {
        // Arrange
        $I->haveHttpHeader('X-Anonymous-Customer-Unique-Id', static::VALUE_FOR_ANONYMOUS);
        $I->sendGET(CartsRestApiConfig::RESOURCE_GUEST_CARTS);

        // Act
        $I->sendPATCH($I->formatUrl(
            '{resourceGuestCarts}/{guestCartUuid}/{resourceGuestCartItems}/{guestCartItemSku}',
            [
                'resourceGuestCarts' => CartsRestApiConfig::RESOURCE_GUEST_CARTS,
                'guestCartUuid' => '',
                'resourceGuestCartItems' => CartsRestApiConfig::RESOURCE_GUEST_CARTS_ITEMS,
                'guestCartItemSku' => $this->fixtures->getProductConcreteTransfer2()->getSku(),
            ]
        ),
            [
                'data' => [
                    'type' => CartsRestApiConfig::RESOURCE_GUEST_CARTS_ITEMS,
                    'attributes' => [
                        'quantity' => static::TEST_QUANTITY_FOR_ITEM_UPDATE,
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
    public function requestUpdateItemsInGuestCartWithout(CartsApiTester $I): void
    {
        // Arrange
        $I->haveHttpHeader('X-Anonymous-Customer-Unique-Id', static::VALUE_FOR_ANONYMOUS);
        $I->sendGET(CartsRestApiConfig::RESOURCE_GUEST_CARTS);
        $guestCartUuid = $I->grabDataFromResponseByJsonPath('$.data[0]')[0]['id'];

        // Act
        $I->sendPATCH($I->formatUrl(
            '{resourceGuestCarts}/{guestCartUuid}/{resourceGuestCartItems}/{guestCartItemSku}',
            [
                'resourceGuestCarts' => CartsRestApiConfig::RESOURCE_GUEST_CARTS,
                'guestCartUuid' => $guestCartUuid,
                'resourceGuestCartItems' => CartsRestApiConfig::RESOURCE_GUEST_CARTS_ITEMS,
                'guestCartItemSku' => $this->fixtures->getProductConcreteTransfer2()->getSku(),
            ]
        ),
            [
                'data' => [
                    'type' => CartsRestApiConfig::RESOURCE_GUEST_CARTS_ITEMS,
                    'attributes' => [
                        'quantity' => static::TEST_QUANTITY_FOR_ITEM_UPDATE,
                    ],
                ],
            ]
        );

        //assert
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();

        $I->amSure('Returned resource is of type guest-carts')
            ->whenI()
            ->seeResponseDataContainsSingleResourceOfType('guest-carts');
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
                        'priceMode' => static::GROSS_MODE,
                        'currency' => static::CURRENCY,
                        'store' => static::STORE_DE,
                        'name' => static::CART_NAME,
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
                        'priceMode' => static::GROSS_MODE,
                        'currency' => static::CURRENCY,
                        'store' => static::STORE_DE,
                        'name' => static::CART_NAME,
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
                        'currency' => static::CURRENCY,
                        'store' => static::STORE_DE,
                        'name' => static::CART_NAME,
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
                        'priceMode' => static::GROSS_MODE,
                        'store' => static::STORE_DE,
                        'name' => static::CART_NAME,
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
     * @param \PyzTest\Glue\Carts\CartsApiTester $I
     *
     * @return void
     */
    protected function authorizeCustomer(CartsApiTester $I): void
    {
        $token = $I->haveAuthorizationToGlue(
            $this->fixtures->getCustomerTransfer(),
            'anonymous:' . static::VALUE_FOR_ANONYMOUS)
            ->getAccessToken();

        $I->amBearerAuthenticated($token);
    }
}
