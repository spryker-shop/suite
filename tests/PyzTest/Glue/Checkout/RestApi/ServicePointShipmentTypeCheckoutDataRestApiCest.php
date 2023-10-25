<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Glue\Checkout\RestApi;

use Codeception\Util\HttpCode;
use Generated\Shared\DataBuilder\AddressBuilder;
use PyzTest\Glue\Checkout\CheckoutApiTester;
use PyzTest\Glue\Checkout\RestApi\Fixtures\ServicePointShipmentTypeCheckoutDataRestApiFixtures;
use Spryker\Glue\CheckoutRestApi\CheckoutRestApiConfig;
use Spryker\Glue\ShipmentTypeServicePointsRestApi\ShipmentTypeServicePointsRestApiConfig;

/**
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Glue
 * @group Checkout
 * @group RestApi
 * @group ServicePointShipmentTypeCheckoutDataRestApiCest
 * Add your own group annotations below this line
 */
class ServicePointShipmentTypeCheckoutDataRestApiCest
{
    /**
     * @var \PyzTest\Glue\Checkout\RestApi\Fixtures\ServicePointShipmentTypeCheckoutDataRestApiFixtures
     */
    protected ServicePointShipmentTypeCheckoutDataRestApiFixtures $fixtures;

    /**
     * @param \PyzTest\Glue\Checkout\CheckoutApiTester $I
     *
     * @return void
     */
    public function loadFixtures(CheckoutApiTester $I): void
    {
        /** @var \PyzTest\Glue\Checkout\RestApi\Fixtures\ServicePointShipmentTypeCheckoutDataRestApiFixtures $fixtures */
        $fixtures = $I->loadFixtures(ServicePointShipmentTypeCheckoutDataRestApiFixtures::class);
        $this->fixtures = $fixtures;
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\Checkout\CheckoutApiTester $I
     *
     * @return void
     */
    public function requestCheckoutDataReturnsServicePointNotProvidedValidationErrorForMultiShipment(CheckoutApiTester $I): void
    {
        // Arrange
        $I->authorizeCustomerToGlue($this->fixtures->getCustomerTransfer());

        $quoteTransfer = $this->fixtures->getQuoteTransfer();
        $url = $I->buildCheckoutDataUrl();

        $requestPayload = [
            'data' => [
                'type' => CheckoutRestApiConfig::RESOURCE_CHECKOUT_DATA,
                'attributes' => [
                    'idCart' => $quoteTransfer->getUuid(),
                    'customer' => $I->getCustomerRequestPayload($this->fixtures->getCustomerTransfer()),
                    'shipments' => [
                        [
                            'items' => [$this->fixtures->getQuoteTransfer()->getItems()[0]->getGroupKeyOrFail()],
                            'shippingAddress' => $I->getAddressRequestPayload((new AddressBuilder())->build()),
                            'idShipmentMethod' => $this->fixtures->getNonPickableShipmentMethodTransfer()->getIdShipmentMethodOrFail(),
                            'requestedDeliveryDate' => null,
                        ],
                        [
                            'items' => [$this->fixtures->getQuoteTransfer()->getItems()[1]->getGroupKeyOrFail()],
                            'shippingAddress' => $I->getAddressRequestPayload((new AddressBuilder())->build()),
                            'idShipmentMethod' => $this->fixtures->getPickableShipmentMethodTransfer()->getIdShipmentMethodOrFail(),
                            'requestedDeliveryDate' => null,
                        ],
                    ],
                ],
            ],
        ];

        // Act
        $I->sendPOST($url, $requestPayload);

        // Assert
        $error = $I->getDataFromResponseByJsonPath('$.errors[0]');
        $I->seeResponseCodeIs(HttpCode::UNPROCESSABLE_ENTITY);
        $I->seeResponseIsJson();

        $I->amSure('The returned response contains expected error message')
            ->whenI()
            ->assertSame(
                ShipmentTypeServicePointsRestApiConfig::ERROR_RESPONSE_DETAIL_SERVICE_POINT_NOT_PROVIDED,
                $error['detail'],
            );

        $I->amSure('The returned response contains expected error code')
            ->whenI()
            ->assertSame(
                ShipmentTypeServicePointsRestApiConfig::ERROR_RESPONSE_CODE_SERVICE_POINT_NOT_PROVIDED,
                $error['code'],
            );
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\Checkout\CheckoutApiTester $I
     *
     * @return void
     */
    public function requestCheckoutDataReturnsServicePointNotProvidedValidationErrorForSingleShipment(CheckoutApiTester $I): void
    {
        // Arrange
        $I->authorizeCustomerToGlue($this->fixtures->getCustomerTransfer());

        $quoteTransfer = $this->fixtures->getQuoteTransfer();
        $url = $I->buildCheckoutDataUrl();

        $requestPayload = [
            'data' => [
                'type' => CheckoutRestApiConfig::RESOURCE_CHECKOUT_DATA,
                'attributes' => [
                    'idCart' => $quoteTransfer->getUuid(),
                    'customer' => $I->getCustomerRequestPayload($this->fixtures->getCustomerTransfer()),
                    'shipment' => [
                        'idShipmentMethod' => $this->fixtures->getPickableShipmentMethodTransfer()->getIdShipmentMethodOrFail(),
                        'requestedDeliveryDate' => null,
                    ],
                ],
            ],
        ];

        // Act
        $I->sendPOST($url, $requestPayload);

        // Assert
        $error = $I->getDataFromResponseByJsonPath('$.errors[0]');
        $I->seeResponseCodeIs(HttpCode::UNPROCESSABLE_ENTITY);
        $I->seeResponseIsJson();

        $I->amSure('The returned response contains expected error message')
            ->whenI()
            ->assertSame(
                ShipmentTypeServicePointsRestApiConfig::ERROR_RESPONSE_DETAIL_SERVICE_POINT_NOT_PROVIDED,
                $error['detail'],
            );

        $I->amSure('The returned response contains expected error code')
            ->whenI()
            ->assertSame(
                ShipmentTypeServicePointsRestApiConfig::ERROR_RESPONSE_CODE_SERVICE_POINT_NOT_PROVIDED,
                $error['code'],
            );
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\Checkout\CheckoutApiTester $I
     *
     * @return void
     */
    public function requestCheckoutDataReturnsCustomerDataNotProvidedValidationError(CheckoutApiTester $I): void
    {
        // Arrange
        $I->authorizeCustomerToGlue($this->fixtures->getCustomerTransfer());

        $quoteTransfer = $this->fixtures->getQuoteTransfer();
        $url = $I->buildCheckoutDataUrl();

        $requestPayload = [
            'data' => [
                'type' => CheckoutRestApiConfig::RESOURCE_CHECKOUT_DATA,
                'attributes' => [
                    'idCart' => $quoteTransfer->getUuid(),
                    'shipments' => [
                        [
                            'items' => [$this->fixtures->getQuoteTransfer()->getItems()[0]->getGroupKeyOrFail()],
                            'shippingAddress' => $I->getAddressRequestPayload((new AddressBuilder())->build()),
                            'idShipmentMethod' => $this->fixtures->getNonPickableShipmentMethodTransfer()->getIdShipmentMethodOrFail(),
                            'requestedDeliveryDate' => null,
                        ],
                        [
                            'items' => [$this->fixtures->getQuoteTransfer()->getItems()[1]->getGroupKeyOrFail()],
                            'shippingAddress' => $I->getAddressRequestPayload((new AddressBuilder())->build()),
                            'idShipmentMethod' => $this->fixtures->getPickableShipmentMethodTransfer()->getIdShipmentMethodOrFail(),
                            'requestedDeliveryDate' => null,
                        ],
                    ],
                    'servicePoints' => [
                        [
                            'items' => [$this->fixtures->getQuoteTransfer()->getItems()[1]->getGroupKeyOrFail()],
                            'idServicePoint' => $this->fixtures->getServicePointWithAddress()->getUuidOrFail(),
                        ],
                    ],
                ],
            ],
        ];

        // Act
        $I->sendPOST($url, $requestPayload);

        // Assert
        $error = $I->getDataFromResponseByJsonPath('$.errors[0]');
        $I->seeResponseCodeIs(HttpCode::UNPROCESSABLE_ENTITY);
        $I->seeResponseIsJson();

        $I->amSure('The returned response contains expected message')
            ->whenI()
            ->assertSame(
                ShipmentTypeServicePointsRestApiConfig::ERROR_RESPONSE_DETAIL_CUSTOMER_DATA_MISSING,
                $error['detail'],
            );

        $I->amSure('The returned response contains expected code')
            ->whenI()
            ->assertSame(
                ShipmentTypeServicePointsRestApiConfig::ERROR_RESPONSE_CODE_CUSTOMER_DATA_MISSING,
                $error['code'],
            );
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\Checkout\CheckoutApiTester $I
     *
     * @return void
     */
    public function requestCheckoutDataReturnsServicePointHasNoAddressValidationError(CheckoutApiTester $I): void
    {
        // Arrange
        $I->authorizeCustomerToGlue($this->fixtures->getCustomerTransfer());

        $quoteTransfer = $this->fixtures->getQuoteTransfer();
        $url = $I->buildCheckoutDataUrl();

        $requestPayload = [
            'data' => [
                'type' => CheckoutRestApiConfig::RESOURCE_CHECKOUT_DATA,
                'attributes' => [
                    'idCart' => $quoteTransfer->getUuid(),
                    'customer' => $I->getCustomerRequestPayload($this->fixtures->getCustomerTransfer()),
                    'shipments' => [
                        [
                            'items' => [$this->fixtures->getQuoteTransfer()->getItems()[0]->getGroupKeyOrFail()],
                            'shippingAddress' => $I->getAddressRequestPayload((new AddressBuilder())->build()),
                            'idShipmentMethod' => $this->fixtures->getNonPickableShipmentMethodTransfer()->getIdShipmentMethodOrFail(),
                            'requestedDeliveryDate' => null,
                        ],
                        [
                            'items' => [$this->fixtures->getQuoteTransfer()->getItems()[1]->getGroupKeyOrFail()],
                            'shippingAddress' => $I->getAddressRequestPayload((new AddressBuilder())->build()),
                            'idShipmentMethod' => $this->fixtures->getPickableShipmentMethodTransfer()->getIdShipmentMethodOrFail(),
                            'requestedDeliveryDate' => null,
                        ],
                    ],
                    'servicePoints' => [
                        [
                            'items' => [$this->fixtures->getQuoteTransfer()->getItems()[1]->getGroupKeyOrFail()],
                            'idServicePoint' => $this->fixtures->getServicePoint()->getUuidOrFail(),
                        ],
                    ],
                ],
            ],
        ];

        // Act
        $I->sendPOST($url, $requestPayload);

        // Assert
        $error = $I->getDataFromResponseByJsonPath('$.errors[0]');
        $I->seeResponseCodeIs(HttpCode::UNPROCESSABLE_ENTITY);
        $I->seeResponseIsJson();

        $I->amSure('The returned response contains expected message')
            ->whenI()
            ->assertSame(
                sprintf(
                    ShipmentTypeServicePointsRestApiConfig::ERROR_RESPONSE_DETAIL_SERVICE_POINT_ADDRESS_MISSING,
                    $this->fixtures->getServicePoint()->getUuidOrFail(),
                ),
                $error['detail'],
            );

        $I->amSure('The returned response contains expected code')
            ->whenI()
            ->assertSame(
                ShipmentTypeServicePointsRestApiConfig::ERROR_RESPONSE_CODE_SERVICE_POINT_ADDRESS_MISSING,
                $error['code'],
            );
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\Checkout\CheckoutApiTester $I
     *
     * @return void
     */
    public function requestCheckoutReturnsShippingAddressMissingValidationError(CheckoutApiTester $I): void
    {
        // Arrange
        $I->authorizeCustomerToGlue($this->fixtures->getCustomerTransfer());

        $quoteTransfer = $this->fixtures->getQuoteTransfer();
        $url = $I->buildCheckoutUrl();

        $requestPayload = [
            'data' => [
                'type' => CheckoutRestApiConfig::RESOURCE_CHECKOUT,
                'attributes' => [
                    'idCart' => $quoteTransfer->getUuid(),
                    'customer' => $I->getCustomerRequestPayload($this->fixtures->getCustomerTransfer()),
                    'billingAddress' => $I->getAddressRequestPayload((new AddressBuilder())->build()),
                    'payments' => $I->getPaymentRequestPayload(),
                    'shipments' => [
                        [
                            'items' => [$this->fixtures->getQuoteTransfer()->getItems()[0]->getGroupKeyOrFail()],
                            'idShipmentMethod' => $this->fixtures->getNonPickableShipmentMethodTransfer()->getIdShipmentMethodOrFail(),
                            'requestedDeliveryDate' => null,
                        ],
                        [
                            'items' => [$this->fixtures->getQuoteTransfer()->getItems()[1]->getGroupKeyOrFail()],
                            'shippingAddress' => $I->getAddressRequestPayload((new AddressBuilder())->build()),
                            'idShipmentMethod' => $this->fixtures->getPickableShipmentMethodTransfer()->getIdShipmentMethodOrFail(),
                            'requestedDeliveryDate' => null,
                        ],
                    ],
                    'servicePoints' => [
                        [
                            'items' => [$this->fixtures->getQuoteTransfer()->getItems()[1]->getGroupKeyOrFail()],
                            'idServicePoint' => $this->fixtures->getServicePointWithAddress()->getUuidOrFail(),
                        ],
                    ],
                ],
            ],
        ];

        // Act
        $I->sendPOST($url, $requestPayload);

        // Assert
        $error = $I->getDataFromResponseByJsonPath('$.errors[0]');
        $I->seeResponseCodeIs(HttpCode::UNPROCESSABLE_ENTITY);
        $I->seeResponseIsJson();

        $I->amSure('The returned response contains expected message')
            ->whenI()
            ->assertSame(
                sprintf(
                    ShipmentTypeServicePointsRestApiConfig::ERROR_RESPONSE_DETAIL_ITEM_SHIPPING_ADDRESS_MISSING,
                    $this->fixtures->getQuoteTransfer()->getItems()[0]->getGroupKeyOrFail(),
                ),
                $error['detail'],
            );

        $I->amSure('The returned response contains expected code')
            ->whenI()
            ->assertSame(
                ShipmentTypeServicePointsRestApiConfig::ERROR_RESPONSE_CODE_ITEM_SHIPPING_ADDRESS_MISSING,
                $error['code'],
            );
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\Checkout\CheckoutApiTester $I
     *
     * @return void
     */
    public function requestCheckoutDataReturnsOnlyOneServicePointShouldBeSelectedValidationError(CheckoutApiTester $I): void
    {
        // Arrange
        $I->authorizeCustomerToGlue($this->fixtures->getCustomerTransfer());

        $quoteTransfer = $this->fixtures->getQuoteTransfer();
        $url = $I->buildCheckoutDataUrl();

        $requestPayload = [
            'data' => [
                'type' => CheckoutRestApiConfig::RESOURCE_CHECKOUT_DATA,
                'attributes' => [
                    'idCart' => $quoteTransfer->getUuid(),
                    'customer' => $I->getCustomerRequestPayload($this->fixtures->getCustomerTransfer()),
                    'shipment' => [
                        'idShipmentMethod' => $this->fixtures->getPickableShipmentMethodTransfer()->getIdShipmentMethodOrFail(),
                    ],
                    'servicePoints' => [
                        [
                            'items' => [$this->fixtures->getQuoteTransfer()->getItems()[0]->getGroupKeyOrFail()],
                            'idServicePoint' => $this->fixtures->getServicePointWithAddress()->getUuidOrFail(),
                        ],
                        [
                            'items' => [$this->fixtures->getQuoteTransfer()->getItems()[1]->getGroupKeyOrFail()],
                            'idServicePoint' => $this->fixtures->getServicePointWithAddress()->getUuidOrFail(),
                        ],
                    ],
                ],
            ],
        ];

        // Act
        $I->sendPOST($url, $requestPayload);

        // Assert
        $error = $I->getDataFromResponseByJsonPath('$.errors[0]');
        $I->seeResponseCodeIs(HttpCode::UNPROCESSABLE_ENTITY);
        $I->seeResponseIsJson();

        $I->amSure('The returned response contains expected message')
            ->whenI()
            ->assertSame(
                ShipmentTypeServicePointsRestApiConfig::ERROR_RESPONSE_DETAIL_ONLY_ONE_SERVICE_POINT_SHOULD_BE_SELECTED,
                $error['detail'],
            );

        $I->amSure('The returned response contains expected code')
            ->whenI()
            ->assertSame(
                ShipmentTypeServicePointsRestApiConfig::ERROR_RESPONSE_CODE_ONLY_ONE_SERVICE_POINT_SHOULD_BE_SELECTED,
                $error['code'],
            );
    }
}
