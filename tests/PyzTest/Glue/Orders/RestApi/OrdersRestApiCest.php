<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Glue\Orders\RestApi;

use Codeception\Util\HttpCode;
use Generated\Shared\Transfer\CustomerTransfer;
use PyzTest\Glue\Orders\OrdersApiTester;
use Spryker\Glue\OrdersRestApi\OrdersRestApiConfig;

/**
 * Auto-generated group annotations
 *
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
    /**
     * @var \PyzTest\Glue\Orders\RestApi\OrdersRestApiFixtures
     */
    protected $fixtures;

    /**
     * @param \PyzTest\Glue\Orders\OrdersApiTester $I
     *
     * @return void
     */
    public function loadFixtures(OrdersApiTester $I): void
    {
        /** @var \PyzTest\Glue\Orders\RestApi\OrdersRestApiFixtures $fixtures */
        $fixtures = $I->loadFixtures(OrdersRestApiFixtures::class);
        $this->fixtures = $fixtures;
    }

    /**
     * @param \PyzTest\Glue\Orders\OrdersApiTester $I
     *
     * @return void
     */
    public function requestGetListOfOrdersWithSingleOrder(OrdersApiTester $I): void
    {
        //Arrange
        $this->authorizeCustomer($I, $this->fixtures->getCustomerWithOrders());

        //Act
        $I->sendGET(
            $I->formatUrl(OrdersRestApiConfig::RESOURCE_ORDERS),
        );

        //Assert
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesOpenApiSchema();

        $I->amSure('The returned resource has correct self-link')
            ->whenI()
            ->seeResponseLinksContainsSelfLink(
                $I->formatFullUrl(OrdersRestApiConfig::RESOURCE_ORDERS),
            );

        $I->amSure('The returned resource has not empty collection')
            ->whenI()
            ->seeResponseDataContainsNonEmptyCollection();

        $I->amSure('The returned resource has correct resource collection type')
            ->whenI()
            ->seeResponseDataContainsResourceCollectionOfType('orders');

        $I->amSure('The returned resource has correct size')
            ->whenI()
            ->seeResponseDataContainsResourceCollectionOfTypeWithSizeOf('orders', 1);
    }

    /**
     * @param \PyzTest\Glue\Orders\OrdersApiTester $I
     *
     * @return void
     */
    public function requestGetCustomerOrderAuthorizationError(OrdersApiTester $I): void
    {
        //Arrange
        $this->authorizeCustomer($I, $this->fixtures->getCustomerWithOrders());

        //Act
        $I->sendGET(
            $I->formatUrl(
                '{customer}/{customerReference}/{orders}',
                [
                    'customer' => OrdersRestApiConfig::RESOURCE_CUSTOMERS,
                    'customerReference' => 'wrongCustomerReference',
                    'orders' => OrdersRestApiConfig::RESOURCE_ORDERS,
                ],
            ),
        );

        //Assert
        $I->seeResponseCodeIs(HttpCode::FORBIDDEN);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesOpenApiSchema();
    }

    /**
     * @param \PyzTest\Glue\Orders\OrdersApiTester $I
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return void
     */
    protected function authorizeCustomer(OrdersApiTester $I, CustomerTransfer $customerTransfer): void
    {
        $token = $I->haveAuthorizationToGlue($customerTransfer)->getAccessToken();

        $I->amBearerAuthenticated($token);
    }
}
