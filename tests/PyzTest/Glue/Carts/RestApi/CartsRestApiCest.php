<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Glue\Carts\RestApi;

use Codeception\Util\HttpCode;
use PyzTest\Glue\Carts\CartsApiTester;
use PyzTest\Glue\Carts\RestApi\Fixtures\CartsRestApiFixtures;

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
class CartsRestApiCest
{
    /**
     * @var \PyzTest\Glue\Carts\RestApi\Fixtures\CartsRestApiFixtures
     */
    protected $fixtures;

    /**
     * @param \PyzTest\Glue\Carts\CartsApiTester $I
     *
     * @return void
     */
    public function loadFixtures(CartsApiTester $I): void
    {
        /** @var \PyzTest\Glue\Carts\RestApi\Fixtures\CartsRestApiFixtures $fixtures */
        $fixtures = $I->loadFixtures(CartsRestApiFixtures::class);

        $this->fixtures = $fixtures;
    }

    /**
     * @param \PyzTest\Glue\Carts\CartsApiTester $I
     *
     * @return void
     */
    protected function authorizeCustomer(CartsApiTester $I): void
    {
        $token = $I->haveAuthorizationToGlue($this->fixtures->getCustomerTransfer())->getAccessToken();

        $I->amBearerAuthenticated($token);
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\Carts\CartsApiTester $I
     *
     * @return void
     */
    public function requestCustomerCarts(CartsApiTester $I): void
    {
        // Arrange
        $quoteTransfer = $I->createPersistentQuote(
            $I,
            $this->fixtures->getCustomerTransfer(),
            [],
        );

        $url = $I->buildCustomerCartUrl($quoteTransfer->getCustomer()->getCustomerReference());

        $oauthResponseTransfer = $I->haveAuthorizationToGlue($quoteTransfer->getCustomer());
        $I->amBearerAuthenticated($oauthResponseTransfer->getAccessToken());

        // Act
        $I->sendGET($url);

        // Assert
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseMatchesOpenApiSchema();
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\Carts\CartsApiTester $I
     *
     * @return void
     */
    public function requestCustomerCartsAuthorizationError(CartsApiTester $I): void
    {
        // Arrange
        $quoteTransfer = $I->createPersistentQuote(
            $I,
            $this->fixtures->getCustomerTransfer(),
            [],
        );

        $url = $I->buildCustomerCartUrl('wrongCustomerReference');

        $oauthResponseTransfer = $I->haveAuthorizationToGlue($quoteTransfer->getCustomer());
        $I->amBearerAuthenticated($oauthResponseTransfer->getAccessToken());

        // Act
        $I->sendGET($url);

        // Assert
        $I->seeResponseCodeIs(HttpCode::FORBIDDEN);
        $I->seeResponseMatchesOpenApiSchema();
    }
}
