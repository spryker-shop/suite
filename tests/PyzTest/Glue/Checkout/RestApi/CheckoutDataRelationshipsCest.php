<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Glue\Checkout\RestApi;

use Codeception\Util\HttpCode;
use PyzTest\Glue\Checkout\CheckoutApiTester;
use PyzTest\Glue\Checkout\RestApi\Fixtures\CheckoutDataServicePointRelationshipsFixtures;
use PyzTest\Glue\Checkout\RestApi\Fixtures\CheckoutDataShipmentRelationshipsFixtures;
use Spryker\Glue\CheckoutRestApi\CheckoutRestApiConfig;
use Spryker\Glue\ServicePointsRestApi\ServicePointsRestApiConfig;
use Spryker\Glue\ShipmentsRestApi\ShipmentsRestApiConfig;
use Spryker\Glue\ShipmentTypesRestApi\ShipmentTypesRestApiConfig;

/**
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Glue
 * @group Checkout
 * @group RestApi
 * @group CheckoutDataRelationshipsCest
 * Add your own group annotations below this line
 */
class CheckoutDataRelationshipsCest
{
    /**
     * @var \PyzTest\Glue\Checkout\RestApi\Fixtures\CheckoutDataShipmentRelationshipsFixtures
     */
    protected CheckoutDataShipmentRelationshipsFixtures $checkoutDataShipmentRelationshipsFixtures;

    /**
     * @var \PyzTest\Glue\Checkout\RestApi\Fixtures\CheckoutDataServicePointRelationshipsFixtures
     */
    protected CheckoutDataServicePointRelationshipsFixtures $checkoutDataServicePointRelationshipsFixtures;

    /**
     * @param \PyzTest\Glue\Checkout\CheckoutApiTester $I
     *
     * @return void
     */
    public function loadShipmentFixtures(CheckoutApiTester $I): void
    {
        /** @var \PyzTest\Glue\Checkout\RestApi\Fixtures\CheckoutDataShipmentRelationshipsFixtures $fixtures */
        $fixtures = $I->loadFixtures(CheckoutDataShipmentRelationshipsFixtures::class);
        $this->checkoutDataShipmentRelationshipsFixtures = $fixtures;
    }

    /**
     * @param \PyzTest\Glue\Checkout\CheckoutApiTester $I
     *
     * @return void
     */
    public function loadServicePointFixtures(CheckoutApiTester $I): void
    {
        /** @var \PyzTest\Glue\Checkout\RestApi\Fixtures\CheckoutDataServicePointRelationshipsFixtures $fixtures */
        $fixtures = $I->loadFixtures(CheckoutDataServicePointRelationshipsFixtures::class);
        $this->checkoutDataServicePointRelationshipsFixtures = $fixtures;
    }

    /**
     * @depends loadShipmentFixtures
     *
     * @param \PyzTest\Glue\Checkout\CheckoutApiTester $I
     *
     * @return void
     */
    public function requestCheckoutDataIncludesShipmentsRelationship(CheckoutApiTester $I): void
    {
        // Arrange
        $I->authorizeCustomerToGlue($this->checkoutDataShipmentRelationshipsFixtures->getCustomerTransfer());

        $url = $I->buildCheckoutDataUrl([
            ShipmentsRestApiConfig::RESOURCE_SHIPMENTS,
        ]);
        $requestPayload = [
            'data' => [
                'type' => CheckoutRestApiConfig::RESOURCE_CHECKOUT_DATA,
                'attributes' => [
                    'idCart' => $this->checkoutDataShipmentRelationshipsFixtures->getQuoteTransfer()->getUuid(),
                ],
            ],
        ];

        $shipmentMethodTransfer = $this->checkoutDataShipmentRelationshipsFixtures->getShipmentMethodTransfer();

        // Act
        $I->sendPOST($url, $requestPayload);

        // Assert
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesOpenApiSchema();

        $I->amSure('The response contains included shipments')
            ->whenI()
            ->seeIncludesContainResourceOfType(ShipmentsRestApiConfig::RESOURCE_SHIPMENTS);

        $jsonPath = sprintf('$..included[?(@.type == \'%s\')]', 'shipments');
        $shipments = $I->getDataFromResponseByJsonPath($jsonPath)[0];

        $I->amSure('The included shipments resource contains correct attributes')
            ->whenI()
            ->assertSame(
                [$this->checkoutDataShipmentRelationshipsFixtures->getQuoteTransfer()->getItems()->offsetGet(0)->getGroupKey()],
                $shipments['attributes']['items'],
            );
        $I->amSure('The included shipments resource contains correct attributes')
            ->whenI()
            ->assertArraySubset(
                [
                    'id' => $shipmentMethodTransfer->getIdShipmentMethod(),
                    'name' => $shipmentMethodTransfer->getName(),
                    'carrierName' => $shipmentMethodTransfer->getCarrierName(),
                ],
                $shipments['attributes']['selectedShipmentMethod'],
            );
    }

