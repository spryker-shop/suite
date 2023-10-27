<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Glue\Checkout\RestApi;

use Codeception\Util\HttpCode;
use Generated\Shared\DataBuilder\CustomerBuilder;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RestCheckoutErrorTransfer;
use PyzTest\Glue\Checkout\CheckoutApiTester;
use PyzTest\Glue\Checkout\RestApi\Fixtures\ServicePointShipmentTypeCheckoutRestApiFixtures;
use Spryker\Glue\CheckoutRestApi\CheckoutRestApiConfig;

/**
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Glue
 * @group Checkout
 * @group RestApi
 * @group ServicePointShipmentTypeCheckoutRestApiCest
 * Add your own group annotations below this line
 * @group EndToEnd
 */
class ServicePointShipmentTypeCheckoutRestApiCest
{
    /**
     * @var string
     */
    protected const HEADER_ANONYMOUS_CUSTOMER_UNIQUE_ID = 'X-Anonymous-Customer-Unique-Id';

    /**
     * @uses \Spryker\Glue\CheckoutRestApi\CheckoutRestApiConfig::RESPONSE_CODE_CUSTOMER_DATA_MISSING
     *
     * @var string
     */
    protected const RESPONSE_CODE_CUSTOMER_DATA_MISSING = '1109';

    /**
     * @uses \Spryker\Glue\CheckoutRestApi\CheckoutRestApiConfig::RESPONSE_DETAILS_CUSTOMER_DATA_MISSING
     *
     * @var string
     */
    protected const RESPONSE_DETAILS_CUSTOMER_DATA_MISSING = 'Required customer information is missing from the request body.';

    /**
     * @var \PyzTest\Glue\Checkout\RestApi\Fixtures\ServicePointShipmentTypeCheckoutRestApiFixtures
     */
    protected ServicePointShipmentTypeCheckoutRestApiFixtures $fixtures;

