<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Glue\ShipmentTypes\RestApi;

use Codeception\Util\HttpCode;
use PyzTest\Glue\ShipmentTypes\ShipmentTypesApiTester;
use Spryker\Glue\ShipmentTypesRestApi\ShipmentTypesRestApiConfig;

/**
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Glue
 * @group ShipmentTypes
 * @group RestApi
 * @group ShipmentTypesRestApiCest
 * Add your own group annotations below this line
 * @group EndToEnd
 */
class ShipmentTypesRestApiCest
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
    public function requestGetShipmentTypes(ShipmentTypesApiTester $I): void
    {
        // Act
        $I->sendGET(
            $I->formatUrl(ShipmentTypesRestApiConfig::RESOURCE_SHIPMENT_TYPES),
        );

        // Assert
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesOpenApiSchema();

        $I->amSure('The returned resource has correct self-link')
            ->whenI()
            ->seeResponseLinksContainsSelfLink(
                $I->formatFullUrl(ShipmentTypesRestApiConfig::RESOURCE_SHIPMENT_TYPES),
            );

        $I->amSure('The returned resource has not empty collection')
            ->whenI()
            ->seeResponseDataContainsNonEmptyCollection();

        $I->amSure('The returned resource has correct resource collection type')
            ->whenI()
            ->seeResponseDataContainsResourceCollectionOfType(ShipmentTypesRestApiConfig::RESOURCE_SHIPMENT_TYPES);
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\ShipmentTypes\ShipmentTypesApiTester $I
     *
     * @return void
     */
    public function requestGetShipmentTypesReturnsShipmentTypesSortedByDESC(ShipmentTypesApiTester $I): void
    {
        // Act
        $I->sendGET(
            $I->formatUrl(
                '{shipmentTypeResource}?sort={direction}',
                [
                    'shipmentTypeResource' => ShipmentTypesRestApiConfig::RESOURCE_SHIPMENT_TYPES,
                    'direction' => '-key',
                ],
            ),
        );

        // Assert
        $I->amSure('The returned resource filtered by DESC key')
            ->whenI()
            ->assertSame('DESC', $I->getShipmentTypeKeysSorting($I->getDataFromResponseByJsonPath('$.data[*].attributes.key')));
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\ShipmentTypes\ShipmentTypesApiTester $I
     *
     * @return void
     */
    public function requestGetShipmentTypesReturnsShipmentTypesSortedByASC(ShipmentTypesApiTester $I): void
    {
        // Act
        $I->sendGET(
            $I->formatUrl(
                '{shipmentTypeResource}?sort={direction}',
                [
                    'shipmentTypeResource' => ShipmentTypesRestApiConfig::RESOURCE_SHIPMENT_TYPES,
                    'direction' => 'key',
                ],
            ),
        );

        // Assert
        $I->amSure('The returned resource filtered by ASC key')
            ->whenI()
            ->assertSame('ASC', $I->getShipmentTypeKeysSorting($I->getDataFromResponseByJsonPath('$.data[*].attributes.key')));
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\ShipmentTypes\ShipmentTypesApiTester $I
     *
     * @return void
     */
    public function requestGetShipmentTypeByUuid(ShipmentTypesApiTester $I): void
    {
        // Arrange
        $shipmentTypeTransfer = $this->fixtures->getShipmentTypes()[0];

        // Act
        $I->sendGET(
            $I->formatUrl(
                '{shipmentTypeResource}/{shipmentTypeUuid}',
                [
                    'shipmentTypeResource' => ShipmentTypesRestApiConfig::RESOURCE_SHIPMENT_TYPES,
                    'shipmentTypeUuid' => $shipmentTypeTransfer->getUuidOrFail(),
                ],
            ),
        );

        // Assert
        $data = $I->getDataFromResponseByJsonPath('$.data');

        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->assertSame(ShipmentTypesRestApiConfig::RESOURCE_SHIPMENT_TYPES, $data['type']);
        $I->assertSame($shipmentTypeTransfer->getUuidOrFail(), $data['id']);
        $I->assertEquals(
            [
                'key' => $shipmentTypeTransfer->getKeyOrFail(),
                'name' => $shipmentTypeTransfer->getNameOrFail(),
            ],
            $data['attributes'],
        );
    }

    /**
     * @param \PyzTest\Glue\ShipmentTypes\ShipmentTypesApiTester $I
     *
     * @return void
     */
    public function requestGetShipmentTypeByUndefinedUuid(ShipmentTypesApiTester $I): void
    {
        // Act
        $I->sendGET(
            $I->formatUrl(
                '{shipmentTypeResource}/{shipmentTypeUuid}',
                [
                    'shipmentTypeResource' => ShipmentTypesRestApiConfig::RESOURCE_SHIPMENT_TYPES,
                    'shipmentTypeUuid' => 'fake-uuid',
                ],
            ),
        );

        // Assert
        $I->seeResponseCodeIs(HttpCode::NOT_FOUND);
        $I->seeResponseIsJson();
        $I->assertSame(
            $I->getDataFromResponseByJsonPath('$.errors[0]'),
            [
                'code' => '5501',
                'status' => 404,
                'detail' => 'A delivery type entity was not found.',
            ],
        );
    }
}