    /**
     * @depends loadShipmentFixtures
     *
     * @param \PyzTest\Glue\Checkout\CheckoutApiTester $I
     *
     * @return void
     */
    public function requestCheckoutDataIncludesShipmentMethodsRelationship(CheckoutApiTester $I): void
    {
        // Arrange
        $I->authorizeCustomerToGlue($this->checkoutDataShipmentRelationshipsFixtures->getCustomerTransfer());

        $url = $I->buildCheckoutDataUrl([
            ShipmentsRestApiConfig::RESOURCE_SHIPMENTS,
            ShipmentsRestApiConfig::RESOURCE_SHIPMENT_METHODS,
        ]);
        $requestPayload = [
            'data' => [
                'type' => CheckoutRestApiConfig::RESOURCE_CHECKOUT_DATA,
                'attributes' => [
                    'idCart' => $this->checkoutDataShipmentRelationshipsFixtures->getQuoteTransfer()->getUuid(),
                ],
            ],
        ];

        $shipmentMethodTransfer = $this->checkoutDataShipmentRelationshipsFixtures->getShipmentMethodTransfer();

        // Act
        $I->sendPOST($url, $requestPayload);

        // Assert
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesOpenApiSchema();

        $I->amSure('The response contains included shipment methods')
            ->whenI()
            ->seeIncludesContainResourceOfType(ShipmentsRestApiConfig::RESOURCE_SHIPMENT_METHODS);
        $I->amSure('The response contains includes expected shipment-methods resource')
            ->whenI()
            ->seeIncludesContainsResourceByTypeAndId(
                ShipmentsRestApiConfig::RESOURCE_SHIPMENT_METHODS,
                $shipmentMethodTransfer->getIdShipmentMethod(),
            );
        $I->amSure('The included shipment-methods resource contains correct attributes')
            ->whenI()
            ->seeIncludedResourceByTypeAndIdContainsAttributes(
                ShipmentsRestApiConfig::RESOURCE_SHIPMENT_METHODS,
                $shipmentMethodTransfer->getIdShipmentMethodOrFail(),
                [
                    'name' => $shipmentMethodTransfer->getNameOrFail(),
                    'carrierName' => $shipmentMethodTransfer->getCarrierNameOrFail(),
                ],
            );
    }

    /**
     * @depends loadShipmentFixtures
     *
     * @param \PyzTest\Glue\Checkout\CheckoutApiTester $I
     *
     * @return void
     */
    public function requestCheckoutDataIncludesShipmentTypesRelationship(CheckoutApiTester $I): void
    {
        // Arrange
        $I->authorizeCustomerToGlue($this->checkoutDataShipmentRelationshipsFixtures->getCustomerTransfer());

        $url = $I->buildCheckoutDataUrl([
            ShipmentsRestApiConfig::RESOURCE_SHIPMENTS,
            ShipmentsRestApiConfig::RESOURCE_SHIPMENT_METHODS,
            ShipmentTypesRestApiConfig::RESOURCE_SHIPMENT_TYPES,
        ]);
        $requestPayload = [
            'data' => [
                'type' => CheckoutRestApiConfig::RESOURCE_CHECKOUT_DATA,
                'attributes' => [
                    'idCart' => $this->checkoutDataShipmentRelationshipsFixtures->getQuoteTransfer()->getUuid(),
                ],
            ],
        ];

        $shipmentTypeTransfer = $this->checkoutDataShipmentRelationshipsFixtures->getShipmentTypeTransfer();

        // Act
        $I->sendPOST($url, $requestPayload);

        // Assert
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesOpenApiSchema();

        $I->amSure('The response contains included shipment types')
            ->whenI()
            ->seeIncludesContainResourceOfType(ShipmentTypesRestApiConfig::RESOURCE_SHIPMENT_TYPES);
        $I->amSure('The response contains includes expected shipment-types resource')
            ->whenI()
            ->seeIncludesContainsResourceByTypeAndId(
                ShipmentTypesRestApiConfig::RESOURCE_SHIPMENT_TYPES,
                $shipmentTypeTransfer->getUuidOrFail(),
            );
        $I->amSure('The included shipment-types resource contains correct attributes')
            ->whenI()
            ->seeIncludedResourceByTypeAndIdContainsAttributes(
                ShipmentTypesRestApiConfig::RESOURCE_SHIPMENT_TYPES,
                $shipmentTypeTransfer->getUuidOrFail(),
                [
                    'key' => $shipmentTypeTransfer->getKeyOrFail(),
                    'name' => $shipmentTypeTransfer->getNameOrFail(),
                ],
            );
    }

