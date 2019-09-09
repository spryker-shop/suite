<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Glue\ShipmentsRestApi\RestApi;

use Codeception\Util\HttpCode;
use PyzTest\Glue\ShipmentsRestApi\ShipmentsRestApiTester;

/**
 * Auto-generated group annotations
 * @group PyzTest
 * @group Glue
 * @group ShipmentsRestApi
 * @group RestApi
 * @group ShipmentsRestApiCest
 * Add your own group annotations below this line
 * @group EndToEnd
 */
class ShipmentsRestApiCest
{
    /**
     * @var \PyzTest\Glue\ShipmentsRestApi\RestApi\ShipmentsRestApiFixtures
     */
    protected $fixtures;

    /**
     * @param \PyzTest\Glue\ShipmentsRestApi\ShipmentsRestApiTester $I
     *
     * @return void
     */
    public function loadFixtures(ShipmentsRestApiTester $I): void
    {
        $this->fixtures = $I->loadFixtures(ShipmentsRestApiFixtures::class);
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\ShipmentsRestApi\ShipmentsRestApiTester $I
     *
     * @return void
     */
    public function requestCheckoutDataWithIncludedShipmentMethods(ShipmentsRestApiTester $I): void
    {
        $idCart = $I->createCartWithItems(
            $this->fixtures->getCustomerTransfer(),
            $this->fixtures->getProductConcreteTransfer()
        );

        $I->sendPOST('checkout-data?include=shipment-methods', [
            'data' => [
                'type' => 'checkout-data',
                'attributes' => [
                    'idCart' => $idCart,
                ],
            ],
        ]);

        $this->assertCheckoutDataRequestWithIncludedShipmentMethods($I);
    }

    /**
     * @param \PyzTest\Glue\ShipmentsRestApi\ShipmentsRestApiTester $I
     *
     * @return void
     */
    protected function assertCheckoutDataRequestWithIncludedShipmentMethods(
        ShipmentsRestApiTester $I
    ): void {
        $idShipmentMethod = $this->fixtures->getShipmentMethodTransfer()->getIdShipmentMethod();

        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesOpenApiSchema();

        $I->amSure('Returned resource has shipment method in `relationships` section.')
            ->whenI()
            ->seeSingleResourceHasRelationshipByTypeAndId('shipment-methods', $idShipmentMethod);

        $I->amSure('Returned resource has shipment method in `included` section.')
            ->whenI()
            ->seeIncludesContainsResourceByTypeAndId('shipment-methods', $idShipmentMethod);
    }
}
