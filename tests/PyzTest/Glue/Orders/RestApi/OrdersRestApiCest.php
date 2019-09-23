<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Glue\Orders\RestApi;

use Codeception\Util\HttpCode;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use PyzTest\Glue\Orders\OrdersRestApiTester;
use Spryker\Glue\OrdersRestApi\OrdersRestApiConfig;

/**
 * Auto-generated group annotations
 * @group PyzTest
 * @group Glue
 * @group Orders
 * @group RestApi
 * @group OrdersRestApiCest
 * Add your own group annotations below this line
 * @group EndToEnd
 */
class OrdersRestApiCest
{
    protected const KEY_ACCESS_TOKEN = 'accessToken';
    /**
     * @var \PyzTest\Glue\Orders\RestApi\OrdersRestApiFixtures
     */
    protected $fixtures;

    /**
     * @param \PyzTest\Glue\Orders\OrdersRestApiTester $I
     *
     * @return void
     */
    public function loadFixtures(OrdersRestApiTester $I): void
    {
        $this->fixtures = $I->loadFixtures(OrdersRestApiFixtures::class);
    }

    /**
     * @param OrdersRestApiTester $I
     *
     * @return void
     */
    public function gettingOrderDataAsAuthorizedCustomerShouldBeSuccessful(OrdersRestApiTester $I): void
    {
        //Arrange
        $orderTransfer = $this->getOrderTransfer($I);
        $this->authorizeCustomer($I, $this->fixtures->getCustomerTransfer());

        //Act
        $I->sendGET($I->formatUrl(
            '{resource}/{reference}', [
                'resource' => OrdersRestApiConfig::RESOURCE_ORDERS,
                'reference' => $orderTransfer->getOrderReference(),
            ]
        ));

        //Assert
        $this->assertOrderResponse($I, HttpCode::OK);

        $shipmentsData = current($I->grabDataFromResponseByJsonPath('$.data.attributes.shipments'));
        $shipmentData = current($shipmentsData);

        $I->assertIsArray($shipmentsData);
        $I->assertIsArray($shipmentData);
        $I->assertEquals($shipmentData['shipmentMethodName'], $this->fixtures->getShipmentMethodTransfer()->getName());
    }

    /**
     * @param OrdersRestApiTester $I
     *
     * @return void
     */
    public function gettingOrderDataAsNonAuthorizedCustomerShouldFail(OrdersRestApiTester $I): void
    {
        //Arrange
        $orderTransfer = $this->getOrderTransfer($I);

        //Act
        $I->sendGET($I->formatUrl(
            '{resource}/{reference}', [
                'resource' => OrdersRestApiConfig::RESOURCE_ORDERS,
                'reference' => $orderTransfer->getOrderReference(),
            ]
        ));

        //Assert
        $I->seeResponseCodeIs(HttpCode::FORBIDDEN);
        $I->seeResponseIsJson();
    }

    /**
     * @param OrdersRestApiTester $I
     *
     * @return OrderTransfer
     */
    protected function getOrderTransfer(OrdersRestApiTester $I): OrderTransfer
    {
        $saveOrderTransfer = $this->fixtures->getSaveOrderTransfer();

        return $I->getLocator()->sales()->facade()->getOrderByIdSalesOrder($saveOrderTransfer->getIdSalesOrder());
    }

    /**
     * @param OrdersRestApiTester $I
     * @param CustomerTransfer $customerTransfer
     *
     * @return void
     */
    protected function authorizeCustomer(OrdersRestApiTester $I, CustomerTransfer $customerTransfer): void
    {
        $accessToken = $I->haveAuthorizationToGlue($customerTransfer)[static::KEY_ACCESS_TOKEN];

        $I->amAuthorizedGlueUser($accessToken);
    }

    /**
     * @param \PyzTest\Glue\Orders\OrdersRestApiTester $I
     * @param int $responseCode
     *
     * @return void
     */
    protected function assertOrderResponse(OrdersRestApiTester $I, int $responseCode): void
    {
        $I->seeResponseCodeIs($responseCode);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesOpenApiSchema();
    }
}