    /**
     * @depends loadShipmentFixtures
     *
     * @param \PyzTest\Glue\Checkout\CheckoutApiTester $I
     *
     * @return void
     */
    public function requestCheckoutDataReturnsSelectedShipmentTypes(CheckoutApiTester $I): void
    {
        // Arrange
        $I->authorizeCustomerToGlue($this->checkoutDataShipmentRelationshipsFixtures->getCustomerTransfer());

        $requestPayload = [
            'data' => [
                'type' => CheckoutRestApiConfig::RESOURCE_CHECKOUT_DATA,
                'attributes' => [
                    'idCart' => $this->checkoutDataShipmentRelationshipsFixtures->getQuoteTransfer()->getUuid(),
                    'shipment' => [
                        'idShipmentMethod' => $this->checkoutDataShipmentRelationshipsFixtures->getShipmentMethodTransfer()->getIdShipmentMethod(),
                    ],
                ],
            ],
        ];

        $shipmentTypeTransfer = $this->checkoutDataShipmentRelationshipsFixtures->getShipmentTypeTransfer();

        // Act
        $I->sendPOST($I->buildCheckoutDataUrl(), $requestPayload);

        // Assert
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesOpenApiSchema();

        $I->amSure('The checkout data resource contains correct selected shipment types')
            ->whenI()
            ->assertSame(
                [
                    'id' => $shipmentTypeTransfer->getUuidOrFail(),
                    'name' => $shipmentTypeTransfer->getNameOrFail(),
                    'key' => $shipmentTypeTransfer->getKeyOrFail(),
                ],
                $I->getDataFromResponseByJsonPath('$.data.attributes')['selectedShipmentTypes'][0],
            );
    }

    /**
     * @depends loadServicePointFixtures
     *
     * @param \PyzTest\Glue\Checkout\CheckoutApiTester $I
     *
     * @return void
     */
    public function requestCheckoutDataIncludesServicePointsRelationship(CheckoutApiTester $I): void
    {
        // Arrange
        $I->authorizeCustomerToGlue($this->checkoutDataServicePointRelationshipsFixtures->getCustomerTransfer());

        $quoteTransfer = $this->checkoutDataServicePointRelationshipsFixtures->getQuoteTransfer();
        $servicePointUuid = $quoteTransfer->getItems()[0]->getServicePoint()->getUuid();
        $itemGroupKey = $quoteTransfer->getItems()[0]->getGroupKey();

        $url = $I->buildCheckoutDataUrl([
            ServicePointsRestApiConfig::RESOURCE_SERVICE_POINTS,
        ]);
        $requestPayload = [
            'data' => [
                'type' => CheckoutRestApiConfig::RESOURCE_CHECKOUT_DATA,
                'attributes' => [
                    'idCart' => $quoteTransfer->getUuid(),
                    'servicePoints' => $I->getServicePointsRequestPayload($servicePointUuid, [$itemGroupKey]),
                ],
            ],
        ];

        // Act
        $I->sendPOST($url, $requestPayload);

        // Assert
        $includedServicePointsData = $I->getDataFromResponseByJsonPath('$.data.relationships.service-points.data');
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesOpenApiSchema();

        $I->amSure('The response contains included service points')
            ->whenI()
            ->seeIncludesContainResourceOfType(ServicePointsRestApiConfig::RESOURCE_SERVICE_POINTS);
        $I->amSure('The returned resource contains included service point with correct service point uuid')
            ->whenI()
            ->assertSame($servicePointUuid, $includedServicePointsData[0]['id']);
    }
}
