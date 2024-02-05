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
use Spryker\Glue\WarehouseUsersBackendApi\WarehouseUsersBackendApiConfig;

/**
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Glue
 * @group OauthUserConnector
 * @group BackendApi
 * @group MerchantUserScopeAuthorizeBackendApiCest
 * Add your own group annotations below this line
 */
class MerchantUserScopeAuthorizeBackendApiCest
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
    public function requestServicePointsForMerchantUserForbidden(OauthUserConnectorBackendApiTester $I): void
    {
        $backendOauthResponseTransfer = $I->havePasswordAuthorizationToBackendApi($this->fixtures->getMerchantUserTransfer());
        $I->amBearerAuthenticated($backendOauthResponseTransfer->getAccessToken());

        //Act
        $I->sendJsonApiGet(ServicePointsBackendApiConfig::RESOURCE_SERVICE_POINTS);

        //Assert
        $I->seeResponseCodeIs(HttpCode::FORBIDDEN);
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\OauthUserConnector\OauthUserConnectorBackendApiTester $I
     *
     * @return void
     */
    public function requestWarehouseUserAssignmentsForMerchantUserForbidden(OauthUserConnectorBackendApiTester $I): void
    {
        $backendOauthResponseTransfer = $I->havePasswordAuthorizationToBackendApi($this->fixtures->getMerchantUserTransfer());
        $I->amBearerAuthenticated($backendOauthResponseTransfer->getAccessToken());

        //Act
        $I->sendJsonApiGet(WarehouseUsersBackendApiConfig::RESOURCE_TYPE_WAREHOUSE_USER_ASSIGNMENTS);

        //Assert
        $I->seeResponseCodeIs(HttpCode::FORBIDDEN);
    }
}
