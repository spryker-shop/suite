<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Glue\Orders\RestApi;

use Codeception\Util\HttpCode;
use PyzTest\Glue\Carts\CartsApiTester;
use PyzTest\Glue\Orders\OrdersApiTester;
use PyzTest\Glue\Orders\RestApi\OrdersRestApiFixtures;
use Spryker\Glue\CartsRestApi\CartsRestApiConfig;
use Spryker\Glue\GlueApplication\Rest\RequestConstantsInterface;
use Spryker\Shared\Calculation\CalculationPriceMode;

/**
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Glue
 * @group Carts
 * @group RestApi
 * @group CartsRestApiCest
 * Add your own group annotations below this line
 * @group EndToEnd
 */
class OrdersRestApiCest
{
    /**
     * @var  \PyzTest\Glue\Orders\RestApi\OrdersRestApiFixtures
     */
    protected $fixtures;

    /**
     * @param \PyzTest\Glue\Carts\CartsApiTester $I
     *
     * @return void
     */
    public function loadFixtures(CartsApiTester $I): void
    {
        $this->fixtures = $I->loadFixtures(OrdersRestApiFixtures::class);
    }


    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\Products\ProductsApiTester $I
     *
     * @return void
     */
    public function requestExistingProductConcrete(OrdersApiTester $I): void
    {
        //arrange
        $this->authorizeCustomer($I);

        //act
        $I->sendGET(
            $I->formatUrl(
                'orders/{orderId}',
                [
                    'orderId' => $this->fixtures->getOrderTransfer()->getIdSalesOrder(),
                ]
            )
        );

        //assert
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();

    }

    /**
     * @param \PyzTest\Glue\Carts\CartsApiTester $I
     *
     * @return void
     */
    protected function authorizeCustomer(OrdersApiTester $I): void
    {
        $token = $I->haveAuthorizationToGlue($this->fixtures->getCustomerTransfer())->getAccessToken();

        $I->amBearerAuthenticated($token);
    }

}
