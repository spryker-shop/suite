<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Glue\ShipmentTypes\RestApi;

use Codeception\Util\HttpCode;
use PyzTest\Glue\ShipmentTypes\ShipmentTypesApiTester;
use Spryker\Glue\ServicePointsRestApi\ServicePointsRestApiConfig;
use Spryker\Glue\ShipmentTypesRestApi\ShipmentTypesRestApiConfig;

/**
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Glue
 * @group ShipmentTypes
 * @group RestApi
 * @group ShipmentTypesServiceTypesRelationshipRestApiCest
 * Add your own group annotations below this line
 */
class ShipmentTypesServiceTypesRelationshipRestApiCest
{
    /**
     * @var \PyzTest\Glue\ShipmentTypes\RestApi\ShipmentTypesRestApiFixtures
     */
    protected ShipmentTypesRestApiFixtures $fixtures;

    /**
     * @param \PyzTest\Glue\ShipmentTypes\ShipmentTypesApiTester $I
     *
     * @return void
     */
    public function loadFixtures(ShipmentTypesApiTester $I): void
    {
        /** @var \PyzTest\Glue\ShipmentTypes\RestApi\ShipmentTypesRestApiFixtures $fixtures */
        $fixtures = $I->loadFixtures(ShipmentTypesRestApiFixtures::class);
        $this->fixtures = $fixtures;
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\ShipmentTypes\ShipmentTypesApiTester $I
     *
     * @return void
     */
    public function requestGetShipmentTypesWithServiceTypesRelationshipsIncludesCorrectServiceTypeResource(ShipmentTypesApiTester $I): void
    {
        // Arrange
        $serviceTypeTransfer = $this->fixtures->getServiceTypeTransfer();

        // Act
        $I->sendGET(
            $I->formatUrl(
                '{shipmentTypeResource}?include={include}',
                [
                    'shipmentTypeResource' => ShipmentTypesRestApiConfig::RESOURCE_SHIPMENT_TYPES,
                    'include' => ServicePointsRestApiConfig::RESOURCE_SERVICE_TYPES,
                ],
            ),
        );

        // Assert
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesOpenApiSchema();

        $I->amSure('The returned resource has correct resource collection type')
            ->whenI()
            ->seeResponseDataContainsResourceCollectionOfType(ShipmentTypesRestApiConfig::RESOURCE_SHIPMENT_TYPES);
        $I->amSure('The response contains included service types')
            ->whenI()
            ->seeIncludesContainResourceOfType(ServicePointsRestApiConfig::RESOURCE_SERVICE_TYPES);
        $I->amSure('The response contains includes expected service-types resource')
            ->whenI()
            ->seeIncludesContainsResourceByTypeAndId(
                ServicePointsRestApiConfig::RESOURCE_SERVICE_TYPES,
                $serviceTypeTransfer->getUuidOrFail(),
            );
        $I->amSure('The included service-types resource contains correct attributes')
            ->whenI()
            ->seeIncludedResourceByTypeAndIdContainsAttributes(
                ServicePointsRestApiConfig::RESOURCE_SERVICE_TYPES,
                $serviceTypeTransfer->getUuidOrFail(),
                [
                    'key' => $serviceTypeTransfer->getKeyOrFail(),
                    'name' => $serviceTypeTransfer->getNameOrFail(),
                ],
            );
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\ShipmentTypes\ShipmentTypesApiTester $I
     *
     * @return void
     */
    public function requestGetShipmentTypesByUuidWithServiceTypesRelationshipsIncludesCorrectServiceTypeResource(ShipmentTypesApiTester $I): void
    {
        // Arrange
        $shipmentTypeTransfer = $this->fixtures->getShipmentTypes()[0];
        $serviceTypeTransfer = $this->fixtures->getServiceTypeTransfer();

        // Act
        $I->sendGET(
            $I->formatUrl(
                '{shipmentTypeResource}/{shipmentTypeUuid}?include={include}',
                [
                    'shipmentTypeResource' => ShipmentTypesRestApiConfig::RESOURCE_SHIPMENT_TYPES,
                    'shipmentTypeUuid' => $shipmentTypeTransfer->getUuidOrFail(),
                    'include' => ServicePointsRestApiConfig::RESOURCE_SERVICE_TYPES,
                ],
            ),
        );

        // Assert
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesOpenApiSchema();

        $I->amSure('The returned resource has correct resource type')
            ->whenI()
            ->seeResponseDataContainsSingleResourceOfType(ShipmentTypesRestApiConfig::RESOURCE_SHIPMENT_TYPES);
        $I->amSure('The response contains includes expected service-types resource')
            ->whenI()
            ->seeIncludesContainsResourceByTypeAndId(
                ServicePointsRestApiConfig::RESOURCE_SERVICE_TYPES,
                $serviceTypeTransfer->getUuidOrFail(),
            );
        $I->amSure('The included service-types resource contains correct attributes')
            ->whenI()
            ->seeIncludedResourceByTypeAndIdContainsAttributes(
                ServicePointsRestApiConfig::RESOURCE_SERVICE_TYPES,
                $serviceTypeTransfer->getUuidOrFail(),
                [
                    'key' => $serviceTypeTransfer->getKeyOrFail(),
                    'name' => $serviceTypeTransfer->getNameOrFail(),
                ],
            );
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\ShipmentTypes\ShipmentTypesApiTester $I
     *
     * @return void
     */
    public function requestGetShipmentTypesByUuidWithServiceTypesRelationshipsDoesNotIncludeServiceTypeResourceWhenShipmentTypeHasNoRelationsWithServiceTypes(
        ShipmentTypesApiTester $I,
    ): void {
        // Arrange
        $shipmentTypeTransfer = $this->fixtures->getShipmentTypes()[1];

        // Act
        $I->sendGET(
            $I->formatUrl(
                '{shipmentTypeResource}/{shipmentTypeUuid}?include={include}',
                [
                    'shipmentTypeResource' => ShipmentTypesRestApiConfig::RESOURCE_SHIPMENT_TYPES,
                    'shipmentTypeUuid' => $shipmentTypeTransfer->getUuidOrFail(),
                    'include' => ServicePointsRestApiConfig::RESOURCE_SERVICE_TYPES,
                ],
            ),
        );

        // Assert
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesOpenApiSchema();

        $I->amSure('The returned resource has correct resource type')
            ->whenI()
            ->seeResponseDataContainsSingleResourceOfType(ShipmentTypesRestApiConfig::RESOURCE_SHIPMENT_TYPES);
        $I->amSure('The response does not include service-types resources')
            ->whenI()
            ->dontSeeIncludesContainResourceOfType(ServicePointsRestApiConfig::RESOURCE_SERVICE_TYPES);
    }
}
