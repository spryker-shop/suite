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
    public function requestRefreshTokenWithValidRefreshTokenValue(AuthRestApiTester $I): void
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
    public function requestRefreshTokenWithInvalidRefreshTokenValue(AuthRestApiTester $I): void
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
    public function requestRefreshTokenWithEmptyRefreshTokenValue(AuthRestApiTester $I): void
    {
        //Act
        $I->sendPOST(AuthRestApiConfig::RESOURCE_REFRESH_TOKENS, [
            'data' => [
                'type' => AuthRestApiConfig::RESOURCE_REFRESH_TOKENS,
                'attributes' => [
                    'refreshToken' => '',
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
    public function requestRefreshTokenWithInvalidPostData(AuthRestApiTester $I): void
    {
        //Act
        $I->sendPOST(AuthRestApiConfig::RESOURCE_REFRESH_TOKENS, [
            'type' => AuthRestApiConfig::RESOURCE_REFRESH_TOKENS,
            'attributes' => [
                'refreshToken' => $this->fixtures->getOauthResponseTransfer()->getRefreshToken(),
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
    public function requestRefreshTokenWithInvalidRequestType(AuthRestApiTester $I): void
    {
        //Act
        $I->sendPOST(AuthRestApiConfig::RESOURCE_REFRESH_TOKENS, [
            'data' => [
                'type' => AuthRestApiConfig::RESOURCE_ACCESS_TOKENS,
                'attributes' => [
                    'refreshToken' => $this->fixtures->getOauthResponseTransfer()->getRefreshToken(),
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
    public function requestRefreshTokenWithEmptyType(AuthRestApiTester $I): void
    {
        //Act
        $I->sendPOST(AuthRestApiConfig::RESOURCE_REFRESH_TOKENS, [
            'data' => [
                'type' => '',
                'attributes' => [
                    'refreshToken' => $this->fixtures->getOauthResponseTransfer()->getRefreshToken(),
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
}
