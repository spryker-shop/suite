<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Glue\Checkout\RestApi;

use Codeception\Util\HttpCode;
use Generated\Shared\Transfer\RestCheckoutDataResponseAttributesTransfer;
use PyzTest\Glue\Checkout\CheckoutApiTester;
use PyzTest\Glue\Checkout\RestApi\Fixtures\ServicePointCheckoutDataRestApiFixtures;
use Spryker\Glue\CheckoutRestApi\CheckoutRestApiConfig;

/**
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Glue
 * @group Checkout
 * @group RestApi
 * @group ServicePointCheckoutDataRestApiCest
 * Add your own group annotations below this line
 * @group EndToEnd
 */
class ServicePointCheckoutDataRestApiCest
{
    /**
     * @var \PyzTest\Glue\Checkout\RestApi\Fixtures\ServicePointCheckoutDataRestApiFixtures
     */
    protected ServicePointCheckoutDataRestApiFixtures $fixtures;

    /**
     * @param \PyzTest\Glue\Checkout\CheckoutApiTester $I
     *
     * @return void
     */
    public function loadFixtures(CheckoutApiTester $I): void
    {
        $fixtures = $I->loadFixtures(ServicePointCheckoutDataRestApiFixtures::class);
        $this->fixtures = $fixtures;
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\Checkout\CheckoutApiTester $I
     *
     * @return void
     */
    public function requestCheckoutDataReturnsSelectedServicePoints(CheckoutApiTester $I): void
    {
        // Arrange
        $I->authorizeCustomerToGlue($this->fixtures->getCustomerTransfer());

        $quoteTransfer = $this->fixtures->getQuoteTransfer();
        $servicePointUuid = $quoteTransfer->getItems()[0]->getServicePoint()->getUuid();
        $itemGroupKey = $quoteTransfer->getItems()[0]->getGroupKey();

        $url = $I->buildCheckoutDataUrl();
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
        $selectedServicePointsData = $I->getDataFromResponseByJsonPath('$.data.attributes.selectedServicePoints');
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesOpenApiSchema();

        $I->assertCheckoutDataResponseResourceHasCorrectData();
        $I->amSure('The returned resource contains selected service point')
            ->whenI()
            ->seeSingleResourceHasAttribute(RestCheckoutDataResponseAttributesTransfer::SELECTED_SERVICE_POINTS);
        $I->amSure('The returned resource contains selected service point with correct quote item group key')
            ->whenI()
            ->assertSame($itemGroupKey, $selectedServicePointsData[0]['items'][0]);
        $I->amSure('The returned resource contains selected service point with correct service point id')
            ->whenI()
            ->assertSame($servicePointUuid, $selectedServicePointsData[0]['idServicePoint']);
        $I->amSure('The returned resource has correct self link')
            ->whenI()
            ->seeSingleResourceHasSelfLink($url);
    }
}
