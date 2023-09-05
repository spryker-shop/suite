<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Glue\ShipmentTypes\RestApi;

use Generated\Shared\Transfer\ServiceTypeTransfer;
use Generated\Shared\Transfer\ShipmentTypeTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use PyzTest\Glue\ShipmentTypes\ShipmentTypesApiTester;
use SprykerTest\Shared\Testify\Fixtures\FixturesBuilderInterface;
use SprykerTest\Shared\Testify\Fixtures\FixturesContainerInterface;

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
class ShipmentTypesRestApiFixtures implements FixturesBuilderInterface, FixturesContainerInterface
{
    /**
     * @var list<\Generated\Shared\Transfer\ShipmentTypeTransfer>
     */
    protected array $shipmentTypes = [];

    /**
     * @var \Generated\Shared\Transfer\ServiceTypeTransfer
     */
    protected ServiceTypeTransfer $serviceTypeTransfer;

    /**
     * @param \PyzTest\Glue\ShipmentTypes\ShipmentTypesApiTester $I
     *
     * @return \SprykerTest\Shared\Testify\Fixtures\FixturesContainerInterface
     */
    public function buildFixtures(ShipmentTypesApiTester $I): FixturesContainerInterface
    {
        $this->shipmentTypes = [
            $this->createActiveShipmentType($I, ['DE']),
            $this->createActiveShipmentType($I, ['DE']),
        ];
        $this->serviceTypeTransfer = $this->createServiceType($I);
        $this->createShipmentTypeServiceTypeRelation($I);

        return $this;
    }

    /**
     * @return list<\Generated\Shared\Transfer\ShipmentTypeTransfer>
     */
    public function getShipmentTypes(): array
    {
        return $this->shipmentTypes;
    }

    /**
     * @return \Generated\Shared\Transfer\ServiceTypeTransfer
     */
    public function getServiceTypeTransfer(): ServiceTypeTransfer
    {
        return $this->serviceTypeTransfer;
    }

    /**
     * @param \PyzTest\Glue\ShipmentTypes\ShipmentTypesApiTester $I
     * @param list<string> $storeNames
     *
     * @return \Generated\Shared\Transfer\ShipmentTypeTransfer
     */
    protected function createActiveShipmentType(ShipmentTypesApiTester $I, array $storeNames = []): ShipmentTypeTransfer
    {
        $storeRelationTransfer = (new StoreRelationTransfer());
        foreach ($storeNames as $storeName) {
            $storeRelationTransfer->addStores($I->haveStore([StoreTransfer::NAME => $storeName]));
        }

        return $I->haveShipmentType([
            ShipmentTypeTransfer::IS_ACTIVE => true,
            ShipmentTypeTransfer::STORE_RELATION => $storeRelationTransfer,
        ]);
    }

    /**
     * @param \PyzTest\Glue\ShipmentTypes\ShipmentTypesApiTester $I
     *
     * @return \Generated\Shared\Transfer\ServiceTypeTransfer
     */
    protected function createServiceType(ShipmentTypesApiTester $I): ServiceTypeTransfer
    {
        return $I->haveServiceType();
    }

    /**
     * @param \PyzTest\Glue\ShipmentTypes\ShipmentTypesApiTester $I
     *
     * @return void
     */
    protected function createShipmentTypeServiceTypeRelation(ShipmentTypesApiTester $I): void
    {
        $I->haveShipmentTypeServiceTypeRelation(
            $this->shipmentTypes[0],
            $this->serviceTypeTransfer,
        );
    }
}
