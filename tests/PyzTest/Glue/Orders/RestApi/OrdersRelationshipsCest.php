<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace PyzTest\Glue\Orders\RestApi;

use Codeception\Util\HttpCode;
use DateTime;
use Generated\Shared\Transfer\CustomerTransfer;
use PyzTest\Glue\Orders\OrdersApiTester;
use PyzTest\Glue\Orders\RestApi\Fixtures\OrdersOrderAmendmentsRelationshipsFixtures;
use Spryker\Glue\OrderAmendmentsRestApi\OrderAmendmentsRestApiConfig;

/**
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Glue
 * @group Orders
 * @group RestApi
 * @group OrdersRelationshipsCest
 * Add your own group annotations below this line
 * @group EndToEnd
 */
class OrdersRelationshipsCest
{
    /**
     * @var \PyzTest\Glue\Orders\RestApi\Fixtures\OrdersOrderAmendmentsRelationshipsFixtures
     */
    protected OrdersOrderAmendmentsRelationshipsFixtures $ordersOrderAmendmentsRelationshipsFixtures;

    /**
     * @param \PyzTest\Glue\Orders\OrdersApiTester $I
     *
     * @return void
     */
    public function loadSalesOrderAmendmentFixtures(OrdersApiTester $I): void
    {
        /** @var \PyzTest\Glue\Orders\RestApi\Fixtures\OrdersOrderAmendmentsRelationshipsFixtures $fixtures */
        $fixtures = $I->loadFixtures(OrdersOrderAmendmentsRelationshipsFixtures::class);
        $this->ordersOrderAmendmentsRelationshipsFixtures = $fixtures;
    }

    /**
     * @depends loadSalesOrderAmendmentFixtures
     *
     * @param \PyzTest\Glue\Orders\OrdersApiTester $I
     *
     * @return void
     */
    public function requestOrdersIncludesOrderAmendmentsRelationship(OrdersApiTester $I): void
    {
        // Arrange
        $this->authorizeCustomer($I, $this->ordersOrderAmendmentsRelationshipsFixtures->getCustomerTransfer());

        $url = $I->buildOrdersUrl(
            $this->ordersOrderAmendmentsRelationshipsFixtures->getSaveOrderTransfer()->getOrderReference(),
            [OrderAmendmentsRestApiConfig::RESOURCE_ORDER_AMENDMENTS],
        );

        // Act
        $I->sendGet($url);

        // Assert
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesOpenApiSchema();

        $I->amSure('The response contains included order-amendments')
            ->whenI()
            ->seeIncludesContainResourceOfType(OrderAmendmentsRestApiConfig::RESOURCE_ORDER_AMENDMENTS);

        $jsonPath = sprintf('$..included[?(@.type == \'%s\')]', 'order-amendments');
        $orderAmendment = $I->getDataFromResponseByJsonPath($jsonPath)[0];

        $orderAmendmentTransfer = $this
            ->ordersOrderAmendmentsRelationshipsFixtures
            ->getSalesOrderAmendmentTransfer();

        $I->amSure('The response contains includes expected order-amendments resource')
            ->whenI()
            ->assertNotNull($orderAmendment);

        $I->amSure('The response contains includes expected order-amendments resource')
            ->whenI()
            ->seeIncludesContainsResourceByTypeAndId(
                OrderAmendmentsRestApiConfig::RESOURCE_ORDER_AMENDMENTS,
                $orderAmendmentTransfer->getUuid(),
            );

        $I->amSure('The included order-amendments resource contains correct attributes')
            ->whenI()
            ->assertArraySubset([
                'createdAt' => DateTime::createFromFormat('Y-m-d H:i:s.u', $orderAmendmentTransfer->getCreatedAt())->format('Y-m-d H:i:s'),
                'updatedAt' => DateTime::createFromFormat('Y-m-d H:i:s.u', $orderAmendmentTransfer->getUpdatedAt())->format('Y-m-d H:i:s'),
            ], [
                'createdAt' => DateTime::createFromFormat('Y-m-d H:i:s.u', $orderAmendment['attributes']['createdAt'])->format('Y-m-d H:i:s'),
                'updatedAt' => DateTime::createFromFormat('Y-m-d H:i:s.u', $orderAmendment['attributes']['updatedAt'])->format('Y-m-d H:i:s'),
            ]);
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
