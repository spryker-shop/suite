<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Glue\Carts\RestApi;

use Codeception\Util\HttpCode;
use Generated\Shared\Transfer\CustomerTransfer;
use PyzTest\Glue\Carts\CartsApiTester;
use PyzTest\Glue\Carts\RestApi\Fixtures\GuestCartsRestApiFixtures;
use Spryker\Glue\CartsRestApi\CartsRestApiConfig;

/**
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Glue
 * @group Carts
 * @group RestApi
 * @group GuestCartsRestApiCest
 * Add your own group annotations below this line
 * @group EndToEnd
 */
class GuestCartsRestApiCest
{
    /**
     * @var \PyzTest\Glue\Carts\RestApi\Fixtures\GuestCartsRestApiFixtures
     */
    protected $fixtures;

    /**
     * @param \PyzTest\Glue\Carts\CartsApiTester $I
     *
     * @return void
     */
    public function loadFixtures(CartsApiTester $I): void
    {
        /** @var \PyzTest\Glue\Carts\RestApi\Fixtures\GuestCartsRestApiFixtures $fixtures */
        $fixtures = $I->loadFixtures(GuestCartsRestApiFixtures::class);

        $this->fixtures = $fixtures;
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
        $I->haveHttpHeader(
            CartsRestApiConfig::HEADER_ANONYMOUS_CUSTOMER_UNIQUE_ID,
            $I->createGuestCustomerReference(),
        );

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
            ],
        );

        // Assert
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
        $I->haveHttpHeader(
            CartsRestApiConfig::HEADER_ANONYMOUS_CUSTOMER_UNIQUE_ID,
            $I->createGuestCustomerReference(),
        );

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
            ],
        );

        // Assert
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
    public function requestGetGuestCartWithoutAnonymousCustomerUniqueId(CartsApiTester $I): void
    {
        // Act
        $I->sendGET(
            $I->formatUrl(
                '{resourceGuestCarts}',
                [
                    'resourceGuestCarts' => CartsRestApiConfig::RESOURCE_GUEST_CARTS,
                ],
            ),
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
    public function requestAddItemsToGuestCart(CartsApiTester $I): void
    {
        // Arrange
        $guestCustomerReference = $I->createGuestCustomerReference();
        $I->haveHttpHeader(
            CartsRestApiConfig::HEADER_ANONYMOUS_CUSTOMER_UNIQUE_ID,
            $guestCustomerReference,
        );
        $quoteTransfer = $I->createPersistentQuote(
            $I,
            (new CustomerTransfer())->setCustomerReference(GuestCartsRestApiFixtures::ANONYMOUS_PREFIX . $guestCustomerReference),
            [],
        );
        $guestQuoteUuid = $quoteTransfer->getUuid();

        // Act
        $I->sendPOST(
            $I->formatUrl(
                '{resourceGuestCarts}/{guestCartUuid}/{resourceGuestCartItems}',
                [
                    'resourceGuestCarts' => CartsRestApiConfig::RESOURCE_GUEST_CARTS,
                    'guestCartUuid' => $guestQuoteUuid,
                    'resourceGuestCartItems' => CartsRestApiConfig::RESOURCE_GUEST_CARTS_ITEMS,
                ],
            ),
            [
                'data' => [
                    'type' => CartsRestApiConfig::RESOURCE_GUEST_CARTS_ITEMS,
                    'attributes' => [
                        'sku' => $this->fixtures->getProductConcreteTransfer1()->getSku(),
                        'quantity' => 1,
                    ],
                ],
            ],
        );

        // Assert
        $I->seeResponseCodeIs(HttpCode::CREATED);
        $I->seeResponseMatchesOpenApiSchema();

        $I->amSure('Returned resource has correct id')
            ->whenI()
            ->seeSingleResourceIdEqualTo($guestQuoteUuid);

        $I->amSure(sprintf('Returned resource is of type %s', CartsRestApiConfig::RESOURCE_GUEST_CARTS))
            ->whenI()
            ->seeResponseDataContainsSingleResourceOfType(CartsRestApiConfig::RESOURCE_GUEST_CARTS);

        $I->seeSingleResourceHasSelfLink(
            $I->formatFullUrl(
                '{resourceGuestCarts}/{guestCartUuid}',
                [
                    'resourceGuestCarts' => CartsRestApiConfig::RESOURCE_GUEST_CARTS,
                    'guestCartUuid' => $guestQuoteUuid,
                ],
            ),
        );
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
        $quoteTransfer = $I->createPersistentQuote(
            $I,
            (new CustomerTransfer())->setCustomerReference(GuestCartsRestApiFixtures::ANONYMOUS_PREFIX . $I->createGuestCustomerReference()),
            [],
        );
        $guestQuoteUuid = $quoteTransfer->getUuid();

        // Act
        $I->sendPOST(
            $I->formatUrl(
                '{resourceGuestCarts}/{guestCartUuid}/{resourceGuestCartItems}',
                [
                    'resourceGuestCarts' => CartsRestApiConfig::RESOURCE_GUEST_CARTS,
                    'guestCartUuid' => $guestQuoteUuid,
                    'resourceGuestCartItems' => CartsRestApiConfig::RESOURCE_GUEST_CARTS_ITEMS,
                ],
            ),
            [
                'data' => [
                    'type' => CartsRestApiConfig::RESOURCE_GUEST_CARTS_ITEMS,
                    'attributes' => [
                        'sku' => $this->fixtures->getProductConcreteTransfer2()->getSku(),
                        'quantity' => 1,
                    ],
                ],
            ],
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
    public function requestAddItemsToGuestCartWithoutItemSku(CartsApiTester $I): void
    {
        // Arrange
        $guestCustomerReference = $I->createGuestCustomerReference();
        $quoteTransfer = $I->createPersistentQuote(
            $I,
            (new CustomerTransfer())->setCustomerReference(GuestCartsRestApiFixtures::ANONYMOUS_PREFIX . $guestCustomerReference),
            [$this->fixtures->getProductConcreteTransfer1()],
        );
        $guestQuoteUuid = $quoteTransfer->getUuid();

        $I->haveHttpHeader(
            CartsRestApiConfig::HEADER_ANONYMOUS_CUSTOMER_UNIQUE_ID,
            $guestCustomerReference,
        );

        // Act
        $I->sendPOST(
            $I->formatUrl(
                '{resourceGuestCarts}/{guestCartUuid}/{resourceGuestCartItems}',
                [
                    'resourceGuestCarts' => CartsRestApiConfig::RESOURCE_GUEST_CARTS,
                    'guestCartUuid' => $guestQuoteUuid,
                    'resourceGuestCartItems' => CartsRestApiConfig::RESOURCE_GUEST_CARTS_ITEMS,
                ],
            ),
            [
                'data' => [
                    'type' => CartsRestApiConfig::RESOURCE_GUEST_CARTS_ITEMS,
                    'attributes' => [
                        'quantity' => 1,
                    ],
                ],
            ],
        );

        // Assert
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
        $guestCustomerReference = $I->createGuestCustomerReference();
        $quoteTransfer = $I->createPersistentQuote(
            $I,
            (new CustomerTransfer())->setCustomerReference(GuestCartsRestApiFixtures::ANONYMOUS_PREFIX . $guestCustomerReference),
            [$this->fixtures->getProductConcreteTransfer1()],
        );
        $guestQuoteUuid = $quoteTransfer->getUuid();
        $I->haveHttpHeader(
            CartsRestApiConfig::HEADER_ANONYMOUS_CUSTOMER_UNIQUE_ID,
            $guestCustomerReference,
        );

        // Act
        $I->sendPOST(
            $I->formatUrl(
                '{resourceGuestCarts}/{guestCartUuid}/{resourceGuestCartItems}',
                [
                    'resourceGuestCarts' => CartsRestApiConfig::RESOURCE_GUEST_CARTS,
                    'guestCartUuid' => $guestQuoteUuid,
                    'resourceGuestCartItems' => CartsRestApiConfig::RESOURCE_GUEST_CARTS_ITEMS,
                ],
            ),
            [
                'data' => [
                    'type' => CartsRestApiConfig::RESOURCE_GUEST_CARTS_ITEMS,
                    'attributes' => [
                        'sku' => $this->fixtures->getProductConcreteTransfer2()->getSku(),
                    ],
                ],
            ],
        );

        // Assert
        $I->seeResponseCodeIs(HttpCode::UNPROCESSABLE_ENTITY);
        $I->seeResponseMatchesOpenApiSchema();
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
        $guestCustomerReference = $I->createGuestCustomerReference();
        $I->haveHttpHeader(
            CartsRestApiConfig::HEADER_ANONYMOUS_CUSTOMER_UNIQUE_ID,
            $guestCustomerReference,
        );
        $quoteTransfer = $I->createPersistentQuote(
            $I,
            (new CustomerTransfer())->setCustomerReference(GuestCartsRestApiFixtures::ANONYMOUS_PREFIX . $guestCustomerReference),
            [$this->fixtures->getProductConcreteTransfer1()],
        );
        $guestQuoteUuid = $quoteTransfer->getUuid();
        $itemGroupKey = $I->getGroupKeyFromQuote($quoteTransfer, $this->fixtures->getProductConcreteTransfer1()->getSku());

        // Act
        $I->sendPATCH(
            $I->formatUrl(
                '{resourceGuestCarts}/{guestCartUuid}/{resourceGuestCartItems}/{itemId}?include={resourceGuestCartItems}',
                [
                    'resourceGuestCarts' => CartsRestApiConfig::RESOURCE_GUEST_CARTS,
                    'guestCartUuid' => $guestQuoteUuid,
                    'resourceGuestCartItems' => CartsRestApiConfig::RESOURCE_GUEST_CARTS_ITEMS,
                    'itemId' => $itemGroupKey,
                ],
            ),
            [
                'data' => [
                    'type' => CartsRestApiConfig::RESOURCE_GUEST_CARTS_ITEMS,
                    'attributes' => [
                        'quantity' => $this->fixtures::QUANTITY_FOR_ITEM_UPDATE,
                    ],
                ],
            ],
        );

        // Assert
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseMatchesOpenApiSchema();

        $I->amSure('Returned resource has correct id')
            ->whenI()
            ->seeSingleResourceIdEqualTo($guestQuoteUuid);

        $I->seeCartItemQuantityEqualsToQuantityInRequest(
            $this->fixtures::QUANTITY_FOR_ITEM_UPDATE,
            CartsRestApiConfig::RESOURCE_GUEST_CARTS_ITEMS,
            $itemGroupKey,
        );

        $I->seeSingleResourceHasSelfLink(
            $I->formatFullUrl(
                '{resourceGuestCarts}/{guestCartUuid}',
                [
                    'resourceGuestCarts' => CartsRestApiConfig::RESOURCE_GUEST_CARTS,
                    'guestCartUuid' => $guestQuoteUuid,
                ],
            ),
        );
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\Carts\CartsApiTester $I
     *
     * @return void
     */
    public function requestUpdateItemsInGuestCartWithoutGuestCartUuid(CartsApiTester $I): void
    {
        // Arrange
        $I->haveHttpHeader(
            CartsRestApiConfig::HEADER_ANONYMOUS_CUSTOMER_UNIQUE_ID,
            $I->createGuestCustomerReference(),
        );

        // Act
        $I->sendPATCH(
            $I->formatUrl(
                '{resourceGuestCarts}//{resourceGuestCartItems}/{guestCartItemSku}',
                [
                    'resourceGuestCarts' => CartsRestApiConfig::RESOURCE_GUEST_CARTS,
                    'resourceGuestCartItems' => CartsRestApiConfig::RESOURCE_GUEST_CARTS_ITEMS,
                    'guestCartItemSku' => $this->fixtures->getProductConcreteTransfer2()->getSku(),
                ],
            ),
            [
                'data' => [
                    'type' => CartsRestApiConfig::RESOURCE_GUEST_CARTS_ITEMS,
                    'attributes' => [
                        'quantity' => $this->fixtures::QUANTITY_FOR_ITEM_UPDATE,
                    ],
                ],
            ],
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
    public function requestUpdateItemsInGuestCartWithoutAnonymousCustomerUniqueId(CartsApiTester $I): void
    {
        // Arrange
        $quoteTransfer = $I->createPersistentQuote(
            $I,
            (new CustomerTransfer())->setCustomerReference(GuestCartsRestApiFixtures::ANONYMOUS_PREFIX . $I->createGuestCustomerReference()),
            [$this->fixtures->getProductConcreteTransfer1()],
        );
        $guestQuoteUuid = $quoteTransfer->getUuid();

        // Act
        $I->sendPATCH(
            $I->formatUrl(
                '{resourceGuestCarts}/{guestCartUuid}/{resourceGuestCartItems}/{itemSku}',
                [
                    'resourceGuestCarts' => CartsRestApiConfig::RESOURCE_GUEST_CARTS,
                    'guestCartUuid' => $guestQuoteUuid,
                    'resourceGuestCartItems' => CartsRestApiConfig::RESOURCE_GUEST_CARTS_ITEMS,
                    'itemSku' => $this->fixtures->getProductConcreteTransfer1()->getSku(),
                ],
            ),
            [
                'data' => [
                    'type' => CartsRestApiConfig::RESOURCE_GUEST_CARTS_ITEMS,
                    'attributes' => [
                        'quantity' => $this->fixtures::QUANTITY_FOR_ITEM_UPDATE,
                    ],
                ],
            ],
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
    public function requestUpdateItemsInGuestCartWithoutQuantity(CartsApiTester $I): void
    {
        // Arrange
        $guestCustomerReference = $I->createGuestCustomerReference();
        $quoteTransfer = $I->createPersistentQuote(
            $I,
            (new CustomerTransfer())->setCustomerReference(GuestCartsRestApiFixtures::ANONYMOUS_PREFIX . $guestCustomerReference),
            [$this->fixtures->getProductConcreteTransfer1()],
        );
        $guestQuoteUuid = $quoteTransfer->getUuid();
        $I->haveHttpHeader(
            CartsRestApiConfig::HEADER_ANONYMOUS_CUSTOMER_UNIQUE_ID,
            $guestCustomerReference,
        );

        // Act
        $I->sendPATCH(
            $I->formatUrl(
                '{resourceGuestCarts}/{guestCartUuid}/{resourceGuestCartItems}/{itemSku}',
                [
                    'resourceGuestCarts' => CartsRestApiConfig::RESOURCE_GUEST_CARTS,
                    'guestCartUuid' => $guestQuoteUuid,
                    'resourceGuestCartItems' => CartsRestApiConfig::RESOURCE_GUEST_CARTS_ITEMS,
                    'itemSku' => $this->fixtures->getProductConcreteTransfer1()->getSku(),
                ],
            ),
            [
                'data' => [
                    'type' => CartsRestApiConfig::RESOURCE_GUEST_CARTS_ITEMS,
                    'attributes' => [
                    ],
                ],
            ],
        );

        // Assert
        $I->seeResponseCodeIs(HttpCode::UNPROCESSABLE_ENTITY);
        $I->seeResponseMatchesOpenApiSchema();
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\Carts\CartsApiTester $I
     *
     * @return void
     */
    public function requestUpdateItemsInGuestCartWithoutItemSku(CartsApiTester $I): void
    {
        // Arrange
        $guestCustomerReference = $I->createGuestCustomerReference();
        $quoteTransfer = $I->createPersistentQuote(
            $I,
            (new CustomerTransfer())->setCustomerReference(GuestCartsRestApiFixtures::ANONYMOUS_PREFIX . $guestCustomerReference),
            [$this->fixtures->getProductConcreteTransfer1()],
        );
        $guestQuoteUuid = $quoteTransfer->getUuid();
        $I->haveHttpHeader(
            CartsRestApiConfig::HEADER_ANONYMOUS_CUSTOMER_UNIQUE_ID,
            $guestCustomerReference,
        );

        // Act
        $I->sendPATCH(
            $I->formatUrl(
                '{resourceGuestCarts}/{guestCartUuid}/{resourceGuestCartItems}/',
                [
                    'resourceGuestCarts' => CartsRestApiConfig::RESOURCE_GUEST_CARTS,
                    'guestCartUuid' => $guestQuoteUuid,
                    'resourceGuestCartItems' => CartsRestApiConfig::RESOURCE_GUEST_CARTS_ITEMS,
                ],
            ),
            [
                'data' => [
                    'type' => CartsRestApiConfig::RESOURCE_GUEST_CARTS_ITEMS,
                    'attributes' => [
                        'quantity' => $this->fixtures::QUANTITY_FOR_ITEM_UPDATE,
                    ],
                ],
            ],
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
    public function requestDeleteItemsFromGuestCart(CartsApiTester $I): void
    {
        // Arrange
        $guestCustomerReference = $I->createGuestCustomerReference();
        $quoteTransfer = $I->createPersistentQuote(
            $I,
            (new CustomerTransfer())->setCustomerReference(GuestCartsRestApiFixtures::ANONYMOUS_PREFIX . $guestCustomerReference),
            [$this->fixtures->getProductConcreteTransfer1()],
        );
        $guestQuoteUuid = $quoteTransfer->getUuid();
        $I->haveHttpHeader(
            CartsRestApiConfig::HEADER_ANONYMOUS_CUSTOMER_UNIQUE_ID,
            $guestCustomerReference,
        );

        // Act
        $I->sendDelete(
            $I->formatUrl(
                '{resourceGuestCarts}/{guestCartUuid}/{resourceGuestCartItems}/{itemSku}',
                [
                    'resourceGuestCarts' => CartsRestApiConfig::RESOURCE_GUEST_CARTS,
                    'guestCartUuid' => $guestQuoteUuid,
                    'resourceGuestCartItems' => CartsRestApiConfig::RESOURCE_GUEST_CARTS_ITEMS,
                    'itemSku' => $this->fixtures->getProductConcreteTransfer1()->getSku(),
                ],
            ),
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
    public function requestDeleteItemsFromGuestCartWithoutGuestCartUuid(CartsApiTester $I): void
    {
        // Arrange
        $I->haveHttpHeader(
            CartsRestApiConfig::HEADER_ANONYMOUS_CUSTOMER_UNIQUE_ID,
            $I->createGuestCustomerReference(),
        );

        // Act
        $I->sendDelete(
            $I->formatUrl(
                '{resourceGuestCarts}//{resourceGuestCartItems}/{itemSku}',
                [
                    'resourceGuestCarts' => CartsRestApiConfig::RESOURCE_GUEST_CARTS,
                    'resourceGuestCartItems' => CartsRestApiConfig::RESOURCE_GUEST_CARTS_ITEMS,
                    'itemSku' => $this->fixtures->getProductConcreteTransfer1()->getSku(),
                ],
            ),
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
    public function requestDeleteItemsFromGuestCartWithoutAnonymousCustomerUniqueId(CartsApiTester $I): void
    {
        // Arrange
        $quoteTransfer = $I->createPersistentQuote(
            $I,
            (new CustomerTransfer())->setCustomerReference(GuestCartsRestApiFixtures::ANONYMOUS_PREFIX . $I->createGuestCustomerReference()),
            [$this->fixtures->getProductConcreteTransfer1()],
        );
        $guestQuoteUuid = $quoteTransfer->getUuid();

        // Act
        $I->sendDelete(
            $I->formatUrl(
                '{resourceGuestCarts}/{guestCartUuid}/{resourceGuestCartItems}/{itemSku}',
                [
                    'resourceGuestCarts' => CartsRestApiConfig::RESOURCE_GUEST_CARTS,
                    'guestCartUuid' => $guestQuoteUuid,
                    'resourceGuestCartItems' => CartsRestApiConfig::RESOURCE_GUEST_CARTS_ITEMS,
                    'itemSku' => $this->fixtures->getProductConcreteTransfer1()->getSku(),
                ],
            ),
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
    public function requestDeleteItemsFromGuestCartWithoutItemSku(CartsApiTester $I): void
    {
        // Arrange
        $guestCustomerReference = $I->createGuestCustomerReference();
        $quoteTransfer = $I->createPersistentQuote(
            $I,
            (new CustomerTransfer())->setCustomerReference(GuestCartsRestApiFixtures::ANONYMOUS_PREFIX . $guestCustomerReference),
            [$this->fixtures->getProductConcreteTransfer1()],
        );
        $guestQuoteUuid = $quoteTransfer->getUuid();
        $I->haveHttpHeader(
            CartsRestApiConfig::HEADER_ANONYMOUS_CUSTOMER_UNIQUE_ID,
            $guestCustomerReference,
        );

        // Act
        $I->sendDelete(
            $I->formatUrl(
                '{resourceGuestCarts}/{guestCartUuid}/{resourceGuestCartItems}/',
                [
                    'resourceGuestCarts' => CartsRestApiConfig::RESOURCE_GUEST_CARTS,
                    'guestCartUuid' => $guestQuoteUuid,
                    'resourceGuestCartItems' => CartsRestApiConfig::RESOURCE_GUEST_CARTS_ITEMS,
                ],
            ),
        );

        // Assert
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseIsJson();
    }
}
