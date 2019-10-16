<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Glue\CompanyUserAuth\RestApi;

use Codeception\Util\HttpCode;
use PyzTest\Glue\CompanyUserAuth\CompanyUserAuthRestApiTester;
use Spryker\Glue\CompanyUserAuthRestApi\CompanyUserAuthRestApiConfig;

/**
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Glue
 * @group CompanyUserAuth
 * @group RestApi
 * @group CompanyUserAuthAccessTokensRestApiCest
 * Add your own group annotations below this line
 * @group EndToEnd
 */
class CompanyUserAuthAccessTokensRestApiCest
{
    /**
     * @var \PyzTest\Glue\CompanyUserAuth\RestApi\CompanyUserAuthAccessTokensRestApiFixtures
     */
    protected $fixtures;

    /**
     * @param \PyzTest\Glue\CompanyUserAuth\CompanyUserAuthRestApiTester $I
     *
     * @return void
     */
    public function loadFixtures(CompanyUserAuthRestApiTester $I): void
    {
        $this->fixtures = $I->loadFixtures(CompanyUserAuthAccessTokensRestApiFixtures::class);
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\CompanyUserAuth\CompanyUserAuthRestApiTester $I
     *
     * @return void
     */
    public function requestCompanyUserAccessTokenForExistingCustomerWithCompanyUser(CompanyUserAuthRestApiTester $I): void
    {
        //Arrange
        $I->amBearerAuthenticated($this->fixtures->getOauthResponseTransferForCompanyUser()->getAccessToken());

        //Act
        $I->sendPOST(CompanyUserAuthRestApiConfig::RESOURCE_COMPANY_USER_ACCESS_TOKENS, [
            'data' => [
                'type' => CompanyUserAuthRestApiConfig::RESOURCE_COMPANY_USER_ACCESS_TOKENS,
                'attributes' => [
                    'idCompanyUser' => $this->fixtures->getOauthResponseTransferForCompanyUser()->getIdCompanyUser(),
                ],
            ],
        ]);

        //Assert
        $this->assertResponse($I, HttpCode::CREATED);
        $I->seeSingleResourceHasSelfLink($I->formatFullUrl(CompanyUserAuthRestApiConfig::RESOURCE_COMPANY_USER_ACCESS_TOKENS));
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\CompanyUserAuth\CompanyUserAuthRestApiTester $I
     *
     * @return void
     */
    public function requestCompanyUserAccessTokenForExistingCustomerWithWrongType(CompanyUserAuthRestApiTester $I): void
    {
        //Arrange
        $I->amBearerAuthenticated($this->fixtures->getOauthResponseTransferForCompanyUser()->getAccessToken());

        //Act
        $I->sendPOST(CompanyUserAuthRestApiConfig::RESOURCE_COMPANY_USER_ACCESS_TOKENS, [
            'data' => [
                'type' => uniqid(CompanyUserAuthRestApiConfig::RESOURCE_COMPANY_USER_ACCESS_TOKENS),
                'attributes' => [
                    'idCompanyUser' => $this->fixtures->getOauthResponseTransferForCompanyUser()->getIdCompanyUser(),
                ],
            ],
        ]);

        //Assert
        $this->assertResponse($I, HttpCode::BAD_REQUEST);
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\CompanyUserAuth\CompanyUserAuthRestApiTester $I
     *
     * @return void
     */
    public function requestCompanyUserAccessTokenForExistingCustomerWithInvalidPostData(CompanyUserAuthRestApiTester $I): void
    {
        //Arrange
        $I->amBearerAuthenticated($this->fixtures->getOauthResponseTransferForCompanyUser()->getAccessToken());

        //Act
        $I->sendPOST(CompanyUserAuthRestApiConfig::RESOURCE_COMPANY_USER_ACCESS_TOKENS, [
            'type' => uniqid(CompanyUserAuthRestApiConfig::RESOURCE_COMPANY_USER_ACCESS_TOKENS),
            'attributes' => [
                'idCompanyUser' => $this->fixtures->getOauthResponseTransferForCompanyUser()->getIdCompanyUser(),
            ],
        ]);

        //Assert
        $this->assertResponse($I, HttpCode::BAD_REQUEST);
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\CompanyUserAuth\CompanyUserAuthRestApiTester $I
     *
     * @return void
     */
    public function requestCompanyUserAccessTokenWithUuidOfAnotherCompanyUser(CompanyUserAuthRestApiTester $I): void
    {
        //Arrange
        $I->amBearerAuthenticated($this->fixtures->getOauthResponseTransferForNonCompanyUser()->getAccessToken());

        //Act
        $I->sendPOST(CompanyUserAuthRestApiConfig::RESOURCE_COMPANY_USER_ACCESS_TOKENS, [
            'data' => [
                'type' => CompanyUserAuthRestApiConfig::RESOURCE_COMPANY_USER_ACCESS_TOKENS,
                'attributes' => [
                    'idCompanyUser' => $this->fixtures->getOauthResponseTransferForCompanyUser()->getIdCompanyUser(),
                ],
            ],
        ]);

        //Assert
        $this->assertResponse($I, HttpCode::UNAUTHORIZED);
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\CompanyUserAuth\CompanyUserAuthRestApiTester $I
     *
     * @return void
     */
    public function requestCompanyUserAccessTokenWithNoExistingIdCompanyUser(CompanyUserAuthRestApiTester $I): void
    {
        //Arrange
        $I->amBearerAuthenticated($this->fixtures->getOauthResponseTransferForCompanyUser()->getAccessToken());

        //Act
        $I->sendPOST(CompanyUserAuthRestApiConfig::RESOURCE_COMPANY_USER_ACCESS_TOKENS, [
            'data' => [
                'type' => CompanyUserAuthRestApiConfig::RESOURCE_COMPANY_USER_ACCESS_TOKENS,
                'attributes' => [
                    'idCompanyUser' => uniqid(),
                ],
            ],
        ]);

        //Assert
        $this->assertResponse($I, HttpCode::UNPROCESSABLE_ENTITY);
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\CompanyUserAuth\CompanyUserAuthRestApiTester $I
     *
     * @return void
     */
    public function requestCompanyUserAccessTokenWithEmptyIdCompanyUser(CompanyUserAuthRestApiTester $I): void
    {
        //Arrange
        $I->amBearerAuthenticated($this->fixtures->getOauthResponseTransferForCompanyUser()->getAccessToken());

        //Act
        $I->sendPOST(CompanyUserAuthRestApiConfig::RESOURCE_COMPANY_USER_ACCESS_TOKENS, [
            'data' => [
                'type' => CompanyUserAuthRestApiConfig::RESOURCE_COMPANY_USER_ACCESS_TOKENS,
                'attributes' => [
                    'idCompanyUser' => '',
                ],
            ],
        ]);

        //Assert
        $this->assertResponse($I, HttpCode::UNPROCESSABLE_ENTITY);
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\CompanyUserAuth\CompanyUserAuthRestApiTester $I
     *
     * @return void
     */
    public function requestCompanyUserAccessTokenForCustomerWithTwoCompanyUsers(CompanyUserAuthRestApiTester $I): void
    {
        //Arrange
        $firstCompanyUserAccessToken = $this->fixtures->getOauthResponseTransferForCustomerWithTwoCompanyUsers()->getAccessToken();

        $I->amBearerAuthenticated($firstCompanyUserAccessToken);

        //Act
        $I->sendPOST(CompanyUserAuthRestApiConfig::RESOURCE_COMPANY_USER_ACCESS_TOKENS, [
            'data' => [
                'type' => CompanyUserAuthRestApiConfig::RESOURCE_COMPANY_USER_ACCESS_TOKENS,
                'attributes' => [
                    'idCompanyUser' => $this->fixtures->getNonDefaultCompanyUserTransfer()->getUuid(),
                ],
            ],
        ]);

        $secondCompanyUserAccessToken = current($I->grabDataFromResponseByJsonPath('$.data.attributes.accessToken'));

        //Assert
        $this->assertResponse($I, HttpCode::CREATED);

        $I->assertNotNull($secondCompanyUserAccessToken);
        $I->assertNotEquals($firstCompanyUserAccessToken, $secondCompanyUserAccessToken);
        $I->seeSingleResourceHasSelfLink($I->formatFullUrl(CompanyUserAuthRestApiConfig::RESOURCE_COMPANY_USER_ACCESS_TOKENS));
    }

    /**
     * @param \PyzTest\Glue\CompanyUserAuth\CompanyUserAuthRestApiTester $I
     * @param int $responseCode
     *
     * @return void
     */
    protected function assertResponse(CompanyUserAuthRestApiTester $I, int $responseCode): void
    {
        $I->seeResponseCodeIs($responseCode);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesOpenApiSchema();
    }
}
