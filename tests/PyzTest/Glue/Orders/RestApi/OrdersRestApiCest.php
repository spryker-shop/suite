<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Glue\Orders\RestApi;

use Codeception\Util\HttpCode;
use Generated\Shared\Transfer\CustomerTransfer;
use PyzTest\Glue\Orders\OrdersApiTester;

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
        $this->fixtures = $I->loadFixtures(OrdersRestApiFixtures::class);
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\Orders\OrdersApiTester $I
     *
     * @return void
     */
    public function requestGetEmptyListOfOrders(OrdersApiTester $I): void
    {
        //arrange
        $this->authorizeCustomer($I, $this->fixtures->getCustomerTransfer1());

        //act
        $I->sendGET(
            $I->formatUrl('orders')
        );

        //assert
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesOpenApiSchema();

        $I->amSure('The returned resource has correct self-link')
            ->whenI()
            ->seeResponseDataContainsEmptyCollection();

        $I->seeResponseLinksContainsSelfLink(
            $I->formatFullUrl('orders')
        );
    }

    /**
     * @param \PyzTest\Glue\Orders\OrdersApiTester $I
     *
     * @return void
     */
    public function requestGetListOfOrdersWithSingleOrder(OrdersApiTester $I): void
    {
        //arrange
        $this->authorizeCustomer($I, $this->fixtures->getCustomerTransfer2());

        //act
        $I->sendGET(
            $I->formatUrl('orders')
        );

        //assert
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesOpenApiSchema();

        $I->amSure('The returned resource has correct self-link')
            ->whenI()
            ->seeResponseLinksContainsSelfLink(
                $I->formatFullUrl('orders')
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
    public function requestGetListOfOrdersWithMultipleOrders(OrdersApiTester $I): void
    {
        //arrange
        $this->authorizeCustomer($I, $this->fixtures->getCustomerTransfer3());

        //act
        $I->sendGET(
            $I->formatUrl('orders')
        );
        //assert
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesOpenApiSchema();

        $I->amSure('The returned resource has correct self-link')
            ->whenI()
            ->seeResponseLinksContainsSelfLink(
                $I->formatFullUrl('orders')
            );

        $I->amSure('The returned resource has not empty collection')
            ->whenI()
            ->seeResponseDataContainsNonEmptyCollection();

        $I->amSure('The returned resource has correct resource collection type')
            ->whenI()
            ->seeResponseDataContainsResourceCollectionOfType('orders');

        $I->amSure('The returned resource has correct size')
            ->whenI()
            ->seeResponseDataContainsResourceCollectionOfTypeWithSizeOf('orders', 2);
    }

    /**
     * @param \PyzTest\Glue\Orders\OrdersApiTester $I
     *
     * @return void
     */
    public function requestGetOrderDetails(OrdersApiTester $I): void
    {
        //arrange
        $this->authorizeCustomer($I, $this->fixtures->getCustomerTransfer2());
        $orderTransfer = $this->fixtures->getOrderTransfer();

        //act
        $I->sendGET(
            $I->formatUrl(
                'orders/{customerOrderReference}',
                [
                    'customerOrderReference' => $orderTransfer->getOrderReference(),
                ]
            )
        );
        //assert
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesOpenApiSchema();

        $I->amSure('The returned resource has not empty collection')
            ->whenI()
            ->seeResponseDataContainsNonEmptyCollection();

        $I->amSure('The returned resource is of correct type')
            ->whenI()
            ->seeResponseDataContainsSingleResourceOfType('orders');

        $I->amSure('The returned resource has correct id')
            ->whenI()
            ->seeSingleResourceIdEqualTo($orderTransfer->getOrderReference());
    }

    /**
     * @param \PyzTest\Glue\Orders\OrdersApiTester $I
     *
     * @return void
     */
    public function requestGetListOfOrderWithoutAuthorizationToken(OrdersApiTester $I): void
    {
        //act
        $I->sendGET(
            $I->formatUrl('orders')
        );
        //assert
        $I->seeResponseCodeIs(HttpCode::FORBIDDEN);
    }

    /**
     * @param \PyzTest\Glue\Orders\OrdersApiTester $I
     *
     * @return void
     */
    public function requestGetOrderDetailsWithoutAuthorizationToken(OrdersApiTester $I): void
    {
        //arrange
        $orderTransfer = $this->fixtures->getOrderTransfer();

        //act
        $I->sendGET(
            $I->formatUrl(
                'orders/{customerOrderReference}',
                [
                    'customerOrderReference' => $orderTransfer->getOrderReference(),
                ]
            )
        );
        //assert
        $I->seeResponseCodeIs(HttpCode::FORBIDDEN);
    }

    /**
     * @param \PyzTest\Glue\Orders\OrdersApiTester $I
     *
     * @return void
     */
    public function requestGetOrderDetailsWithIncorrectOrderReference(OrdersApiTester $I): void
    {
        //arrange
        $this->authorizeCustomer($I, $this->fixtures->getCustomerTransfer2());

        //act
        $I->sendGET(
            $I->formatUrl(
                'orders/{customerOrderReference}',
                [
                    'customerOrderReference' => 'test',
                ]
            )
        );
        //assert
        $I->seeResponseCodeIs(HttpCode::NOT_FOUND);
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
