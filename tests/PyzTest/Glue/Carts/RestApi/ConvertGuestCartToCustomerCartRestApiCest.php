<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Glue\Carts\RestApi;

use Codeception\Util\HttpCode;
use PyzTest\Glue\Carts\CartsApiTester;
use Spryker\Glue\CartsRestApi\CartsRestApiConfig;

/**
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Glue
 * @group Carts
 * @group RestApi
 * @group ConvertGuestCartToCustomerCartRestApiCest
 * Add your own group annotations below this line
 * @group EndToEnd
 */
class ConvertGuestCartToCustomerCartRestApiCest
{
    /**
     * @var \PyzTest\Glue\Carts\RestApi\CartsRestApiFixtures
     */
    protected $fixtures;

    /**
     * @param \PyzTest\Glue\Carts\CartsApiTester $I
     *
     * @return void
     */
    public function loadFixtures(CartsApiTester $I): void
    {
        $this->fixtures = $I->loadFixtures(CartsRestApiFixtures::class);
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\Carts\CartsApiTester $I
     *
     * @return void
     */
    public function requestGuestCartBecomesCustomerCartAfterCustomerLogin(CartsApiTester $I): void
    {
        // Arrange
        $this->requestCustomerLoginWithXAnonymousCustomerUniqueIdHeader($I);
        $cartUuid = $this->fixtures->getGuestQuoteTransfer1()->getUuid();

        // Act
        $I->sendGET(
            $I->formatUrl(
                '{resourceCarts}/{cartUuid}?include={relationshipItems}',
                [
                    'cartUuid' => $cartUuid,
                    'resourceCarts' => CartsRestApiConfig::RESOURCE_CARTS,
                    'relationshipItems' => CartsRestApiConfig::RESOURCE_CART_ITEMS,
                ]
            )
        );

        // Assert
        $I->assertResponse(HttpCode::OK);

        $I->amSure(sprintf('Returned resource is of type %s', CartsRestApiConfig::RESOURCE_CARTS))
            ->whenI()
            ->seeResponseDataContainsSingleResourceOfType(CartsRestApiConfig::RESOURCE_CARTS);

        $I->amSure(sprintf('Returned resource has include of type %s', CartsRestApiConfig::RESOURCE_CART_ITEMS))
            ->whenI()
            ->seeSingleResourceHasRelationshipByTypeAndId(
                CartsRestApiConfig::RESOURCE_CART_ITEMS,
                $this->fixtures->getProductConcreteTransfer1()->getSku()
            );

        $I->amSure('Returned resource has correct id')
            ->whenI()
            ->seeSingleResourceIdEqualTo($cartUuid);
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\Carts\CartsApiTester $I
     *
     * @return void
     */
    public function requestGuestCartCollectionIsEmpty(CartsApiTester $I): void
    {
        // Arrange
        $I->haveHttpHeader(
            CartsRestApiConfig::HEADER_ANONYMOUS_CUSTOMER_UNIQUE_ID,
            $this->fixtures->getValueForAnonymousCustomerReference1()
        );

        // Act
        $I->sendGET(
            $I->formatUrl(
                '{resourceGuestCarts}',
                [
                    'resourceGuestCarts' => CartsRestApiConfig::RESOURCE_GUEST_CARTS,
                ]
            )
        );

        // Assert
        $I->assertResponse(HttpCode::OK);
        $I->seeResponseDataContainsEmptyCollection();
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\Carts\CartsApiTester $I
     *
     * @return void
     */
    protected function requestCustomerLoginWithXAnonymousCustomerUniqueIdHeader(CartsApiTester $I): void
    {
        $I->haveHttpHeader(
            CartsRestApiConfig::HEADER_ANONYMOUS_CUSTOMER_UNIQUE_ID,
            $this->fixtures->getValueForAnonymousCustomerReference1()
        );
        $token = $I->haveAuthorizationToGlue(
            $this->fixtures->getCustomerTransfer(),
            $I::ANONYMOUS_PREFIX . $this->fixtures->getValueForAnonymousCustomerReference1()
        )->getAccessToken();

        $I->amBearerAuthenticated($token);
    }
}
