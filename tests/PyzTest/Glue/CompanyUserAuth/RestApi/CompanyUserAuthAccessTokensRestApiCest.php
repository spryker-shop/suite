<?php

namespace PyzTest\Glue\CompanyUserAuth\RestApi;

use Codeception\Util\HttpCode;
use PyzTest\Glue\CompanyUserAuth\CompanyUserAuthRestApiTester;
use Spryker\Glue\AuthRestApi\AuthRestApiConfig;

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
     * @param CompanyUserAuthRestApiTester $I
     *
     * @return void
     */
    public function loadFixtures(CompanyUserAuthRestApiTester $I): void
    {
        $this->fixtures = $I->loadFixtures(CompanyUserAuthAccessTokensRestApiFixtures::class);
    }

    /**
     * @param CompanyUserAuthRestApiTester $I
     *
     * @return void
     */
    public function requestAccessTokenForExistingCustomerWithoutCompanyUser(CompanyUserAuthRestApiTester $I): void
    {
        $I->sendPOST(AuthRestApiConfig::RESOURCE_ACCESS_TOKENS, [
            'data' => [
                'type' => AuthRestApiConfig::RESOURCE_ACCESS_TOKENS,
                'attributes' => [
                    'username' => $this->fixtures->getCustomerTransferWithoutCompanyUser()->getEmail(),
                    'password' => CompanyUserAuthAccessTokensRestApiFixtures::TEST_PASSWORD,
                ],
            ]
        ]);

        $this->assertResponse($I, HttpCode::CREATED);
        $I->assertNull(current($I->grabDataFromResponseByJsonPath('$.data.attributes.idCompanyUser')));
    }

    /**
     * @param CompanyUserAuthRestApiTester $I
     *
     * @return void
     */
    public function requestAccessTokenForExistingCustomerWithCompanyUser(CompanyUserAuthRestApiTester $I): void
    {
        $I->sendPOST(AuthRestApiConfig::RESOURCE_ACCESS_TOKENS, [
            'data' => [
                'type' => AuthRestApiConfig::RESOURCE_ACCESS_TOKENS,
                'attributes' => [
                    'username' => $this->fixtures->getCustomerTransferWithCompanyUser()->getEmail(),
                    'password' => CompanyUserAuthAccessTokensRestApiFixtures::TEST_PASSWORD,
                ],
            ]
        ]);

        $this->assertResponse($I, HttpCode::CREATED);
        $I->assertNotNull(current($I->grabDataFromResponseByJsonPath('$.data.attributes.idCompanyUser')));
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
