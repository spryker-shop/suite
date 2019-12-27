<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Glue\Checkout\RestApi;

use Codeception\Util\HttpCode;
use Generated\Shared\Transfer\CustomerTransfer;
use Pyz\Glue\CheckoutRestApi\CheckoutRestApiConfig;
use PyzTest\Glue\Checkout\CheckoutRestApiTester;
use Spryker\Glue\PaymentsRestApi\PaymentsRestApiConfig;
use Spryker\Glue\ShipmentsRestApi\ShipmentsRestApiConfig;

/**
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Glue
 * @group Checkout
 * @group RestApi
 * @group CheckoutDataRestApiCest
 * Add your own group annotations below this line
 * @group EndToEnd
 */
class CheckoutDataRestApiCest
{
    protected const NOT_EXISTING_ID_CART = 'NOT_EXISTING_ID_CART';

    protected const KEY_ACCESS_TOKEN = 'accessToken';

    /**
     * @var \PyzTest\Glue\Checkout\RestApi\CheckoutDataRestApiFixtures
     */
    protected $fixtures;

    /**
     * @param \PyzTest\Glue\Checkout\CheckoutRestApiTester $I
     *
     * @return void
     */
    public function loadFixtures(CheckoutRestApiTester $I): void
    {
        /** @var \PyzTest\Glue\Checkout\RestApi\CheckoutDataRestApiFixtures $fixtures */
        $fixtures = $I->loadFixtures(CheckoutDataRestApiFixtures::class);
        $this->fixtures = $fixtures;
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\Checkout\CheckoutRestApiTester $I
     *
     * @return void
     */
    public function requestCheckoutDataWhenCustomerIsNotLoggedInShouldFail(CheckoutRestApiTester $I): void
    {
        //Act
        $I->sendPOST(CheckoutRestApiConfig::RESOURCE_CHECKOUT_DATA, [
            'data' => [
                'type' => CheckoutRestApiConfig::RESOURCE_CHECKOUT_DATA,
                'attributes' => [
                    'idCart' => static::NOT_EXISTING_ID_CART,
                ],
            ],
        ]);

        //Assert
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesOpenApiSchema();
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\Checkout\CheckoutRestApiTester $I
     *
     * @return void
     */
    public function requestCheckoutDataWithIdCartShouldBeSuccessful(CheckoutRestApiTester $I): void
    {
        //Arrange
        $this->requestCustomerLogin($I, $this->fixtures->getCustomerTransfer());

        //Act
        $I->sendPOST(CheckoutRestApiConfig::RESOURCE_CHECKOUT_DATA, [
            'data' => [
                'type' => CheckoutRestApiConfig::RESOURCE_CHECKOUT_DATA,
                'attributes' => [
                    'idCart' => $this->fixtures->getQuoteTransfer()->getUuid(),
                ],
            ],
        ]);

        //Assert
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesOpenApiSchema();
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\Checkout\CheckoutRestApiTester $I
     *
     * @return void
     */
    public function requestCheckoutDataWithIncorrectIdCartShouldFail(CheckoutRestApiTester $I): void
    {
        //Arrange
        $this->requestCustomerLogin($I, $this->fixtures->getCustomerTransfer());

        //Act
        $I->sendPOST(CheckoutRestApiConfig::RESOURCE_CHECKOUT_DATA, [
            'data' => [
                'type' => CheckoutRestApiConfig::RESOURCE_CHECKOUT_DATA,
                'attributes' => [
                    'idCart' => static::NOT_EXISTING_ID_CART,
                ],
            ],
        ]);

        //Assert
        $I->seeResponseCodeIs(HttpCode::UNPROCESSABLE_ENTITY);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesOpenApiSchema();
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\Checkout\CheckoutRestApiTester $I
     *
     * @return void
     */
    public function requestCheckoutDataWithSelectedShipmentMethodShouldGetShipmentMethodDetails(
        CheckoutRestApiTester $I
    ): void {
        //Arrange
        $this->requestCustomerLogin($I, $this->fixtures->getCustomerTransfer());

        //Act
        $I->sendPOST(CheckoutRestApiConfig::RESOURCE_CHECKOUT_DATA, [
            'data' => [
                'type' => CheckoutRestApiConfig::RESOURCE_CHECKOUT_DATA,
                'attributes' => [
                    'idCart' => $this->fixtures->getQuoteTransfer()->getUuid(),
                    'shipment' => [
                        'idShipmentMethod' => $this->fixtures->getShipmentMethodTransfer()->getIdShipmentMethod(),
                    ],
                ],
            ],
        ]);

        //Assert
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesOpenApiSchema();

        $selectedShipmentMethods = $I->grabDataFromResponseByJsonPath('$.data.attributes.selectedShipmentMethods');
        $I->assertIsArray($selectedShipmentMethods, 'Selected methods were not returned');
        $I->assertCount(1, $selectedShipmentMethods);
        $selectedShipmentMethod = $selectedShipmentMethods[0];

        $I->assertNotEmpty($selectedShipmentMethods);
        $I->assertNotEmpty($selectedShipmentMethod);
        $I->assertSame($selectedShipmentMethod['name'], $this->fixtures->getShipmentMethodTransfer()->getName());
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\Checkout\CheckoutRestApiTester $I
     *
     * @return void
     */
    public function requestCheckoutDataWithIncludedShipmentMethods(CheckoutRestApiTester $I): void
    {
        //Arrange
        $this->requestCustomerLogin($I, $this->fixtures->getCustomerTransfer());

        $url = $I->formatUrl(
            '{resource}?include={relationship}',
            [
                'resource' => CheckoutRestApiConfig::RESOURCE_CHECKOUT_DATA,
                'relationship' => ShipmentsRestApiConfig::RESOURCE_SHIPMENT_METHODS,
            ]
        );

        //Act
        $I->sendPOST($url, [
            'data' => [
                'type' => CheckoutRestApiConfig::RESOURCE_CHECKOUT_DATA,
                'attributes' => [
                    'idCart' => $this->fixtures->getQuoteTransfer()->getUuid(),
                ],
            ],
        ]);

        //Assert
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesOpenApiSchema();

        $idShipmentMethod = $this->fixtures->getShipmentMethodTransfer()->getIdShipmentMethod();

        $I->amSure('Returned resource has shipment method in `relationships` section.')
            ->whenI()
            ->seeSingleResourceHasRelationshipByTypeAndId(ShipmentsRestApiConfig::RESOURCE_SHIPMENT_METHODS, $idShipmentMethod);

        $I->amSure('Returned resource has shipment method in `included` section.')
            ->whenI()
            ->seeIncludesContainsResourceByTypeAndId(ShipmentsRestApiConfig::RESOURCE_SHIPMENT_METHODS, $idShipmentMethod);
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\Checkout\CheckoutRestApiTester $I
     *
     * @return void
     */
    public function requestCheckoutDataWithIncludedPaymentMethods(CheckoutRestApiTester $I): void
    {
        //Arrange
        $this->requestCustomerLogin($I, $this->fixtures->getCustomerTransfer());

        $url = $I->formatUrl('{resource}?include={relationship}', [
            'resource' => CheckoutRestApiConfig::RESOURCE_CHECKOUT_DATA,
            'relationship' => PaymentsRestApiConfig::RESOURCE_PAYMENT_METHODS,
        ]);

        //Act
        $I->sendPOST($url, [
            'data' => [
                'type' => CheckoutRestApiConfig::RESOURCE_CHECKOUT_DATA,
                'attributes' => [
                    'idCart' => $this->fixtures->getQuoteTransfer()->getUuid(),
                ],
            ],
        ]);

        //Assert
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesOpenApiSchema();

        $idPaymentMethod = $this->fixtures->getPaymentMethodTransfer()->getIdPaymentMethod();

        $I->amSure('Returned resource has payment method in `relationships` section.')
            ->whenI()
            ->seeSingleResourceHasRelationshipByTypeAndId(PaymentsRestApiConfig::RESOURCE_PAYMENT_METHODS, $idPaymentMethod);

        $I->amSure('Returned resource has payment method in `included` section.')
            ->whenI()
            ->seeIncludesContainsResourceByTypeAndId(PaymentsRestApiConfig::RESOURCE_PAYMENT_METHODS, $idPaymentMethod);
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\Checkout\CheckoutRestApiTester $I
     *
     * @return void
     */
    public function requestCheckoutDataWithSelectedPaymentMethodShouldGetPaymentMethodDetails(
        CheckoutRestApiTester $I
    ): void {
        //Arrange
        $this->requestCustomerLogin($I, $this->fixtures->getCustomerTransfer());

        $paymentMethodTransfer = $this->fixtures->getPaymentMethodTransfer();
        $paymentProviderTransfer = $this->fixtures->getPaymentProviderTransfer();

        //Act
        $I->sendPOST(CheckoutRestApiConfig::RESOURCE_CHECKOUT_DATA, [
            'data' => [
                'type' => CheckoutRestApiConfig::RESOURCE_CHECKOUT_DATA,
                'attributes' => [
                    'idCart' => $this->fixtures->getQuoteTransfer()->getUuid(),
                    'payments' => [
                        [
                            'paymentProviderName' => $paymentProviderTransfer->getPaymentProviderKey(),
                            'paymentMethodName' => $paymentMethodTransfer->getName(),
                        ],
                    ],
                ],
            ],
        ]);

        //Assert
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesOpenApiSchema();

        $selectedPaymentMethods = $I
            ->grabDataFromResponseByJsonPath('$.data.attributes.selectedPaymentMethods');

        $I->assertNotEmpty($selectedPaymentMethods);

        $selectedPaymentMethod = $selectedPaymentMethods[0];
        $I->assertNotEmpty($selectedPaymentMethod);
        $I->assertSame($selectedPaymentMethod['paymentMethodName'], $paymentMethodTransfer->getName());
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\Checkout\CheckoutRestApiTester $I
     *
     * @return void
     */
    public function requestCheckoutDataWithoutSelectedPaymentMethodShouldNotGetSelectedPaymentMethodDetails(
        CheckoutRestApiTester $I
    ): void {
        //Arrange
        $this->requestCustomerLogin($I, $this->fixtures->getCustomerTransfer());

        //Act
        $I->sendPOST(CheckoutRestApiConfig::RESOURCE_CHECKOUT_DATA, [
            'data' => [
                'type' => CheckoutRestApiConfig::RESOURCE_CHECKOUT_DATA,
                'attributes' => [
                    'idCart' => $this->fixtures->getQuoteTransfer()->getUuid(),
                ],
            ],
        ]);

        //Assert
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesOpenApiSchema();
        $selectedPaymentMethods = $I
            ->grabDataFromResponseByJsonPath('$.data.attributes.selectedPaymentMethods');

        $I->assertIsArray($selectedPaymentMethods);
        $I->assertEmpty(current($selectedPaymentMethods));
    }

    /**
     * @param \PyzTest\Glue\Checkout\CheckoutRestApiTester $I
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return void
     */
    protected function requestCustomerLogin(CheckoutRestApiTester $I, CustomerTransfer $customerTransfer): void
    {
        $accessToken = $I->haveAuthorizationToGlue($customerTransfer)[static::KEY_ACCESS_TOKEN];

        $I->amBearerAuthenticated($accessToken);
    }
}