    /**
     * @param \PyzTest\Glue\Checkout\CheckoutApiTester $I
     *
     * @return void
     */
    public function loadFixtures(CheckoutApiTester $I): void
    {
        /** @var \PyzTest\Glue\Checkout\RestApi\Fixtures\ServicePointShipmentTypeCheckoutRestApiFixtures $fixtures */
        $fixtures = $I->loadFixtures(ServicePointShipmentTypeCheckoutRestApiFixtures::class);
        $this->fixtures = $fixtures;
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\Checkout\CheckoutApiTester $I
     *
     * @return void
     */
    public function requestCheckoutWithSingleShipmentAndPickableShipmentMethod(CheckoutApiTester $I): void
    {
        $customerTransfer = $this->fixtures->getCustomerTransfer();
        $quoteTransfer = $I->havePersistentQuoteWithItems(
            $customerTransfer,
            $this->fixtures->getProductConcreteTransfers(),
        );
        $servicePointTransfer = $this->fixtures->getServicePointTransfer();
        $itemGroupKeys = $this->extractItemGroupKeys($quoteTransfer);

        $I->authorizeCustomerToGlue($customerTransfer);

        $overrideCustomerTransfer = (new CustomerBuilder())->build();
        $url = $I->buildCheckoutUrl();
        $requestPayload = [
            'data' => [
                'type' => CheckoutRestApiConfig::RESOURCE_CHECKOUT,
                'attributes' => [
                    'idCart' => $quoteTransfer->getUuidOrFail(),
                    'billingAddress' => $I->getAddressRequestPayload($quoteTransfer->getBillingAddressOrFail()),
                    'shippingAddress' => $I->getAddressRequestPayload($quoteTransfer->getBillingAddressOrFail()),
                    'customer' => $I->getCustomerRequestPayload($overrideCustomerTransfer),
                    'payments' => $I->getPaymentRequestPayload(),
                    'servicePoints' => $I->getServicePointsRequestPayload($servicePointTransfer->getUuidOrFail(), $itemGroupKeys),
                    'shipment' => $I->getShipmentRequestPayload($this->fixtures->getPickableShipmentMethodTransfer()->getIdShipmentMethodOrFail()),
                ],
            ],
        ];

        // Act
        $I->sendPOST($url, $requestPayload);

        // Assert
        $I->seeResponseCodeIs(HttpCode::CREATED);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesOpenApiSchema();

        $I->assertCheckoutResponseResourceHasCorrectData();

        $I->amSure('The returned resource has correct self link')
            ->whenI()
            ->seeSingleResourceHasSelfLink($url);

        $I->amSure('Service point address is persisted as order shipping address')
            ->whenI()
            ->assertSalesOrderAddressIsCorrectForItem(
                $servicePointTransfer->getAddressOrFail(),
                $overrideCustomerTransfer,
                $this->fixtures->getProductConcreteTransfers()[0]->getSkuOrFail(),
            );
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\Checkout\CheckoutApiTester $I
     *
     * @return void
     */
    public function requestCheckoutWithSplitShipmentAndPickableShipmentMethod(CheckoutApiTester $I): void
    {
        $customerTransfer = $this->fixtures->getCustomerTransfer();
        $servicePointTransfer = $this->fixtures->getServicePointTransfer();
        $quoteTransfer = $I->havePersistentQuoteWithItemsAndItemLevelShipment(
            $customerTransfer,
            [
                $I->getQuoteItemOverrideData($this->fixtures->getProductConcreteTransfers()[0], $this->fixtures->getPickableShipmentMethodTransfer(), 1),
                $I->getQuoteItemOverrideData($this->fixtures->getProductConcreteTransfers()[1], $this->fixtures->getRegularShipmentMethodTransfer(), 1),
            ],
        );
        $quoteTransfer = $I->getCartFacade()->reloadItems($quoteTransfer);

        $I->authorizeCustomerToGlue($customerTransfer);

        $overrideCustomerTransfer = (new CustomerBuilder())->build();
        $url = $I->buildCheckoutUrl();
        $requestPayload = [
            'data' => [
                'type' => CheckoutRestApiConfig::RESOURCE_CHECKOUT,
                'attributes' => [
                    'idCart' => $quoteTransfer->getUuidOrFail(),
                    'billingAddress' => $I->getAddressRequestPayload($quoteTransfer->getBillingAddressOrFail()),
                    'customer' => $I->getCustomerRequestPayload($overrideCustomerTransfer),
                    'payments' => $I->getPaymentRequestPayload(),
                    'servicePoints' => $I->getServicePointsRequestPayload(
                        $servicePointTransfer->getUuidOrFail(),
                        [$quoteTransfer->getItems()->offsetGet(0)->getGroupKeyOrFail()],
                    ),
                    'shipments' => [
                        $I->getSplitShipmentRequestPayload($quoteTransfer->getItems()->offsetGet(0)),
                        $I->getSplitShipmentRequestPayload($quoteTransfer->getItems()->offsetGet(1)),
                    ],
                ],
            ],
        ];

        // Act
        $I->sendPOST($url, $requestPayload);

        // Assert
        $I->seeResponseCodeIs(HttpCode::CREATED);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesOpenApiSchema();

        $I->assertCheckoutResponseResourceHasCorrectData();

        $I->amSure('The returned resource has correct self link')
            ->whenI()
            ->seeSingleResourceHasSelfLink($url);

        $I->amSure('Service point address is persisted as order shipping address')
            ->whenI()
            ->assertSalesOrderAddressIsCorrectForItem(
                $servicePointTransfer->getAddressOrFail(),
                $overrideCustomerTransfer,
                $this->fixtures->getProductConcreteTransfers()[0]->getSkuOrFail(),
            );
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\Checkout\CheckoutApiTester $I
     *
     * @return void
     */
    public function requestCheckoutWithSingleShipmentAndPickableShipmentMethodWithoutCustomerDataInRequestBody(CheckoutApiTester $I): void
    {
        $customerTransfer = $this->fixtures->getCustomerTransfer();
        $quoteTransfer = $I->havePersistentQuoteWithItems(
            $customerTransfer,
            $this->fixtures->getProductConcreteTransfers(),
        );
        $servicePointTransfer = $this->fixtures->getServicePointTransfer();
        $itemGroupKeys = $this->extractItemGroupKeys($quoteTransfer);

        $I->authorizeCustomerToGlue($customerTransfer);

        $url = $I->buildCheckoutUrl();
        $requestPayload = [
            'data' => [
                'type' => CheckoutRestApiConfig::RESOURCE_CHECKOUT,
                'attributes' => [
                    'idCart' => $quoteTransfer->getUuidOrFail(),
                    'billingAddress' => $I->getAddressRequestPayload($quoteTransfer->getBillingAddressOrFail()),
                    'shippingAddress' => $I->getAddressRequestPayload($quoteTransfer->getBillingAddressOrFail()),
                    'payments' => $I->getPaymentRequestPayload(),
                    'servicePoints' => $I->getServicePointsRequestPayload($servicePointTransfer->getUuidOrFail(), $itemGroupKeys),
                    'shipment' => $I->getShipmentRequestPayload($this->fixtures->getPickableShipmentMethodTransfer()->getIdShipmentMethodOrFail()),
                ],
            ],
        ];

        // Act
        $I->sendPOST($url, $requestPayload);

        // Assert
        $I->seeResponseCodeIs(HttpCode::CREATED);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesOpenApiSchema();

        $I->assertCheckoutResponseResourceHasCorrectData();

        $I->amSure('The returned resource has correct self link')
            ->whenI()
            ->seeSingleResourceHasSelfLink($url);

        $I->amSure('Service point address is persisted as order shipping address')
            ->whenI()
            ->assertSalesOrderAddressIsCorrectForItem(
                $servicePointTransfer->getAddressOrFail(),
                $customerTransfer,
                $this->fixtures->getProductConcreteTransfers()[0]->getSkuOrFail(),
            );
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\Checkout\CheckoutApiTester $I
     *
     * @return void
     */
    public function requestCheckoutWithSplitShipmentAndPickableShipmentMethodWithoutCustomerDataInRequestBody(CheckoutApiTester $I): void
    {
        $customerTransfer = $this->fixtures->getCustomerTransfer();
        $servicePointTransfer = $this->fixtures->getServicePointTransfer();
        $quoteTransfer = $I->havePersistentQuoteWithItemsAndItemLevelShipment(
            $customerTransfer,
            [
                $I->getQuoteItemOverrideData($this->fixtures->getProductConcreteTransfers()[0], $this->fixtures->getPickableShipmentMethodTransfer(), 1),
                $I->getQuoteItemOverrideData($this->fixtures->getProductConcreteTransfers()[1], $this->fixtures->getRegularShipmentMethodTransfer(), 1),
            ],
        );
        $quoteTransfer = $I->getCartFacade()->reloadItems($quoteTransfer);

        $I->authorizeCustomerToGlue($customerTransfer);

        $url = $I->buildCheckoutUrl();
        $requestPayload = [
            'data' => [
                'type' => CheckoutRestApiConfig::RESOURCE_CHECKOUT,
                'attributes' => [
                    'idCart' => $quoteTransfer->getUuidOrFail(),
                    'billingAddress' => $I->getAddressRequestPayload($quoteTransfer->getBillingAddressOrFail()),
                    'payments' => $I->getPaymentRequestPayload(),
                    'servicePoints' => $I->getServicePointsRequestPayload(
                        $servicePointTransfer->getUuidOrFail(),
                        [$quoteTransfer->getItems()->offsetGet(0)->getGroupKeyOrFail()],
                    ),
                    'shipments' => [
                        $I->getSplitShipmentRequestPayload($quoteTransfer->getItems()->offsetGet(0)),
                        $I->getSplitShipmentRequestPayload($quoteTransfer->getItems()->offsetGet(1)),
                    ],
                ],
            ],
        ];

        // Act
        $I->sendPOST($url, $requestPayload);

        // Assert
        $I->seeResponseCodeIs(HttpCode::CREATED);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesOpenApiSchema();

        $I->assertCheckoutResponseResourceHasCorrectData();

        $I->amSure('The returned resource has correct self link')
            ->whenI()
            ->seeSingleResourceHasSelfLink($url);

        $I->amSure('Service point address is persisted as order shipping address')
            ->whenI()
            ->assertSalesOrderAddressIsCorrectForItem(
                $servicePointTransfer->getAddressOrFail(),
                $customerTransfer,
                $this->fixtures->getProductConcreteTransfers()[0]->getSkuOrFail(),
            );
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\Checkout\CheckoutApiTester $I
     *
     * @return void
     */
    public function requestCheckoutWithSplitShipmentWIthOneItemAndPickableShipmentMethodWithoutCustomerDataInRequestBody(CheckoutApiTester $I): void
    {
        $customerTransfer = $this->fixtures->getCustomerTransfer();
        $servicePointTransfer = $this->fixtures->getServicePointTransfer();
        $quoteTransfer = $I->havePersistentQuoteWithItemsAndItemLevelShipment(
            $customerTransfer,
            [
                $I->getQuoteItemOverrideData($this->fixtures->getProductConcreteTransfers()[0], $this->fixtures->getPickableShipmentMethodTransfer(), 1),
            ],
        );
        $quoteTransfer = $I->getCartFacade()->reloadItems($quoteTransfer);

        $I->authorizeCustomerToGlue($customerTransfer);

        $url = $I->buildCheckoutUrl();
        $requestPayload = [
            'data' => [
                'type' => CheckoutRestApiConfig::RESOURCE_CHECKOUT,
                'attributes' => [
                    'idCart' => $quoteTransfer->getUuidOrFail(),
                    'billingAddress' => $I->getAddressRequestPayload($quoteTransfer->getBillingAddressOrFail()),
                    'payments' => $I->getPaymentRequestPayload(),
                    'servicePoints' => $I->getServicePointsRequestPayload(
                        $servicePointTransfer->getUuidOrFail(),
                        [$quoteTransfer->getItems()->offsetGet(0)->getGroupKeyOrFail()],
                    ),
                    'shipments' => [
                        $I->getSplitShipmentRequestPayload($quoteTransfer->getItems()->offsetGet(0)),
                    ],
                ],
            ],
        ];

        // Act
        $I->sendPOST($url, $requestPayload);

        // Assert
        $I->seeResponseCodeIs(HttpCode::CREATED);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesOpenApiSchema();

        $I->assertCheckoutResponseResourceHasCorrectData();

        $I->amSure('The returned resource has correct self link')
            ->whenI()
            ->seeSingleResourceHasSelfLink($url);

        $I->amSure('Service point address is persisted as order shipping address')
            ->whenI()
            ->assertSalesOrderAddressIsCorrectForItem(
                $servicePointTransfer->getAddressOrFail(),
                $customerTransfer,
                $this->fixtures->getProductConcreteTransfers()[0]->getSkuOrFail(),
            );
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\Checkout\CheckoutApiTester $I
     *
     * @return void
     */
    public function requestCheckoutForGuestUserWithPickableShipmentMethodWithoutCustomerDataInRequestBody(CheckoutApiTester $I): void
    {
        $guestCustomerReference = $I->createGuestCustomerReference();
        $guestCustomerTransfer = $I->createCustomerTransfer([
            CustomerTransfer::CUSTOMER_REFERENCE => $guestCustomerReference,
        ]);

        $I->haveHttpHeader(
            static::HEADER_ANONYMOUS_CUSTOMER_UNIQUE_ID,
            $guestCustomerReference,
        );

        $servicePointTransfer = $this->fixtures->getServicePointTransfer();
        $quoteTransfer = $I->havePersistentQuoteWithItemsAndItemLevelShipment(
            $guestCustomerTransfer,
            [
                $I->getQuoteItemOverrideData($this->fixtures->getProductConcreteTransfers()[0], $this->fixtures->getPickableShipmentMethodTransfer(), 1),
                $I->getQuoteItemOverrideData($this->fixtures->getProductConcreteTransfers()[1], $this->fixtures->getRegularShipmentMethodTransfer(), 1),
            ],
        );
        $quoteTransfer = $I->getCartFacade()->reloadItems($quoteTransfer);

        $url = $I->buildCheckoutUrl();
        $requestPayload = [
            'data' => [
                'type' => CheckoutRestApiConfig::RESOURCE_CHECKOUT,
                'attributes' => [
                    'idCart' => $quoteTransfer->getUuidOrFail(),
                    'billingAddress' => $I->getAddressRequestPayload($quoteTransfer->getBillingAddressOrFail()),
                    'payments' => $I->getPaymentRequestPayload(),
                    'servicePoints' => $I->getServicePointsRequestPayload($servicePointTransfer->getUuidOrFail(), $this->extractItemGroupKeys($quoteTransfer)),
                    'shipment' => $I->getShipmentRequestPayload($this->fixtures->getPickableShipmentMethodTransfer()->getIdShipmentMethodOrFail()),
                ],
            ],
        ];

        // Act
        $I->sendPOST($url, $requestPayload);

        // Assert
        $I->seeResponseCodeIs(HttpCode::UNPROCESSABLE_ENTITY);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesOpenApiSchema();

        $errors = $I->getDataFromResponseByJsonPath('$.errors[0]');
        $I->assertEquals($errors[RestCheckoutErrorTransfer::CODE], static::RESPONSE_CODE_CUSTOMER_DATA_MISSING);
        $I->assertEquals($errors[RestCheckoutErrorTransfer::STATUS], HttpCode::UNPROCESSABLE_ENTITY);
        $I->assertEquals($errors[RestCheckoutErrorTransfer::DETAIL], static::RESPONSE_DETAILS_CUSTOMER_DATA_MISSING);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return list<string>
     */
    protected function extractItemGroupKeys(QuoteTransfer $quoteTransfer): array
    {
        $itemGroupKeys = [];
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $itemGroupKeys[] = $itemTransfer->getGroupKeyOrFail();
        }

        return $itemGroupKeys;
    }
}
