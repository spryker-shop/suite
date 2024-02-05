<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Glue\OauthUserConnector\BackendApi;

use Codeception\Util\HttpCode;
use PyzTest\Glue\OauthUserConnector\BackendApi\Fixtures\OauthUserConnectorBackendApiFixtures;
use PyzTest\Glue\OauthUserConnector\OauthUserConnectorBackendApiTester;
use Spryker\Glue\ServicePointsBackendApi\ServicePointsBackendApiConfig;

/**
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Glue
 * @group OauthUserConnector
 * @group BackendApi
 * @group BackofficeUserScopeAuthorizeBackendApiCest
 * Add your own group annotations below this line
 */
class BackofficeUserScopeAuthorizeBackendApiCest
{
    /**
     * @var \PyzTest\Glue\OauthUserConnector\BackendApi\Fixtures\OauthUserConnectorBackendApiFixtures
     */
    protected OauthUserConnectorBackendApiFixtures $fixtures;

    /**
     * @param \PyzTest\Glue\OauthUserConnector\OauthUserConnectorBackendApiTester $I
     *
     * @return void
     */
    public function loadFixtures(OauthUserConnectorBackendApiTester $I): void
    {
        /** @var \PyzTest\Glue\OauthUserConnector\BackendApi\Fixtures\OauthUserConnectorBackendApiFixtures $fixtures */
        $fixtures = $I->loadFixtures(OauthUserConnectorBackendApiFixtures::class);

        $this->fixtures = $fixtures;
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\OauthUserConnector\OauthUserConnectorBackendApiTester $I
     *
     * @return void
     */
    public function requestServicePointsForBackofficeUserAllowed(OauthUserConnectorBackendApiTester $I): void
    {
        $backendOauthResponseTransfer = $I->havePasswordAuthorizationToBackendApi($this->fixtures->getBackofficeUserTransfer());
        $I->amBearerAuthenticated($backendOauthResponseTransfer->getAccessToken());

        //Act
        $I->sendJsonApiGet(
            $I->formatFullUrl(ServicePointsBackendApiConfig::RESOURCE_SERVICE_POINTS),
        );

        //Assert
        $I->seeResponseCodeIs(HttpCode::OK);
    }
}
