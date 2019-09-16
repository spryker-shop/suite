<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Glue\Carts\RestApi;

use Codeception\Util\HttpCode;
use PyzTest\Glue\Carts\CartsApiTester;
use Spryker\Glue\AuthRestApi\AuthRestApiConfig;
use Spryker\Glue\CartsRestApi\CartsRestApiConfig;

/**
 * Auto-generated group annotations
 * @group PyzTest
 * @group Glue
 * @group Carts
 * @group RestApi
 * @group CartRestApiCest
 * @group MultiCartRestApiCest
 * Add your own group annotations below this line
 * @group EndToEnd
 */
class MultiCartsRestApiCest
{
    protected const VALUE_FOR_ANONYMOUS = '666';

    protected const GROSS_MODE = 'GROSS_MODE';

    protected const CART_NAME = 'Test Cart Name';

    protected const CURRENCY = 'EUR';

    protected const STORE_DE = 'DE';

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
        $I->amUnauthorizedGlueUser(static::VALUE_FOR_ANONYMOUS);

        // Act
        $I->sendPOST(
            CartsRestApiConfig::RESOURCE_GUEST_CARTS_ITEMS,
            [
                'data' => [
                    'type' => CartsRestApiConfig::RESOURCE_GUEST_CARTS_ITEMS,
                    'attributes' => [
                        'sku' => $this->fixtures->getProductConcreteTransfer()->getSku(),
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
    public function requestCreateCart(CartsApiTester $I): void
    {
        // Arrange
        $token = $I->haveAuthorizationToGlue($this->fixtures->getCustomerTransfer())['accessToken'];
        $I->amAuthorizedGlueUser($token);

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
        $token = $I->haveAuthorizationToGlue($this->fixtures->getCustomerTransfer())['accessToken'];
        $I->amAuthorizedGlueUser($token);

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
        $token = $I->haveAuthorizationToGlue($this->fixtures->getCustomerTransfer())['accessToken'];
        $I->amAuthorizedGlueUser($token);

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
    public function requestCreateGuestCartWithoutAnonymousCustomerUniqueId(CartsApiTester $I): void
    {
        // Act
        $I->sendPOST(
            CartsRestApiConfig::RESOURCE_GUEST_CARTS_ITEMS,
            [
                'data' => [
                    'type' => CartsRestApiConfig::RESOURCE_GUEST_CARTS_ITEMS,
                    'attributes' => [
                        'sku' => $this->fixtures->getProductConcreteTransfer()->getSku(),
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
        $I->amUnauthorizedGlueUser(static::VALUE_FOR_ANONYMOUS);

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
        $I->amUnauthorizedGlueUser(static::VALUE_FOR_ANONYMOUS);

        // Act
        $I->sendPOST(
            CartsRestApiConfig::RESOURCE_GUEST_CARTS_ITEMS,
            [
                'data' => [
                    'type' => CartsRestApiConfig::RESOURCE_GUEST_CARTS_ITEMS,
                    'attributes' => [
                        'sku' => $this->fixtures->getProductConcreteTransfer()->getSku(),
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
    public function requestFindCartByUuid(CartsApiTester $I): void
    {
        // Arrange
        $token = $I->haveAuthorizationToGlue($this->fixtures->getCustomerTransfer())['accessToken'];
        $I->amAuthorizedGlueUser($token);

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

        $I->seeResponseMatchesJsonPath('$.data[*]');
    }
}
