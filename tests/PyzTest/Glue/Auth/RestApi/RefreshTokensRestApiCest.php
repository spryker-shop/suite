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
 * @group RefreshTokensRestApiCest
 * Add your own group annotations below this line
 * @group EndToEnd
 */
class RefreshTokensRestApiCest
{
    protected const INVALID_REFRESH_TOKEN = 'invalid refresh token';

    /**
     * @var \PyzTest\Glue\Auth\RestApi\RefreshTokensRestApiFixtures
     */
    protected $fixtures;

    /**
     * @param \PyzTest\Glue\Auth\AuthRestApiTester $I
     *
     * @return void
     */
    public function loadFixtures(AuthRestApiTester $I): void
    {
        $this->fixtures = $I->loadFixtures(RefreshTokensRestApiFixtures::class);
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\Auth\AuthRestApiTester $I
     *
     * @return void
     */
    public function refreshValidNotExpiredAccessTokenWithValidRefreshToken(AuthRestApiTester $I): void
    {
        //Act
        $I->sendPOST(AuthRestApiConfig::RESOURCE_REFRESH_TOKENS, [
            'data' => [
                'type' => AuthRestApiConfig::RESOURCE_REFRESH_TOKENS,
                'attributes' => [
                    'refreshToken' => $this->fixtures->getOauthResponseTransfer()->getRefreshToken(),
                ],
            ],
        ]);

        //Assert
        $this->assertResponse($I, HttpCode::CREATED);
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\Auth\AuthRestApiTester $I
     *
     * @return void
     */
    public function reuseRefreshValidToken(AuthRestApiTester $I): void
    {
        //Arrange
        $refreshToken = $this->fixtures->getOauthResponseTransfer()->getRefreshToken();
        $refreshedToken = $this->getRefreshedToken($I, $refreshToken);

        //Act
        $I->sendPOST(AuthRestApiConfig::RESOURCE_REFRESH_TOKENS, [
            'data' => [
                'type' => AuthRestApiConfig::RESOURCE_REFRESH_TOKENS,
                'attributes' => [
                    'refreshToken' => $refreshedToken,
                ],
            ],
        ]);

        //Assert
        $this->assertResponse($I, HttpCode::CREATED);
        $I->assertNotEquals($refreshToken, $refreshedToken);
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\Auth\AuthRestApiTester $I
     *
     * @return void
     */
    public function refreshAccessTokenWithInvalidRefreshToken(AuthRestApiTester $I): void
    {
        //Act
        $I->sendPOST(AuthRestApiConfig::RESOURCE_REFRESH_TOKENS, [
            'data' => [
                'type' => AuthRestApiConfig::RESOURCE_REFRESH_TOKENS,
                'attributes' => [
                    'refreshToken' => static::INVALID_REFRESH_TOKEN,
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
    public function refreshAccessTokenWithEmptyValueInRefreshToken(AuthRestApiTester $I): void
    {
        //Act
        $I->sendPOST(AuthRestApiConfig::RESOURCE_REFRESH_TOKENS, [
            'data' => [
                'type' => AuthRestApiConfig::RESOURCE_REFRESH_TOKENS,
                'attributes' => [
                    'refreshToken' => static::INVALID_REFRESH_TOKEN,
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
    public function invalidRefreshTokenRequest(AuthRestApiTester $I): void
    {
        //Act
        $I->sendPOST(AuthRestApiConfig::RESOURCE_REFRESH_TOKENS, [
                'type' => AuthRestApiConfig::RESOURCE_REFRESH_TOKENS,
                'attributes' => [
                    'refreshToken' => static::INVALID_REFRESH_TOKEN,
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
    public function invalidRefreshTokenRequestType(AuthRestApiTester $I): void
    {
        //Act
        $I->sendPOST(AuthRestApiConfig::RESOURCE_REFRESH_TOKENS, [
            'data' => [
                'type' => AuthRestApiConfig::RESOURCE_ACCESS_TOKENS,
                'attributes' => [
                    'refreshToken' => static::INVALID_REFRESH_TOKEN,
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
    public function emptyRefreshTokenRequestType(AuthRestApiTester $I): void
    {
        //Act
        $I->sendPOST(AuthRestApiConfig::RESOURCE_REFRESH_TOKENS, [
            'data' => [
                'type' => '',
                'attributes' => [
                    'refreshToken' => static::INVALID_REFRESH_TOKEN,
                ],
            ],
        ]);

        //Assert
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

    /**
     * @param \PyzTest\Glue\Auth\AuthRestApiTester $I
     * @param string $refreshToken
     *
     * @return string
     */
    protected function getRefreshedToken(AuthRestApiTester $I, string $refreshToken): string
    {
        $I->sendPOST(AuthRestApiConfig::RESOURCE_REFRESH_TOKENS, [
            'data' => [
                'type' => AuthRestApiConfig::RESOURCE_REFRESH_TOKENS,
                'attributes' => [
                    'refreshToken' => $refreshToken,
                ],
            ],
        ]);

        $this->assertResponse($I, HttpCode::CREATED);

        return current($I->grabDataFromResponseByJsonPath('$.data.attributes.refreshToken'));
    }
}
