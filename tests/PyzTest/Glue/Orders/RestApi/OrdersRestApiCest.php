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
        $I->seeResponseMatchesOpenApiSchema();

//        $I->amSure('Returned resource is of type concrete-products')
//            ->whenI()
//            ->seeResponseDataContainsSingleResourceOfType('concrete-products');
//
//        $I->amSure('Returned resource has correct id')
//            ->whenI()
//            ->seeSingleResourceIdEqualTo($this->fixtures->getProductConcreteTransfer()->getSku());
    }

}
