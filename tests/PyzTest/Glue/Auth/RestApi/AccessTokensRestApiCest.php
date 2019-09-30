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
 * @group AccessTokensRestApiCest
 * Add your own group annotations below this line
 * @group EndToEnd
 */
class AccessTokensRestApiCest
{
    /**
     * @var \PyzTest\Glue\Auth\RestApi\AccessTokensRestApiFixtures
     */
    protected $fixtures;

    /**
     * @param \PyzTest\Glue\Auth\AuthRestApiTester $I
     *
     * @return void
     */
    public function loadFixtures(AuthRestApiTester $I): void
    {
        $this->fixtures = $I->loadFixtures(AccessTokensRestApiFixtures::class);
    }

    /**
     * @depends loadFixtures
     *
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
                    'username' => $this->fixtures->getCustomerTransferWithCompanyUser()->getEmail(),
                    'password' => AccessTokensRestApiFixtures::TEST_PASSWORD,
                ],
            ],
        ]);

        //Assert
        $this->assertResponse($I, HttpCode::CREATED);
        $I->assertNotEmpty($I->grabDataFromResponseByJsonPath('$.data.attributes.accessToken'));
    }

    /**
     * @depends loadFixtures
     *
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
                    'username' => uniqid($this->fixtures->getCustomerTransferWithCompanyUser()->getEmail()),
                    'password' => AccessTokensRestApiFixtures::TEST_PASSWORD,
                ],
            ],
        ]);

        //Assert
        $this->assertResponse($I, HttpCode::UNAUTHORIZED);
    }

    /**
     * @depends loadFixtures
     *
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
                    'username' => uniqid($this->fixtures->getCustomerTransferWithCompanyUser()->getEmail()),
                    'password' => uniqid(AccessTokensRestApiFixtures::TEST_PASSWORD),
                ],
            ],
        ]);

        //Assert
        $this->assertResponse($I, HttpCode::UNAUTHORIZED);
    }

    /**
     * @depends loadFixtures
     *
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
                    'username' => $this->fixtures->getCustomerTransferWithCompanyUser()->getEmail(),
                    'password' => '',
                ],
            ],
        ]);

        //Assert
        $this->assertResponse($I, HttpCode::UNPROCESSABLE_ENTITY);
    }

    /**
     * @depends loadFixtures
     *
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
                    'password' => AccessTokensRestApiFixtures::TEST_PASSWORD,
                ],
            ],
        ]);

        //Assert
        $this->assertResponse($I, HttpCode::UNPROCESSABLE_ENTITY);
    }

    /**
     * @depends loadFixtures
     *
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
                'username' => $this->fixtures->getCustomerTransferWithCompanyUser()->getEmail(),
                'password' => AccessTokensRestApiFixtures::TEST_PASSWORD,
            ],
        ]);

        //Assert
        $this->assertResponse($I, HttpCode::BAD_REQUEST);
    }

    /**
     * @depends loadFixtures
     *
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
                    'username' => $this->fixtures->getCustomerTransferWithCompanyUser()->getEmail(),
                    'password' => AccessTokensRestApiFixtures::TEST_PASSWORD,
                ],
            ],
        ]);

        //Assert
        $this->assertResponse($I, HttpCode::BAD_REQUEST);
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\Auth\AuthRestApiTester $I
     *
     * @return void
     */
    public function requestAccessTokenForExistingCustomerWithoutCompanyUser(AuthRestApiTester $I): void
    {
        //Act
        $I->sendPOST(AuthRestApiConfig::RESOURCE_ACCESS_TOKENS, [
            'data' => [
                'type' => AuthRestApiConfig::RESOURCE_ACCESS_TOKENS,
                'attributes' => [
                    'username' => $this->fixtures->getCustomerTransferWithoutCompanyUser()->getEmail(),
                    'password' => AccessTokensRestApiFixtures::TEST_PASSWORD,
                ],
            ],
        ]);

        //Assert
        $this->assertResponse($I, HttpCode::CREATED);
        $I->assertNull(current($I->grabDataFromResponseByJsonPath('$.data.attributes.idCompanyUser')));
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\Auth\AuthRestApiTester $I
     *
     * @return void
     */
    public function requestAccessTokenForExistingCustomerWithCompanyUser(AuthRestApiTester $I): void
    {
        //Act
        $I->sendPOST(AuthRestApiConfig::RESOURCE_ACCESS_TOKENS, [
            'data' => [
                'type' => AuthRestApiConfig::RESOURCE_ACCESS_TOKENS,
                'attributes' => [
                    'username' => $this->fixtures->getCustomerTransferWithCompanyUser()->getEmail(),
                    'password' => AccessTokensRestApiFixtures::TEST_PASSWORD,
                ],
            ],
        ]);

        //Assert
        $this->assertResponse($I, HttpCode::CREATED);
        $I->assertNotNull(current($I->grabDataFromResponseByJsonPath('$.data.attributes.idCompanyUser')));
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
