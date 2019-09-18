<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Glue\Checkout\RestApi;

use Codeception\Util\HttpCode;
use Generated\Shared\Transfer\CustomerTransfer;
use PyzTest\Glue\Checkout\CheckoutRestApiTester;
use Spryker\Glue\CheckoutRestApi\CheckoutRestApiConfig;
use Spryker\Glue\PaymentsRestApi\PaymentsRestApiConfig;
use Spryker\Glue\ShipmentsRestApi\ShipmentsRestApiConfig;

/**
 * Auto-generated group annotations
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
        $this->fixtures = $I->loadFixtures(CheckoutDataRestApiFixtures::class);
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
        $this->assertCheckoutDataRequest($I, HttpCode::BAD_REQUEST);
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
        $this->assertCheckoutDataRequest($I, HttpCode::OK);
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
        $this->assertCheckoutDataRequest($I, HttpCode::UNPROCESSABLE_ENTITY);
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
        $this->assertCheckoutDataRequest($I, HttpCode::OK);
        $selectedShipmentMethods = $I
            ->grabDataFromResponseByJsonPath('$.data.attributes.selectedShipmentMethods')[0];
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
        $this->assertCheckoutDataRequest($I, HttpCode::OK);
        $this->assertCheckoutDataRequestWithIncludedShipmentMethods($I);
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
            'relationship' => PaymentsRestApiConfig::RESOURCE_PAYMENT_METHODS
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
        $this->assertCheckoutDataRequest($I, HttpCode::OK);
        $this->assertCheckoutDataRequestWithIncludedPaymentMethods($I);
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

        $paymentProviderTransfer = $I->haveAvailablePaymentProvider();
        $paymentMethodTransfer = current($paymentProviderTransfer->getPaymentMethods());

        //Act
        $I->sendPOST(CheckoutRestApiConfig::RESOURCE_CHECKOUT_DATA, [
            'data' => [
                'type' => CheckoutRestApiConfig::RESOURCE_CHECKOUT_DATA,
                'attributes' => [
                    'idCart' => $this->fixtures->getQuoteTransfer()->getUuid(),
                    'payments' => [
                        [
                            'paymentProviderName' => $paymentProviderTransfer->getName(),
                            'paymentMethodName' => $paymentMethodTransfer->getMethodName(),
                        ]
                    ],
                ],
            ],
        ]);


        //Assert
        $this->assertCheckoutDataRequest($I, HttpCode::OK);
        $selectedPaymentMethods = $I
            ->grabDataFromResponseByJsonPath('$.data.attributes.selectedPaymentMethods')[0];

        $selectedPaymentMethod = $selectedPaymentMethods[0];

        $I->assertNotEmpty($selectedPaymentMethods);
        $I->assertNotEmpty($selectedPaymentMethod);
        $I->assertSame($selectedPaymentMethod['paymentMethodName'], $paymentMethodTransfer->getMethodName());
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
        $this->assertCheckoutDataRequest($I, HttpCode::OK);
        $selectedPaymentMethods = $I
            ->grabDataFromResponseByJsonPath('$.data.attributes.selectedPaymentMethods');

        $I->assertIsArray($selectedPaymentMethods);
        $I->assertEmpty(current($selectedPaymentMethods));
    }

    /**
     * @param \PyzTest\Glue\Checkout\CheckoutRestApiTester $I
     *
     * @return void
     */
    protected function assertCheckoutDataRequestWithIncludedShipmentMethods(
        CheckoutRestApiTester $I
    ): void {
        $idShipmentMethod = $this->fixtures->getShipmentMethodTransfer()->getIdShipmentMethod();

        $I->amSure('Returned resource has shipment method in `relationships` section.')
            ->whenI()
            ->seeSingleResourceHasRelationshipByTypeAndId(ShipmentsRestApiConfig::RESOURCE_SHIPMENT_METHODS, $idShipmentMethod);

        $I->amSure('Returned resource has shipment method in `included` section.')
            ->whenI()
            ->seeIncludesContainsResourceByTypeAndId(ShipmentsRestApiConfig::RESOURCE_SHIPMENT_METHODS, $idShipmentMethod);
    }

    /**
     * @param \PyzTest\Glue\Checkout\CheckoutRestApiTester $I
     * @param array $pamentMethods
     *
     * @return void
     */
    protected function assertCheckoutDataRequestWithIncludedPaymentMethods(
        CheckoutRestApiTester $I
    ): void {
        $idPaymentMethod = current($I->haveAvailablePaymentProvider()->getPaymentMethods())->getIdSalesPaymentMethodType();
        $I->amSure('Returned resource has payment method in `relationships` section.')
            ->whenI()
            ->seeSingleResourceHasRelationshipByTypeAndId(PaymentsRestApiConfig::RESOURCE_PAYMENT_METHODS, $idPaymentMethod);

        $I->amSure('Returned resource has payment method in `included` section.')
            ->whenI()
            ->seeIncludesContainsResourceByTypeAndId(PaymentsRestApiConfig::RESOURCE_PAYMENT_METHODS, $idPaymentMethod);
    }

    /**
     * @param \PyzTest\Glue\Checkout\CheckoutRestApiTester $I
     * @param int $responseCode
     *
     * @return void
     */
    protected function assertCheckoutDataRequest(
        CheckoutRestApiTester $I,
        int $responseCode
    ): void {
        $I->seeResponseCodeIs($responseCode);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesOpenApiSchema();
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

        $I->amAuthorizedGlueUser($accessToken);
    }
}
