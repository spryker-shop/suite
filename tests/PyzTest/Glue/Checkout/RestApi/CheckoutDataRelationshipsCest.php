<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Glue\Checkout\RestApi;

use Codeception\Util\HttpCode;
use Pyz\Glue\CheckoutRestApi\CheckoutRestApiConfig;
use PyzTest\Glue\Checkout\CheckoutApiTester;
use PyzTest\Glue\Checkout\RestApi\Fixtures\CheckoutDataRelationshipsFixtures;
use Spryker\Glue\ServicePointsRestApi\ServicePointsRestApiConfig;

/**
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Glue
 * @group Checkout
 * @group RestApi
 * @group CheckoutDataRelationshipsCest
 * Add your own group annotations below this line
 */
class CheckoutDataRelationshipsCest
{
    /**
     * @var \PyzTest\Glue\Checkout\RestApi\Fixtures\CheckoutDataRelationshipsFixtures
     */
    protected CheckoutDataRelationshipsFixtures $fixtures;

    /**
     * @param \PyzTest\Glue\Checkout\CheckoutApiTester $I
     *
     * @return void
     */
    public function loadFixtures(CheckoutApiTester $I): void
    {
        /** @var \PyzTest\Glue\Checkout\RestApi\Fixtures\CheckoutDataRelationshipsFixtures $fixtures */
        $fixtures = $I->loadFixtures(CheckoutDataRelationshipsFixtures::class);
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
        $I->authorizeCustomerToGlue($this->fixtures->getCustomerTransfer());

        $quoteTransfer = $this->fixtures->getQuoteTransfer();
        $servicePointUuid = $quoteTransfer->getItems()[0]->getServicePoint()->getUuid();
        $itemGroupKey = $quoteTransfer->getItems()[0]->getGroupKey();

        $url = $I->buildCheckoutDataUrl([
            ServicePointsRestApiConfig::RESOURCE_SERVICE_POINTS,
        ]);
        $requestPayload = [
            'data' => [
                'type' => CheckoutRestApiConfig::RESOURCE_CHECKOUT_DATA,
                'attributes' => [
                    'idCart' => $quoteTransfer->getUuid(),
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
