<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Glue\Checkout\RestApi;

use Codeception\Util\HttpCode;
use Generated\Shared\DataBuilder\AddressBuilder;
use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\RestCheckoutErrorTransfer;
use Generated\Shared\Transfer\RestCheckoutResponseTransfer;
use Generated\Shared\Transfer\RestPaymentTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use PyzTest\Glue\Checkout\CheckoutApiTester;
use PyzTest\Glue\Checkout\RestApi\Fixtures\GuestCheckoutRestApiFixtures;
use Spryker\Glue\CheckoutRestApi\CheckoutRestApiConfig;

/**
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Glue
 * @group Checkout
 * @group RestApi
 * @group GuestCheckoutRestApiCest
 * Add your own group annotations below this line
 * @group EndToEnd
 */
class GuestCheckoutRestApiCest
{
    protected const HEADER_ANONYMOUS_CUSTOMER_UNIQUE_ID = 'X-Anonymous-Customer-Unique-Id';

    protected const RESPONSE_CODE_CART_IS_EMPTY = '1104';
    protected const RESPONSE_DETAILS_CART_IS_EMPTY = 'Cart is empty.';

    /**
     * @var \PyzTest\Glue\Checkout\RestApi\Fixtures\GuestCheckoutRestApiFixtures
     */
    protected $fixtures;

