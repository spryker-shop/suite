<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Glue\Auth\RestApi;

use Codeception\Util\HttpCode;
use PyzTest\Glue\Auth\AuthRestApiTester;
use Spryker\Glue\AuthRestApi\AuthRestApiConfig;

/**
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Glue
 * @group Auth
 * @group RestApi
 * @group AccessTokensApiCest
 * Add your own group annotations below this line
 * @group EndToEnd
 */
class AccessTokensApiCest
{
    /**
     * @var \PyzTest\Glue\Auth\RestApi\AccessTokensApiFixtures
     */
    protected $fixtures;

    /**
     * @param \PyzTest\Glue\Auth\AuthRestApiTester $I
     *
     * @return void
     */
    public function loadFixtures(AuthRestApiTester $I): void
    {
        $this->fixtures = $I->loadFixtures(AccessTokensApiFixtures::class);
    }

    /**
     * @param \PyzTest\Glue\Auth\AuthRestApiTester $I
     *
     * @return void
     */
    public function requestAccessTokenForExistingCustomer(AuthRestApiTester $I): void
    {
        //Act
        $I->sendPOST(AuthRestApiConfig::RESOURCE_ACCESS_TOKENS, [
            'data' => [
                'type' => AuthRestApiConfig::RESOURCE_ACCESS_TOKENS,
                'attributes' => [
                    'username' => $this->fixtures->getCustomerTransfer()->getEmail(),
                    'password' => AccessTokensApiFixtures::TEST_PASSWORD,
                ],
            ],
        ]);

        //Arrange
        $this->assertResponse($I, HttpCode::CREATED);
        $I->assertNotEmpty($I->grabDataFromResponseByJsonPath('$.data.attributes.accessToken'));
    }

    /**
     * @param \PyzTest\Glue\Auth\AuthRestApiTester $I
     *
     * @return void
     */
    public function requestAccessTokenForNotExistingCustomer(AuthRestApiTester $I): void
    {
        //Act
        $I->sendPOST(AuthRestApiConfig::RESOURCE_ACCESS_TOKENS, [
            'data' => [
                'type' => AuthRestApiConfig::RESOURCE_ACCESS_TOKENS,
                'attributes' => [
                    'username' => uniqid($this->fixtures->getCustomerTransfer()->getEmail()),
                    'password' => AccessTokensApiFixtures::TEST_PASSWORD,
                ],
            ],
        ]);

        //Arrange
        $this->assertResponse($I, HttpCode::UNAUTHORIZED);
    }

    /**
     * @param \PyzTest\Glue\Auth\AuthRestApiTester $I
     *
     * @return void
     */
    public function requestAccessTokenWithWrongCredentials(AuthRestApiTester $I): void
    {
        //Act
        $I->sendPOST(AuthRestApiConfig::RESOURCE_ACCESS_TOKENS, [
            'data' => [
                'type' => AuthRestApiConfig::RESOURCE_ACCESS_TOKENS,
                'attributes' => [
                    'username' => uniqid($this->fixtures->getCustomerTransfer()->getEmail()),
                    'password' => uniqid(AccessTokensApiFixtures::TEST_PASSWORD),
                ],
            ],
        ]);

        //Arrange
        $this->assertResponse($I, HttpCode::UNAUTHORIZED);
    }

    /**
     * @param \PyzTest\Glue\Auth\AuthRestApiTester $I
     *
     * @return void
     */
    public function requestAccessTokenWithEmptyPassword(AuthRestApiTester $I): void
    {
        //Act
        $I->sendPOST(AuthRestApiConfig::RESOURCE_ACCESS_TOKENS, [
            'data' => [
                'type' => AuthRestApiConfig::RESOURCE_ACCESS_TOKENS,
                'attributes' => [
                    'username' => $this->fixtures->getCustomerTransfer()->getEmail(),
                    'password' => '',
                ],
            ],
        ]);

        //Arrange
        $this->assertResponse($I, HttpCode::UNPROCESSABLE_ENTITY);
    }

    /**
     * @param \PyzTest\Glue\Auth\AuthRestApiTester $I
     *
     * @return void
     */
    public function requestAccessTokenWithEmptyUsername(AuthRestApiTester $I): void
    {
        //Act
        $I->sendPOST(AuthRestApiConfig::RESOURCE_ACCESS_TOKENS, [
            'data' => [
                'type' => AuthRestApiConfig::RESOURCE_ACCESS_TOKENS,
                'attributes' => [
                    'username' => '',
                    'password' => AccessTokensApiFixtures::TEST_PASSWORD,
                ],
            ],
        ]);

        //Arrange
        $this->assertResponse($I, HttpCode::UNPROCESSABLE_ENTITY);
    }

    /**
     * @param \PyzTest\Glue\Auth\AuthRestApiTester $I
     *
     * @return void
     */
    public function invalidAccessTokenRequest(AuthRestApiTester $I): void
    {
        //Act
        $I->sendPOST(AuthRestApiConfig::RESOURCE_ACCESS_TOKENS, [
            'type' => AuthRestApiConfig::RESOURCE_ACCESS_TOKENS,
            'attributes' => [
                'username' => $this->fixtures->getCustomerTransfer()->getEmail(),
                'password' => AccessTokensApiFixtures::TEST_PASSWORD,
            ],
        ]);

        //Arrange
        $this->assertResponse($I, HttpCode::BAD_REQUEST);
    }

    /**
     * @param \PyzTest\Glue\Auth\AuthRestApiTester $I
     *
     * @return void
     */
    public function invalidAccessTokenRequestType(AuthRestApiTester $I): void
    {
        //Act
        $I->sendPOST(AuthRestApiConfig::RESOURCE_ACCESS_TOKENS, [
            'data' => [
                'type' => AuthRestApiConfig::RESOURCE_REFRESH_TOKENS,
                'attributes' => [
                    'username' => $this->fixtures->getCustomerTransfer()->getEmail(),
                    'password' => AccessTokensApiFixtures::TEST_PASSWORD,
                ],
            ],
        ]);

        //Arrange
        $this->assertResponse($I, HttpCode::BAD_REQUEST);
    }

    /**
     * @param \PyzTest\Glue\Auth\AuthRestApiTester $I
     * @param int $responseCode
     *
     * @return void
     */
    protected function assertResponse(AuthRestApiTester $I, int $responseCode): void
    {
        $I->seeResponseCodeIs($responseCode);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesOpenApiSchema();
    }
}
