<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Glue\CheckoutDataRestApi\RestApi;

use Codeception\Util\HttpCode;
use PyzTest\Glue\CheckoutDataRestApi\CheckoutDataRestApiTester;

/**
 * Auto-generated group annotations
 * @group PyzTest
 * @group Glue
 * @group CheckoutDataRestApi
 * @group RestApi
 * @group CheckoutDataRestApiCest
 * Add your own group annotations below this line
 * @group EndToEnd
 */
class CheckoutDataRestApiCest
{
    protected const NOT_EXISTED_ID_CART = 'NOT_EXISTED_ID_CART';

    /**
     * @var \PyzTest\Glue\CheckoutDataRestApi\RestApi\CheckoutDataRestApiFixtures
     */
    protected $fixtures;

    /**
     * @param \PyzTest\Glue\CheckoutDataRestApi\CheckoutDataRestApiTester $I
     *
     * @return void
     */
    public function loadFixtures(CheckoutDataRestApiTester $I): void
    {
        $this->fixtures = $I->loadFixtures(CheckoutDataRestApiFixtures::class);
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\CheckoutDataRestApi\CheckoutDataRestApiTester $I
     *
     * @return void
     */
    public function requestCheckoutDataByWhenCustomerIsNotLoggedInShouldBeFailed(CheckoutDataRestApiTester $I): void
    {
        $I->sendPOST('checkout-data', [
            'data' => [
                'type' => 'checkout-data',
                'attributes' => [
                    'idCart' => static::NOT_EXISTED_ID_CART,
                ],
            ],
        ]);

        $this->assertCheckoutDataRequest($I, HttpCode::BAD_REQUEST);
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\CheckoutDataRestApi\CheckoutDataRestApiTester $I
     *
     * @return void
     */
    public function requestCheckoutDataByIdCartShouldBeSuccessful(CheckoutDataRestApiTester $I): void
    {
        $idCart = $I->createCartWithItems(
            $this->fixtures->getCustomerTransfer(),
            $this->fixtures->getProductConcreteTransfer()
        );

        $I->sendPOST('checkout-data', [
            'data' => [
                'type' => 'checkout-data',
                'attributes' => [
                    'idCart' => $idCart,
                ],
            ],
        ]);

        $this->assertCheckoutDataRequest($I, HttpCode::OK);
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\CheckoutDataRestApi\CheckoutDataRestApiTester $I
     *
     * @return void
     */
    public function requestCheckoutDataByIncorrectIdCartShouldBeFailed(CheckoutDataRestApiTester $I): void
    {
        $I->customerLogIn($this->fixtures->getCustomerTransfer());

        $I->sendPOST('checkout-data', [
            'data' => [
                'type' => 'checkout-data',
                'attributes' => [
                    'idCart' => static::NOT_EXISTED_ID_CART,
                ],
            ],
        ]);

        $this->assertCheckoutDataRequest($I, HttpCode::UNPROCESSABLE_ENTITY);
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\CheckoutDataRestApi\CheckoutDataRestApiTester $I
     *
     * @return void
     */
    public function requestCheckoutDataByIdCartWithSelectedShipmentMethodShouldGetShipmentMethodDetails(
        CheckoutDataRestApiTester $I
    ): void {
        $idCart = $I->createCartWithItems(
            $this->fixtures->getCustomerTransfer(),
            $this->fixtures->getProductConcreteTransfer()
        );

        $I->sendPOST('checkout-data', [
            'data' => [
                'type' => 'checkout-data',
                'attributes' => [
                    'idCart' => $idCart,
                    'shipment' => [
                        'idShipmentMethod' => $this->fixtures->getShipmentMethodTransfer()->getIdShipmentMethod(),
                    ],
                ],
            ],
        ]);

        $this->assertCheckoutDataRequest($I, HttpCode::OK);
        $selectedShipmentMethods = $I
            ->grabDataFromResponseByJsonPath('$.data.attributes.selectedShipmentMethods')[0];
        $selectedShipmentMethod = $selectedShipmentMethods[0];

        $I->assertNotEmpty($selectedShipmentMethods);
        $I->assertNotEmpty($selectedShipmentMethod);
        $I->assertSame($selectedShipmentMethod['name'], $this->fixtures->getShipmentMethodTransfer()->getName());
    }

    /**
     * @param \PyzTest\Glue\CheckoutDataRestApi\CheckoutDataRestApiTester $I
     * @param int $responseCode
     *
     * @return void
     */
    protected function assertCheckoutDataRequest(
        CheckoutDataRestApiTester $I,
        int $responseCode
    ): void {
        $I->seeResponseCodeIs($responseCode);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesOpenApiSchema();
    }
}