    /**
     * @param \PyzTest\Glue\Checkout\CheckoutApiTester $I
     *
     * @return void
     */
    public function loadFixtures(CheckoutApiTester $I): void
    {
        $this->fixtures = $I->loadFixtures(GuestCheckoutRestApiFixtures::class);
        /**
         * @todo Create available payment methods.
         * Create available shipment methods.
         */
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\Checkout\CheckoutApiTester $I
     *
     * @return void
     */
    public function requestWithNoItemsInQuote(CheckoutApiTester $I): void
    {
        // Arrange
        $customerTransfer = $this->fixtures->getGuestCustomerTransfer();
        $I->haveHttpHeader(
            static::HEADER_ANONYMOUS_CUSTOMER_UNIQUE_ID,
            $this->fixtures->getGuestCustomerReference()
        );

        $quoteTransfer = $I->createPersistentQuote($customerTransfer, []);
        $shippingAddress = (new AddressBuilder())->build();

        $url = $I->buildCheckoutUrl();
        $urlParams = [
            'data' => [
                'type' => CheckoutRestApiConfig::RESOURCE_CHECKOUT,
                'attributes' => [
                    'idCart' => $quoteTransfer->getUuid(),
                    'billingAddress' => $this->getAddressParamData($quoteTransfer->getBillingAddress()),
                    'shippingAddress' => $this->getAddressParamData($shippingAddress),
                    'customer' => $this->getCustomerParamData($customerTransfer),
                    'payments' => [
                        [
                            RestPaymentTransfer::PAYMENT_METHOD_NAME => 'invoice',
                            RestPaymentTransfer::PAYMENT_PROVIDER_NAME => 'DummyPayment',
                            RestPaymentTransfer::PAYMENT_SELECTION => 'dummyPaymentInvoice',
                            RestPaymentTransfer::DUMMY_PAYMENT_INVOICE => [
                                'dateOfBirth' => '08.04.1986',
                            ],
                            'amount' => 899910,
                        ],
                    ],
                    'shipment' => [
                        ShipmentMethodTransfer::ID_SHIPMENT_METHOD => 1,
                        [
                            ShipmentMethodTransfer::ID => 1,
                            ShipmentMethodTransfer::CARRIER_NAME => 'Spryker Dummy Shipment',
                            ShipmentMethodTransfer::NAME => 'Standard',
                            ShipmentMethodTransfer::TAX_RATE => null,
                            'price' => 490,
                            'shipmentDeliveryTime' => null,
                        ],
                    ],
                ],
            ],
        ];

        // Act
        $I->sendPOST($url, $urlParams);

        // Assert
        $this->assertResponseHasCorrectInfrastructure($I, HttpCode::UNPROCESSABLE_ENTITY);

        $errors = $I->amSure('I\'m taking the error info from the returned resource')
            ->whenI()
            ->grabDataFromResponseByJsonPath('$.errors[0]');
        $I->assertEquals($errors[RestCheckoutErrorTransfer::CODE], static::RESPONSE_CODE_CART_IS_EMPTY);
        $I->assertEquals($errors[RestCheckoutErrorTransfer::STATUS], HttpCode::UNPROCESSABLE_ENTITY);
        $I->assertEquals($errors[RestCheckoutErrorTransfer::DETAIL], static::RESPONSE_DETAILS_CART_IS_EMPTY);
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\Checkout\CheckoutApiTester $I
     *
     * @return void
     */
    public function requestWithOneItemInQuoteAndInvoicePayment(CheckoutApiTester $I): void
    {
        // Arrange
        $customerTransfer = $this->fixtures->getGuestCustomerTransfer();
        $I->haveHttpHeader(
            static::HEADER_ANONYMOUS_CUSTOMER_UNIQUE_ID,
            $this->fixtures->getGuestCustomerReference()
        );

        $quoteTransfer = $I->createPersistentQuote($customerTransfer, [$this->fixtures->getProductConcreteTransfer()]);
        $shippingAddress = $quoteTransfer->getItems()[0]->getShipment()->getShippingAddress();

        $url = $I->buildCheckoutUrl();
        $urlParams = [
            'data' => [
                'type' => CheckoutRestApiConfig::RESOURCE_CHECKOUT,
                'attributes' => [
                    'idCart' => $quoteTransfer->getUuid(),
                    'billingAddress' => $this->getAddressParamData($quoteTransfer->getBillingAddress()),
                    'shippingAddress' => $this->getAddressParamData($shippingAddress),
                    'customer' => $this->getCustomerParamData($customerTransfer),
                    'payments' => [
                        [
                            RestPaymentTransfer::PAYMENT_METHOD_NAME => 'invoice',
                            RestPaymentTransfer::PAYMENT_PROVIDER_NAME => 'DummyPayment',
                        ],
                    ],
                    'shipment' => [
                        ShipmentMethodTransfer::ID_SHIPMENT_METHOD => 1,
                    ],
                ],
            ],
        ];

        // Act
        $I->sendPOST($url, $urlParams);

        // Assert
        $this->assertResponseHasCorrectInfrastructure($I);

        $I->amSure('The returned resource is of correct type')
            ->whenI()
            ->seeResponseDataContainsSingleResourceOfType(CheckoutRestApiConfig::RESOURCE_CHECKOUT);

        $this->assertResponseResourceHasCorrectData($I);

        $I->amSure('The returned resource has correct self link')
            ->whenI()
            ->seeSingleResourceHasSelfLink($I->buildCheckoutUrl());
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\Checkout\CheckoutApiTester $I
     *
     * @return void
     */
    public function requestWithOneItemInQuoteAndCreditCardPayment(CheckoutApiTester $I): void
    {
        // Arrange
        $customerTransfer = $this->fixtures->getGuestCustomerTransfer();
        $I->haveHttpHeader(
            static::HEADER_ANONYMOUS_CUSTOMER_UNIQUE_ID,
            $this->fixtures->getGuestCustomerReference()
        );

        $quoteTransfer = $I->createPersistentQuote($customerTransfer, [$this->fixtures->getProductConcreteTransfer()]);
        $shippingAddress = $quoteTransfer->getItems()[0]->getShipment()->getShippingAddress();

        $url = $I->buildCheckoutUrl();
        $urlParams = [
            'data' => [
                'type' => CheckoutRestApiConfig::RESOURCE_CHECKOUT,
                'attributes' => [
                    'idCart' => $quoteTransfer->getUuid(),
                    'billingAddress' => $this->getAddressParamData($quoteTransfer->getBillingAddress()),
                    'shippingAddress' => $this->getAddressParamData($shippingAddress),
                    'customer' => $this->getCustomerParamData($customerTransfer),
                    'payments' => [
                        [
                            RestPaymentTransfer::PAYMENT_METHOD_NAME => 'credit card',
                            RestPaymentTransfer::PAYMENT_PROVIDER_NAME => 'DummyPayment',
                        ],
                    ],
                    'shipment' => [
                        ShipmentMethodTransfer::ID_SHIPMENT_METHOD => 1,
                    ],
                ],
            ],
        ];

        // Act
        $I->sendPOST($url, $urlParams);

        // Assert
        $this->assertResponseHasCorrectInfrastructure($I);

        $I->amSure('The returned resource is of correct type')
            ->whenI()
            ->seeResponseDataContainsSingleResourceOfType(CheckoutRestApiConfig::RESOURCE_CHECKOUT);

        $this->assertResponseResourceHasCorrectData($I);

        $I->amSure('The returned resource has correct self link')
            ->whenI()
            ->seeSingleResourceHasSelfLink($I->buildCheckoutUrl());
    }

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     *
     * @return array
     */
    protected function getAddressParamData(AddressTransfer $addressTransfer): array
    {
        return [
            AddressTransfer::SALUTATION => $addressTransfer->getSalutation(),
            AddressTransfer::FIRST_NAME => $addressTransfer->getFirstName(),
            AddressTransfer::LAST_NAME => $addressTransfer->getLastName(),
            AddressTransfer::ADDRESS1 => $addressTransfer->getAddress1(),
            AddressTransfer::ADDRESS2 => $addressTransfer->getAddress2(),
            AddressTransfer::ADDRESS3 => $addressTransfer->getAddress3(),
            AddressTransfer::ZIP_CODE => $addressTransfer->getZipCode(),
            AddressTransfer::CITY => $addressTransfer->getCity(),
            AddressTransfer::ISO2_CODE => $addressTransfer->getIso2Code(),
            AddressTransfer::PHONE => $addressTransfer->getPhone(),
            AddressTransfer::EMAIL => $addressTransfer->getEmail(),
            AddressTransfer::IS_DEFAULT_BILLING => $addressTransfer->getIsDefaultBilling(),
            AddressTransfer::IS_DEFAULT_SHIPPING => $addressTransfer->getIsDefaultShipping(),
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return array
     */
    protected function getCustomerParamData(CustomerTransfer $customerTransfer): array
    {
        return [
            CustomerTransfer::SALUTATION => $customerTransfer->getSalutation(),
            CustomerTransfer::FIRST_NAME => $customerTransfer->getFirstName(),
            CustomerTransfer::LAST_NAME => $customerTransfer->getLastName(),
            CustomerTransfer::EMAIL => $customerTransfer->getEmail(),
        ];
    }

    /**
     * @param \PyzTest\Glue\Checkout\CheckoutApiTester $I
     * @param \Generated\Shared\Transfer\CustomerTransfer|null $customerTransfer
     *
     * @return void
     */
    protected function authorizeCustomerToGlue(CheckoutApiTester $I, ?CustomerTransfer $customerTransfer = null): void
    {
        if ($customerTransfer === null) {
            $customerTransfer = $this->fixtures->getCustomerTransfer();
        }

        $oauthResponseTransfer = $I->haveAuthorizationToGlue($customerTransfer);
        $I->amBearerAuthenticated($oauthResponseTransfer->getAccessToken());
    }

    /**
     * @param \PyzTest\Glue\Checkout\CheckoutApiTester $I
     * @param int $httpCode
     *
     * @return void
     */
    protected function assertResponseHasCorrectInfrastructure(CheckoutApiTester $I, int $httpCode = HttpCode::CREATED): void
    {
        $I->seeResponseCodeIs($httpCode);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesOpenApiSchema();
    }

    /**
     * @param \PyzTest\Glue\Checkout\CheckoutApiTester $I
     *
     * @return void
     */
    protected function assertResponseResourceHasCorrectData(CheckoutApiTester $I): void
    {
        $idResource = $I->amSure('I\'m taking the the returned resource id')
            ->whenI()
            ->grabDataFromResponseByJsonPath('$.data.id');
        $I->assertNull($idResource, 'The returned resource id should be null');

        $attributes = $I->amSure('I\'m taking the attributes from the returned resource')
            ->whenI()
            ->grabDataFromResponseByJsonPath('$.data.attributes');
        $I->assertNotEmpty($attributes[RestCheckoutResponseTransfer::ORDER_REFERENCE], 'The returned resource attributes order reference should not be empty');
        $I->assertArrayHasKey(RestCheckoutResponseTransfer::IS_EXTERNAL_REDIRECT, $attributes, 'The returned resource attributes should have an external redirect key');
        $I->assertArrayHasKey(RestCheckoutResponseTransfer::REDIRECT_URL, $attributes, 'The returned resource attributes should have a redirect URL key');
    }
}
