<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Glue\Checkout\RestApi;

use Codeception\Util\HttpCode;
use Generated\Shared\Transfer\RestCheckoutDataTransfer;
use PyzTest\Glue\Checkout\CheckoutApiTester;
use PyzTest\Glue\Checkout\RestApi\Fixtures\CheckoutDataRestApiFixtures;
use Spryker\Glue\CheckoutRestApi\CheckoutRestApiConfig;

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
    /**
     * @var \PyzTest\Glue\Checkout\RestApi\Fixtures\CheckoutDataRestApiFixtures
     */
    protected $fixtures;

    /**
     * @param \PyzTest\Glue\Checkout\CheckoutApiTester $I
     *
     * @return void
     */
    public function loadFixtures(CheckoutApiTester $I): void
    {
        /** @var \PyzTest\Glue\Checkout\RestApi\Fixtures\CheckoutDataRestApiFixtures $fixtures */
        $this->fixtures = $I->loadFixtures(CheckoutDataRestApiFixtures::class);
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
        $I->authorizeCustomerToGlue($this->fixtures->getCustomerTransfer());

        $quoteTransfer = $this->fixtures->getQuoteTransfer();

        $url = $I->buildCheckoutDataUrl();
        $requestPayload = [
            'data' => [
                'type' => CheckoutRestApiConfig::RESOURCE_CHECKOUT_DATA,
                'attributes' => [
                    'idCart' => $quoteTransfer->getUuid(),
                    'payments' => [],
                ],
            ],
        ];

        // Act
        $I->sendPOST($url, $requestPayload);

        // Assert
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesOpenApiSchema();

        $I->amSure('The returned resource is of correct type')
            ->whenI()
            ->seeResponseDataContainsSingleResourceOfType(CheckoutRestApiConfig::RESOURCE_CHECKOUT_DATA);

        $idResource = $I->grabDataFromResponseByJsonPath('$.data.id');
        $I->assertNull($idResource, 'The returned resource id should be null');

        $attributes = $I->grabDataFromResponseByJsonPath('$.data.attributes');

        $I->assertEmpty(
            $attributes[RestCheckoutDataTransfer::ADDRESSES],
            'The returned resource attributes addresses should be an empty array'
        );
        $I->assertNotEmpty(
            $attributes[RestCheckoutDataTransfer::PAYMENT_PROVIDERS],
            'The returned resource attributes payment providers should not be an empty array'
        );
        $I->assertNotEmpty(
            $attributes[RestCheckoutDataTransfer::SHIPMENT_METHODS],
            'The returned resource attributes shipment methods should not be an empty array'
        );

        $I->amSure('The returned resource has correct self link')
            ->whenI()
            ->seeSingleResourceHasSelfLink($url);
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\Checkout\CheckoutApiTester $I
     *
     * @return void
     */
    public function requestEmptyRequestWithOneItemInQuoteAndEmptyBody(CheckoutApiTester $I): void
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
                ],
            ],
        ];

        // Act
        $I->sendPOST($url, $requestPayload);

        // Assert
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesOpenApiSchema();

        $I->amSure('The returned resource is of correct type')
            ->whenI()
            ->seeResponseDataContainsSingleResourceOfType(CheckoutRestApiConfig::RESOURCE_CHECKOUT_DATA);

        $idResource = $I->grabDataFromResponseByJsonPath('$.data.id');
        $I->assertNull($idResource, 'The returned resource id should be null');

        $attributes = $I->grabDataFromResponseByJsonPath('$.data.attributes');

        $I->assertEmpty(
            $attributes[RestCheckoutDataTransfer::ADDRESSES],
            'The returned resource attributes addresses should be an empty array'
        );
        $I->assertNotEmpty(
            $attributes[RestCheckoutDataTransfer::PAYMENT_PROVIDERS],
            'The returned resource attributes payment providers should not be an empty array'
        );
        $I->assertNotEmpty(
            $attributes[RestCheckoutDataTransfer::SHIPMENT_METHODS],
            'The returned resource attributes shipment methods should not be an empty array'
        );

        $I->amSure('The returned resource has correct self link')
            ->whenI()
            ->seeSingleResourceHasSelfLink($url);
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\Checkout\CheckoutApiTester $I
     *
     * @return void
     */
    public function requestWithOneItemInQuoteAndBillingAddress(CheckoutApiTester $I): void
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
                    'billingAddress' => $I->getAddressRequestPayload($quoteTransfer->getBillingAddress()),
                ],
            ],
        ];

        // Act
        $I->sendPOST($url, $requestPayload);

        // Assert
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesOpenApiSchema();

        $I->amSure('The returned resource is of correct type')
            ->whenI()
            ->seeResponseDataContainsSingleResourceOfType(CheckoutRestApiConfig::RESOURCE_CHECKOUT_DATA);

        $idResource = $I->grabDataFromResponseByJsonPath('$.data.id');
        $I->assertNull($idResource, 'The returned resource id should be null');

        $attributes = $I->grabDataFromResponseByJsonPath('$.data.attributes');

        $I->assertEmpty(
            $attributes[RestCheckoutDataTransfer::ADDRESSES],
            'The returned resource attributes addresses should be an empty array'
        );
        $I->assertNotEmpty(
            $attributes[RestCheckoutDataTransfer::PAYMENT_PROVIDERS],
            'The returned resource attributes payment providers should not be an empty array'
        );
        $I->assertNotEmpty(
            $attributes[RestCheckoutDataTransfer::SHIPMENT_METHODS],
            'The returned resource attributes shipment methods should not be an empty array'
        );

        $I->amSure('The returned resource has correct self link')
            ->whenI()
            ->seeSingleResourceHasSelfLink($url);
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
        $I->authorizeCustomerToGlue($this->fixtures->getCustomerTransfer());

        $quoteTransfer = $this->fixtures->getQuoteTransfer();
        $shippingAddressTransfer = $quoteTransfer->getItems()[0]->getShipment()->getShippingAddress();

        $url = $I->buildCheckoutDataUrl();
        $requestPayload = [
            'data' => [
                'type' => CheckoutRestApiConfig::RESOURCE_CHECKOUT_DATA,
                'attributes' => [
                    'idCart' => $quoteTransfer->getUuid(),
                    'billingAddress' => $I->getAddressRequestPayload($quoteTransfer->getBillingAddress()),
                    'shippingAddress' => $I->getAddressRequestPayload($shippingAddressTransfer),
                    'shipment' => $I->getShipmentRequestPayload(),
                ],
            ],
        ];

        // Act
        $I->sendPOST($url, $requestPayload);

        // Assert
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesOpenApiSchema();

        $I->amSure('The returned resource is of correct type')
            ->whenI()
            ->seeResponseDataContainsSingleResourceOfType(CheckoutRestApiConfig::RESOURCE_CHECKOUT_DATA);

        $idResource = $I->grabDataFromResponseByJsonPath('$.data.id');
        $I->assertNull($idResource, 'The returned resource id should be null');

        $attributes = $I->grabDataFromResponseByJsonPath('$.data.attributes');

        $I->assertEmpty(
            $attributes[RestCheckoutDataTransfer::ADDRESSES],
            'The returned resource attributes addresses should be an empty array'
        );
        $I->assertNotEmpty(
            $attributes[RestCheckoutDataTransfer::PAYMENT_PROVIDERS],
            'The returned resource attributes payment providers should not be an empty array'
        );
        $I->assertNotEmpty(
            $attributes[RestCheckoutDataTransfer::SHIPMENT_METHODS],
            'The returned resource attributes shipment methods should not be an empty array'
        );

        $I->amSure('The returned resource has correct self link')
            ->whenI()
            ->seeSingleResourceHasSelfLink($url);
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
        $I->authorizeCustomerToGlue($this->fixtures->getCustomerTransfer());

        $quoteTransfer = $this->fixtures->getQuoteTransfer();
        $shippingAddressTransfer = $quoteTransfer->getItems()[0]->getShipment()->getShippingAddress();

        $url = $I->buildCheckoutDataUrl();
        $requestPayload = [
            'data' => [
                'type' => CheckoutRestApiConfig::RESOURCE_CHECKOUT_DATA,
                'attributes' => [
                    'idCart' => $quoteTransfer->getUuid(),
                    'billingAddress' => $I->getAddressRequestPayload($quoteTransfer->getBillingAddress()),
                    'shippingAddress' => $I->getAddressRequestPayload($shippingAddressTransfer),
                    'payments' => $I->getPaymentRequestPayload(),
                ],
            ],
        ];

        // Act
        $I->sendPOST($url, $requestPayload);

        // Assert
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesOpenApiSchema();

        $I->amSure('The returned resource is of correct type')
            ->whenI()
            ->seeResponseDataContainsSingleResourceOfType(CheckoutRestApiConfig::RESOURCE_CHECKOUT_DATA);

        $idResource = $I->grabDataFromResponseByJsonPath('$.data.id');
        $I->assertNull($idResource, 'The returned resource id should be null');

        $attributes = $I->grabDataFromResponseByJsonPath('$.data.attributes');

        $I->assertEmpty(
            $attributes[RestCheckoutDataTransfer::ADDRESSES],
            'The returned resource attributes addresses should be an empty array'
        );
        $I->assertNotEmpty(
            $attributes[RestCheckoutDataTransfer::PAYMENT_PROVIDERS],
            'The returned resource attributes payment providers should not be an empty array'
        );
        $I->assertNotEmpty(
            $attributes[RestCheckoutDataTransfer::SHIPMENT_METHODS],
            'The returned resource attributes shipment methods should not be an empty array'
        );

        $I->amSure('The returned resource has correct self link')
            ->whenI()
            ->seeSingleResourceHasSelfLink($url);
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\Checkout\CheckoutApiTester $I
     *
     * @return void
     */
    public function requestWithOneItemInQuoteWithoutVoucherCode(CheckoutApiTester $I): void
    {
        // Arrange
        $I->authorizeCustomerToGlue($this->fixtures->getCustomerTransfer());

        $quoteTransfer = $this->fixtures->getQuoteTransfer();
        $shippingAddressTransfer = $quoteTransfer->getItems()[0]->getShipment()->getShippingAddress();

        $url = $I->buildCheckoutDataUrl();
        $requestPayload = [
            'data' => [
                'type' => CheckoutRestApiConfig::RESOURCE_CHECKOUT_DATA,
                'attributes' => [
                    'idCart' => $quoteTransfer->getUuid(),
                    'billingAddress' => $I->getAddressRequestPayload($quoteTransfer->getBillingAddress()),
                    'shippingAddress' => $I->getAddressRequestPayload($shippingAddressTransfer),
                    'payments' => [],
                    'shipment' => $I->getShipmentRequestPayload(),
                ],
            ],
        ];

        // Act
        $I->sendPOST($url, $requestPayload);

        // Assert
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesOpenApiSchema();

        $I->amSure('The returned resource is of correct type')
            ->whenI()
            ->seeResponseDataContainsSingleResourceOfType(CheckoutRestApiConfig::RESOURCE_CHECKOUT_DATA);

        $idResource = $I->grabDataFromResponseByJsonPath('$.data.id');
        $I->assertNull($idResource, 'The returned resource id should be null');

        $attributes = $I->grabDataFromResponseByJsonPath('$.data.attributes');

        $I->assertEmpty(
            $attributes[RestCheckoutDataTransfer::ADDRESSES],
            'The returned resource attributes addresses should be an empty array'
        );
        $I->assertNotEmpty(
            $attributes[RestCheckoutDataTransfer::PAYMENT_PROVIDERS],
            'The returned resource attributes payment providers should not be an empty array'
        );
        $I->assertNotEmpty(
            $attributes[RestCheckoutDataTransfer::SHIPMENT_METHODS],
            'The returned resource attributes shipment methods should not be an empty array'
        );

        $I->amSure('The returned resource has correct self link')
            ->whenI()
            ->seeSingleResourceHasSelfLink($url);
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
        $I->authorizeCustomerToGlue($this->fixtures->getCustomerTransfer());

        $quoteTransfer = $this->fixtures->getQuoteTransfer();
        $shippingAddressTransfer = $quoteTransfer->getItems()[0]->getShipment()->getShippingAddress();

        $url = $I->buildCheckoutDataUrl();
        $requestPayload = [
            'data' => [
                'type' => CheckoutRestApiConfig::RESOURCE_CHECKOUT_DATA,
                'attributes' => [
                    'idCart' => $quoteTransfer->getUuid(),
                    'billingAddress' => $I->getAddressRequestPayload($quoteTransfer->getBillingAddress()),
                    'shippingAddress' => $I->getAddressRequestPayload($shippingAddressTransfer),
                    'customer' => $I->getCustomerRequestPayload($this->fixtures->getCustomerTransfer()),
                ],
            ],
        ];

        // Act
        $I->sendPOST($url, $requestPayload);

        // Assert
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesOpenApiSchema();

        $I->amSure('The returned resource is of correct type')
            ->whenI()
            ->seeResponseDataContainsSingleResourceOfType(CheckoutRestApiConfig::RESOURCE_CHECKOUT_DATA);

        $idResource = $I->grabDataFromResponseByJsonPath('$.data.id');
        $I->assertNull($idResource, 'The returned resource id should be null');

        $attributes = $I->grabDataFromResponseByJsonPath('$.data.attributes');

        $I->assertEmpty(
            $attributes[RestCheckoutDataTransfer::ADDRESSES],
            'The returned resource attributes addresses should be an empty array'
        );
        $I->assertNotEmpty(
            $attributes[RestCheckoutDataTransfer::PAYMENT_PROVIDERS],
            'The returned resource attributes payment providers should not be an empty array'
        );
        $I->assertNotEmpty(
            $attributes[RestCheckoutDataTransfer::SHIPMENT_METHODS],
            'The returned resource attributes shipment methods should not be an empty array'
        );

        $I->amSure('The returned resource has correct self link')
            ->whenI()
            ->seeSingleResourceHasSelfLink($url);
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
        $I->authorizeCustomerToGlue($this->fixtures->getCustomerTransfer());

        $quoteTransfer = $this->fixtures->getQuoteTransfer();
        $shippingAddressTransfer = $quoteTransfer->getItems()[0]->getShipment()->getShippingAddress();

        $url = $I->buildCheckoutDataUrl();
        $requestPayload = [
            'data' => [
                'type' => CheckoutRestApiConfig::RESOURCE_CHECKOUT_DATA,
                'attributes' => [
                    'idCart' => $quoteTransfer->getUuid(),
                    'billingAddress' => $I->getAddressRequestPayload($quoteTransfer->getBillingAddress()),
                    'shippingAddress' => $I->getAddressRequestPayload($shippingAddressTransfer),
                    'customer' => $I->getCustomerRequestPayload($this->fixtures->getCustomerTransfer()),
                    'payments' => $I->getPaymentRequestPayload(),
                    'shipment' => $I->getShipmentRequestPayload(),
                ],
            ],
        ];

        // Act
        $I->sendPOST($url, $requestPayload);

        // Assert
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesOpenApiSchema();

        $I->amSure('The returned resource is of correct type')
            ->whenI()
            ->seeResponseDataContainsSingleResourceOfType(CheckoutRestApiConfig::RESOURCE_CHECKOUT_DATA);

        $idResource = $I->grabDataFromResponseByJsonPath('$.data.id');
        $I->assertNull($idResource, 'The returned resource id should be null');

        $attributes = $I->grabDataFromResponseByJsonPath('$.data.attributes');

        $I->assertEmpty(
            $attributes[RestCheckoutDataTransfer::ADDRESSES],
            'The returned resource attributes addresses should be an empty array'
        );
        $I->assertNotEmpty(
            $attributes[RestCheckoutDataTransfer::PAYMENT_PROVIDERS],
            'The returned resource attributes payment providers should not be an empty array'
        );
        $I->assertNotEmpty(
            $attributes[RestCheckoutDataTransfer::SHIPMENT_METHODS],
            'The returned resource attributes shipment methods should not be an empty array'
        );

        $I->amSure('The returned resource has correct self link')
            ->whenI()
            ->seeSingleResourceHasSelfLink($url);
    }
}
