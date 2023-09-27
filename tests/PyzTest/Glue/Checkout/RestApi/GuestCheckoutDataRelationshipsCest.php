<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Glue\Checkout\RestApi;

use Codeception\Util\HttpCode;
use Pyz\Glue\CheckoutRestApi\CheckoutRestApiConfig;
use PyzTest\Glue\Checkout\CheckoutApiTester;
use PyzTest\Glue\Checkout\RestApi\Fixtures\GuestCheckoutDataRelationshipsFixtures;
use Spryker\Glue\ServicePointsRestApi\ServicePointsRestApiConfig;

/**
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Glue
 * @group Checkout
 * @group RestApi
 * @group GuestCheckoutDataRelationshipsCest
 * Add your own group annotations below this line
 */
class GuestCheckoutDataRelationshipsCest
{
    /**
     * @var string
     */
    protected const HEADER_ANONYMOUS_CUSTOMER_UNIQUE_ID = 'X-Anonymous-Customer-Unique-Id';

    /**
     * @var \PyzTest\Glue\Checkout\RestApi\Fixtures\GuestCheckoutDataRelationshipsFixtures
     */
    protected GuestCheckoutDataRelationshipsFixtures $fixtures;

    /**
     * @param \PyzTest\Glue\Checkout\CheckoutApiTester $I
     *
     * @return void
     */
    public function loadFixtures(CheckoutApiTester $I): void
    {
        /** @var \PyzTest\Glue\Checkout\RestApi\Fixtures\GuestCheckoutDataRelationshipsFixtures $fixtures */
        $fixtures = $I->loadFixtures(GuestCheckoutDataRelationshipsFixtures::class);
        $this->fixtures = $fixtures;
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\Checkout\CheckoutApiTester $I
     *
     * @return void
     */
    public function requestCheckoutDataIncludesServicePointsRelationship(CheckoutApiTester $I): void
    {
        // Arrange
        $I->haveHttpHeader(
            static::HEADER_ANONYMOUS_CUSTOMER_UNIQUE_ID,
            $this->fixtures->getGuestCustomerReference(),
        );

        $guestQuoteTransfer = $this->fixtures->getGuestQuoteTransfer();
        $servicePointUuid = $guestQuoteTransfer->getItems()[0]->getServicePoint()->getUuid();
        $itemGroupKey = $guestQuoteTransfer->getItems()[0]->getGroupKey();

        $url = $I->buildCheckoutDataUrl([
            ServicePointsRestApiConfig::RESOURCE_SERVICE_POINTS,
        ]);
        $requestPayload = [
            'data' => [
                'type' => CheckoutRestApiConfig::RESOURCE_CHECKOUT_DATA,
                'attributes' => [
                    'idCart' => $guestQuoteTransfer->getUuid(),
                    'servicePoints' => $I->getServicePointsRequestPayload($servicePointUuid, [$itemGroupKey]),
                ],
            ],
        ];

        // Act
        $I->sendPOST($url, $requestPayload);

        // Assert
        $includedServicePointsData = $I->getDataFromResponseByJsonPath('$.data.relationships.service-points.data');
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesOpenApiSchema();

        $I->amSure('The response contains included service points')
            ->whenI()
            ->seeIncludesContainResourceOfType(ServicePointsRestApiConfig::RESOURCE_SERVICE_POINTS);
        $I->amSure('The returned resource contains included service point with correct service point uuid')
            ->whenI()
            ->assertSame($servicePointUuid, $includedServicePointsData[0]['id']);
    }
}
