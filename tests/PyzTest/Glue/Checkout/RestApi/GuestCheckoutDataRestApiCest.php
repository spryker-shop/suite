<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Glue\Checkout\RestApi;

use Codeception\Util\HttpCode;
use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\RestCheckoutDataTransfer;
use Generated\Shared\Transfer\RestPaymentTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use PyzTest\Glue\Checkout\CheckoutApiTester;
use PyzTest\Glue\Checkout\RestApi\Fixtures\GuestCheckoutDataRestApiFixtures;
use Spryker\Glue\CheckoutRestApi\CheckoutRestApiConfig;

/**
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Glue
 * @group Checkout
 * @group RestApi
 * @group GuestCheckoutDataRestApiCest
 * Add your own group annotations below this line
 * @group EndToEnd
 */
class GuestCheckoutDataRestApiCest
{
    protected const HEADER_ANONYMOUS_CUSTOMER_UNIQUE_ID = 'X-Anonymous-Customer-Unique-Id';

    /**
     * @var \PyzTest\Glue\Checkout\RestApi\Fixtures\GuestCheckoutDataRestApiFixtures
     */
    protected $fixtures;

    /**
     * @param \PyzTest\Glue\Checkout\CheckoutApiTester $I
     *
     * @return void
     */
    public function loadFixtures(CheckoutApiTester $I): void
    {
        $this->fixtures = $I->loadFixtures(GuestCheckoutDataRestApiFixtures::class);
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
    public function requestEmptyRequestWithOneItemInQuote(CheckoutApiTester $I): void
    {
        // Arrange
        $quoteTransfer = $this->fixtures->getGuestQuoteTransfer();
        $I->haveHttpHeader(
            static::HEADER_ANONYMOUS_CUSTOMER_UNIQUE_ID,
            $this->fixtures->getGuestCustomerReference()
        );

        $url = $I->buildCheckoutDataUrl();
        $urlParams = [
            'data' => [
                'type' => CheckoutRestApiConfig::RESOURCE_CHECKOUT_DATA,
                'attributes' => [
                    'idCart' => $quoteTransfer->getUuid(),
                ],
            ],
        ];

        // Act
        $I->sendPOST($url, $urlParams);

        // Assert
        $this->assertResponseHasCorrectInfrastructure($I);

        $I->amSure('The returned resource is of correct type')
            ->whenI()
            ->seeResponseDataContainsSingleResourceOfType(CheckoutRestApiConfig::RESOURCE_CHECKOUT_DATA);

        $this->assertResponseResourceHasCorrectData($I);

        $I->amSure('The returned resource has correct self link')
            ->whenI()
            ->seeSingleResourceHasSelfLink($I->buildCheckoutDataUrl());
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\Checkout\CheckoutApiTester $I
     *
     * @return void
     */
    public function requestWithOneItemInQuoteAndBillingAndShippingAddresses(CheckoutApiTester $I): void
    {
        // Arrange
        $quoteTransfer = $this->fixtures->getGuestQuoteTransfer();
        $I->haveHttpHeader(
            static::HEADER_ANONYMOUS_CUSTOMER_UNIQUE_ID,
            $this->fixtures->getGuestCustomerReference()
        );
        $shippingAddress = $quoteTransfer->getItems()[0]->getShipment()->getShippingAddress();

        $url = $I->buildCheckoutDataUrl();
        $urlParams = [
            'data' => [
                'type' => CheckoutRestApiConfig::RESOURCE_CHECKOUT_DATA,
                'attributes' => [
                    'idCart' => $quoteTransfer->getUuid(),
                    'billingAddress' => $this->getAddressParamData($quoteTransfer->getBillingAddress()),
                    'shippingAddress' => $this->getAddressParamData($shippingAddress),
                ],
            ],
        ];

        // Act
        $I->sendPOST($url, $urlParams);

        // Assert
        $this->assertResponseHasCorrectInfrastructure($I);

        $I->amSure('The returned resource is of correct type')
            ->whenI()
            ->seeResponseDataContainsSingleResourceOfType(CheckoutRestApiConfig::RESOURCE_CHECKOUT_DATA);

        $this->assertResponseResourceHasCorrectData($I);

        $I->amSure('The returned resource has correct self link')
            ->whenI()
            ->seeSingleResourceHasSelfLink($I->buildCheckoutDataUrl());
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\Checkout\CheckoutApiTester $I
     *
     * @return void
     */
    public function requestWithOneItemInQuoteWithoutShipment(CheckoutApiTester $I): void
    {
        // Arrange
        $quoteTransfer = $this->fixtures->getGuestQuoteTransfer();
        $I->haveHttpHeader(
            static::HEADER_ANONYMOUS_CUSTOMER_UNIQUE_ID,
            $this->fixtures->getGuestCustomerReference()
        );
        $shippingAddress = $quoteTransfer->getItems()[0]->getShipment()->getShippingAddress();

        $url = $I->buildCheckoutDataUrl();
        $urlParams = [
            'data' => [
                'type' => CheckoutRestApiConfig::RESOURCE_CHECKOUT_DATA,
                'attributes' => [
                    'idCart' => $quoteTransfer->getUuid(),
                    'billingAddress' => $this->getAddressParamData($quoteTransfer->getBillingAddress()),
                    'shippingAddress' => $this->getAddressParamData($shippingAddress),
                    'payments' => [
                        [
                            RestPaymentTransfer::PAYMENT_METHOD_NAME => 'invoice',
                            RestPaymentTransfer::PAYMENT_PROVIDER_NAME => 'DummyPayment',
                        ],
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
            ->seeResponseDataContainsSingleResourceOfType(CheckoutRestApiConfig::RESOURCE_CHECKOUT_DATA);

        $this->assertResponseResourceHasCorrectData($I);

        $I->amSure('The returned resource has correct self link')
            ->whenI()
            ->seeSingleResourceHasSelfLink($I->buildCheckoutDataUrl());
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\Checkout\CheckoutApiTester $I
     *
     * @return void
     */
    public function requestWithOneItemInQuoteWithoutPayment(CheckoutApiTester $I): void
    {
        // Arrange
        $quoteTransfer = $this->fixtures->getGuestQuoteTransfer();
        $I->haveHttpHeader(
            static::HEADER_ANONYMOUS_CUSTOMER_UNIQUE_ID,
            $this->fixtures->getGuestCustomerReference()
        );
        $shippingAddress = $quoteTransfer->getItems()[0]->getShipment()->getShippingAddress();

        $url = $I->buildCheckoutDataUrl();
        $urlParams = [
            'data' => [
                'type' => CheckoutRestApiConfig::RESOURCE_CHECKOUT_DATA,
                'attributes' => [
                    'idCart' => $quoteTransfer->getUuid(),
                    'billingAddress' => $this->getAddressParamData($quoteTransfer->getBillingAddress()),
                    'shippingAddress' => $this->getAddressParamData($shippingAddress),
                    'shipment' => [ShipmentMethodTransfer::ID_SHIPMENT_METHOD => 1],
                ],
            ],
        ];

        // Act
        $I->sendPOST($url, $urlParams);

        // Assert
        $this->assertResponseHasCorrectInfrastructure($I);

        $I->amSure('The returned resource is of correct type')
            ->whenI()
            ->seeResponseDataContainsSingleResourceOfType(CheckoutRestApiConfig::RESOURCE_CHECKOUT_DATA);

        $this->assertResponseResourceHasCorrectData($I);

        $I->amSure('The returned resource has correct self link')
            ->whenI()
            ->seeSingleResourceHasSelfLink($I->buildCheckoutDataUrl());
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\Checkout\CheckoutApiTester $I
     *
     * @return void
     */
    public function requestWithOneItemInQuoteAndCustomerAndBillingAndShippingAddressesAndCart(CheckoutApiTester $I): void
    {
        // Arrange
        $quoteTransfer = $this->fixtures->getGuestQuoteTransfer();
        $I->haveHttpHeader(
            static::HEADER_ANONYMOUS_CUSTOMER_UNIQUE_ID,
            $this->fixtures->getGuestCustomerReference()
        );
        $shippingAddress = $quoteTransfer->getItems()[0]->getShipment()->getShippingAddress();

        $url = $I->buildCheckoutDataUrl();
        $urlParams = [
            'data' => [
                'type' => CheckoutRestApiConfig::RESOURCE_CHECKOUT_DATA,
                'attributes' => [
                    'idCart' => $quoteTransfer->getUuid(),
                    'billingAddress' => $this->getAddressParamData($quoteTransfer->getBillingAddress()),
                    'shippingAddress' => $this->getAddressParamData($shippingAddress),
                    'customer' => $this->getCustomerParamData($this->fixtures->getGuestCustomerTransfer()),
                ],
            ],
        ];

        // Act
        $I->sendPOST($url, $urlParams);

        // Assert
        $this->assertResponseHasCorrectInfrastructure($I);

        $I->amSure('The returned resource is of correct type')
            ->whenI()
            ->seeResponseDataContainsSingleResourceOfType(CheckoutRestApiConfig::RESOURCE_CHECKOUT_DATA);

        $this->assertResponseResourceHasCorrectData($I);

        $I->amSure('The returned resource has correct self link')
            ->whenI()
            ->seeSingleResourceHasSelfLink($I->buildCheckoutDataUrl());
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\Checkout\CheckoutApiTester $I
     *
     * @return void
     */
    public function requestWithOneItemInQuoteAndFullBody(CheckoutApiTester $I): void
    {
        // Arrange
        $quoteTransfer = $this->fixtures->getGuestQuoteTransfer();
        $I->haveHttpHeader(
            static::HEADER_ANONYMOUS_CUSTOMER_UNIQUE_ID,
            $this->fixtures->getGuestCustomerReference()
        );
        $shippingAddress = $quoteTransfer->getItems()[0]->getShipment()->getShippingAddress();

        $url = $I->buildCheckoutDataUrl();
        $urlParams = [
            'data' => [
                'type' => CheckoutRestApiConfig::RESOURCE_CHECKOUT_DATA,
                'attributes' => [
                    'idCart' => $quoteTransfer->getUuid(),
                    'billingAddress' => $this->getAddressParamData($quoteTransfer->getBillingAddress()),
                    'shippingAddress' => $this->getAddressParamData($shippingAddress),
                    'customer' => $this->getCustomerParamData($this->fixtures->getGuestCustomerTransfer()),
                    'payments' => [
                        [
                            RestPaymentTransfer::PAYMENT_METHOD_NAME => 'invoice',
                            RestPaymentTransfer::PAYMENT_PROVIDER_NAME => 'DummyPayment',
                        ],
                    ],
                    'shipment' => [ShipmentMethodTransfer::ID_SHIPMENT_METHOD => 1],
                ],
            ],
        ];

        // Act
        $I->sendPOST($url, $urlParams);

        // Assert
        $this->assertResponseHasCorrectInfrastructure($I);

        $I->amSure('The returned resource is of correct type')
            ->whenI()
            ->seeResponseDataContainsSingleResourceOfType(CheckoutRestApiConfig::RESOURCE_CHECKOUT_DATA);

        $this->assertResponseResourceHasCorrectData($I);

        $I->amSure('The returned resource has correct self link')
            ->whenI()
            ->seeSingleResourceHasSelfLink($I->buildCheckoutDataUrl());
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
        $I->assertEmpty($attributes[RestCheckoutDataTransfer::ADDRESSES], 'The returned resource attributes addresses should be an empty array');
        $I->assertNotEmpty($attributes[RestCheckoutDataTransfer::PAYMENT_PROVIDERS], 'The returned resource attributes payment providers should not be an empty array');
        $I->assertNotEmpty($attributes[RestCheckoutDataTransfer::SHIPMENT_METHODS], 'The returned resource attributes shipment methods should not be an empty array');
    }
}
