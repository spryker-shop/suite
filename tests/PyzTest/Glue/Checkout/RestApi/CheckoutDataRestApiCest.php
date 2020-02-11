<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Glue\Checkout\RestApi;

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
        $this->fixtures = $I->loadFixtures(CheckoutDataRestApiFixtures::class);
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
        $I->authorizeCustomerToGlue($this->fixtures->getCustomerTransfer());

        $quoteTransfer = $this->fixtures->getQuoteTransfer();

        $url = $I->buildCheckoutDataUrl();
        $urlParams = [
            'data' => [
                'type' => CheckoutRestApiConfig::RESOURCE_CHECKOUT_DATA,
                'attributes' => [
                    'idCart' => $quoteTransfer->getUuid(),
                    'payments' => [],
                ],
            ],
        ];

        // Act
        $I->sendPOST($url, $urlParams);

        // Assert
        $I->assertResponseHasCorrectInfrastructure();

        $I->amSure('The returned resource is of correct type')
            ->whenI()
            ->seeResponseDataContainsSingleResourceOfType(CheckoutRestApiConfig::RESOURCE_CHECKOUT_DATA);

        $I->assertCheckoutDataResponseResourceHasCorrectData();

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
    public function requestEmptyRequestWithOneItemInQuoteAndEmptyBody(CheckoutApiTester $I): void
    {
        // Arrange
        $I->authorizeCustomerToGlue($this->fixtures->getCustomerTransfer());

        $quoteTransfer = $this->fixtures->getQuoteTransfer();

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
        $I->assertResponseHasCorrectInfrastructure();

        $I->amSure('The returned resource is of correct type')
            ->whenI()
            ->seeResponseDataContainsSingleResourceOfType(CheckoutRestApiConfig::RESOURCE_CHECKOUT_DATA);

        $I->assertCheckoutDataResponseResourceHasCorrectData();

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
    public function requestWithOneItemInQuoteAndBillingAddress(CheckoutApiTester $I): void
    {
        // Arrange
        $I->authorizeCustomerToGlue($this->fixtures->getCustomerTransfer());

        $quoteTransfer = $this->fixtures->getQuoteTransfer();

        $url = $I->buildCheckoutDataUrl();
        $urlParams = [
            'data' => [
                'type' => CheckoutRestApiConfig::RESOURCE_CHECKOUT_DATA,
                'attributes' => [
                    'idCart' => $quoteTransfer->getUuid(),
                    'billingAddress' => $I->getAddressRequestParams($quoteTransfer->getBillingAddress()),
                ],
            ],
        ];

        // Act
        $I->sendPOST($url, $urlParams);

        // Assert
        $I->assertResponseHasCorrectInfrastructure();

        $I->amSure('The returned resource is of correct type')
            ->whenI()
            ->seeResponseDataContainsSingleResourceOfType(CheckoutRestApiConfig::RESOURCE_CHECKOUT_DATA);

        $I->assertCheckoutDataResponseResourceHasCorrectData();

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
        $I->authorizeCustomerToGlue($this->fixtures->getCustomerTransfer());

        $quoteTransfer = $this->fixtures->getQuoteTransfer();
        $shippingAddressTransfer = $quoteTransfer->getItems()[0]->getShipment()->getShippingAddress();

        $url = $I->buildCheckoutDataUrl();
        $urlParams = [
            'data' => [
                'type' => CheckoutRestApiConfig::RESOURCE_CHECKOUT_DATA,
                'attributes' => [
                    'idCart' => $quoteTransfer->getUuid(),
                    'billingAddress' => $I->getAddressRequestParams($quoteTransfer->getBillingAddress()),
                    'shippingAddress' => $I->getAddressRequestParams($shippingAddressTransfer),
                    'shipment' => $I->getShipmentRequestParams(),
                ],
            ],
        ];

        // Act
        $I->sendPOST($url, $urlParams);

        // Assert
        $I->assertResponseHasCorrectInfrastructure();

        $I->amSure('The returned resource is of correct type')
            ->whenI()
            ->seeResponseDataContainsSingleResourceOfType(CheckoutRestApiConfig::RESOURCE_CHECKOUT_DATA);

        $I->assertCheckoutDataResponseResourceHasCorrectData();

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
        $I->authorizeCustomerToGlue($this->fixtures->getCustomerTransfer());

        $quoteTransfer = $this->fixtures->getQuoteTransfer();
        $shippingAddressTransfer = $quoteTransfer->getItems()[0]->getShipment()->getShippingAddress();

        $url = $I->buildCheckoutDataUrl();
        $urlParams = [
            'data' => [
                'type' => CheckoutRestApiConfig::RESOURCE_CHECKOUT_DATA,
                'attributes' => [
                    'idCart' => $quoteTransfer->getUuid(),
                    'billingAddress' => $I->getAddressRequestParams($quoteTransfer->getBillingAddress()),
                    'shippingAddress' => $I->getAddressRequestParams($shippingAddressTransfer),
                    'payments' => $I->getPaymentRequestParams(),
                ],
            ],
        ];

        // Act
        $I->sendPOST($url, $urlParams);

        // Assert
        $I->assertResponseHasCorrectInfrastructure();

        $I->amSure('The returned resource is of correct type')
            ->whenI()
            ->seeResponseDataContainsSingleResourceOfType(CheckoutRestApiConfig::RESOURCE_CHECKOUT_DATA);

        $I->assertCheckoutDataResponseResourceHasCorrectData();

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
    public function requestWithOneItemInQuoteWithoutVoucherCode(CheckoutApiTester $I): void
    {
        // Arrange
        $I->authorizeCustomerToGlue($this->fixtures->getCustomerTransfer());

        $quoteTransfer = $this->fixtures->getQuoteTransfer();
        $shippingAddressTransfer = $quoteTransfer->getItems()[0]->getShipment()->getShippingAddress();

        $url = $I->buildCheckoutDataUrl();
        $urlParams = [
            'data' => [
                'type' => CheckoutRestApiConfig::RESOURCE_CHECKOUT_DATA,
                'attributes' => [
                    'idCart' => $quoteTransfer->getUuid(),
                    'billingAddress' => $I->getAddressRequestParams($quoteTransfer->getBillingAddress()),
                    'shippingAddress' => $I->getAddressRequestParams($shippingAddressTransfer),
                    'payments' => [],
                    'shipment' => $I->getShipmentRequestParams(),
                ],
            ],
        ];

        // Act
        $I->sendPOST($url, $urlParams);

        // Assert
        $I->assertResponseHasCorrectInfrastructure();

        $I->amSure('The returned resource is of correct type')
            ->whenI()
            ->seeResponseDataContainsSingleResourceOfType(CheckoutRestApiConfig::RESOURCE_CHECKOUT_DATA);

        $I->assertCheckoutDataResponseResourceHasCorrectData();

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
        $I->authorizeCustomerToGlue($this->fixtures->getCustomerTransfer());

        $quoteTransfer = $this->fixtures->getQuoteTransfer();
        $shippingAddressTransfer = $quoteTransfer->getItems()[0]->getShipment()->getShippingAddress();

        $url = $I->buildCheckoutDataUrl();
        $urlParams = [
            'data' => [
                'type' => CheckoutRestApiConfig::RESOURCE_CHECKOUT_DATA,
                'attributes' => [
                    'idCart' => $quoteTransfer->getUuid(),
                    'billingAddress' => $I->getAddressRequestParams($quoteTransfer->getBillingAddress()),
                    'shippingAddress' => $I->getAddressRequestParams($shippingAddressTransfer),
                    'customer' => $I->getCustomerRequestParams($this->fixtures->getCustomerTransfer()),
                ],
            ],
        ];

        // Act
        $I->sendPOST($url, $urlParams);

        // Assert
        $I->assertResponseHasCorrectInfrastructure();

        $I->amSure('The returned resource is of correct type')
            ->whenI()
            ->seeResponseDataContainsSingleResourceOfType(CheckoutRestApiConfig::RESOURCE_CHECKOUT_DATA);

        $I->assertCheckoutDataResponseResourceHasCorrectData();

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
        $I->authorizeCustomerToGlue($this->fixtures->getCustomerTransfer());

        $quoteTransfer = $this->fixtures->getQuoteTransfer();
        $shippingAddressTransfer = $quoteTransfer->getItems()[0]->getShipment()->getShippingAddress();

        $url = $I->buildCheckoutDataUrl();
        $urlParams = [
            'data' => [
                'type' => CheckoutRestApiConfig::RESOURCE_CHECKOUT_DATA,
                'attributes' => [
                    'idCart' => $quoteTransfer->getUuid(),
                    'billingAddress' => $I->getAddressRequestParams($quoteTransfer->getBillingAddress()),
                    'shippingAddress' => $I->getAddressRequestParams($shippingAddressTransfer),
                    'customer' => $I->getCustomerRequestParams($this->fixtures->getCustomerTransfer()),
                    'payments' => $I->getPaymentRequestParams(),
                    'shipment' => $I->getShipmentRequestParams(),
                ],
            ],
        ];

        // Act
        $I->sendPOST($url, $urlParams);

        // Assert
        $I->assertResponseHasCorrectInfrastructure();

        $I->amSure('The returned resource is of correct type')
            ->whenI()
            ->seeResponseDataContainsSingleResourceOfType(CheckoutRestApiConfig::RESOURCE_CHECKOUT_DATA);

        $I->assertCheckoutDataResponseResourceHasCorrectData();

        $I->amSure('The returned resource has correct self link')
            ->whenI()
            ->seeSingleResourceHasSelfLink($I->buildCheckoutDataUrl());
    }
}
