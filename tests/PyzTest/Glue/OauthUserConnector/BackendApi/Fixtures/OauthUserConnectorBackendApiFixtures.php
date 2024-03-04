<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Glue\OauthUserConnector\BackendApi\Fixtures;

use Generated\Shared\Transfer\MerchantProfileTransfer;
use Generated\Shared\Transfer\MerchantTransfer;
use Generated\Shared\Transfer\UserTransfer;
use PyzTest\Glue\OauthUserConnector\OauthUserConnectorBackendApiTester;
use SprykerTest\Shared\Testify\Fixtures\FixturesBuilderInterface;
use SprykerTest\Shared\Testify\Fixtures\FixturesContainerInterface;

class OauthUserConnectorBackendApiFixtures implements FixturesBuilderInterface, FixturesContainerInterface
{
    /**
     * @var string
     */
    protected const TEST_PASSWORD = 'change123';

    /**
     * @var string
     */
    protected const MERCHANT_STATUS_APPROVED = 'approved';

    /**
     * @var \Generated\Shared\Transfer\UserTransfer
     */
    protected $backofficeUserTransfer;

    /**
     * @var \Generated\Shared\Transfer\UserTransfer
     */
    protected $merchantUserTransfer;

    /**
     * @var \Generated\Shared\Transfer\UserTransfer
     */
    protected $warehouseUserTransfer;

    /**
     * @param \PyzTest\Glue\OauthUserConnector\OauthUserConnectorBackendApiTester $I
     *
     * @return \SprykerTest\Shared\Testify\Fixtures\FixturesContainerInterface
     */
    public function buildFixtures(OauthUserConnectorBackendApiTester $I): FixturesContainerInterface
    {
        $this->merchantUserTransfer = $this->createMerchantUserTransfer($I);
        $this->backofficeUserTransfer = $this->createBackofficeUserTransfer($I);
        $this->warehouseUserTransfer = $this->createWarehouseUserTransfer($I);

        return $this;
    }

    /**
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    public function getBackofficeUserTransfer(): UserTransfer
    {
        return $this->backofficeUserTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    public function getMerchantUserTransfer(): UserTransfer
    {
        return $this->merchantUserTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    public function getWarehouseUserTransfer(): UserTransfer
    {
        return $this->warehouseUserTransfer;
    }

    /**
     * @param \PyzTest\Glue\OauthUserConnector\OauthUserConnectorBackendApiTester $I
     *
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    protected function createBackofficeUserTransfer(OauthUserConnectorBackendApiTester $I): UserTransfer
    {
        $userTransfer = $I->haveUser([
            UserTransfer::PASSWORD => static::TEST_PASSWORD,
        ]);

        return $userTransfer->setPassword(static::TEST_PASSWORD);
    }

    /**
     * @param \PyzTest\Glue\OauthUserConnector\OauthUserConnectorBackendApiTester $I
     *
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    protected function createMerchantUserTransfer(OauthUserConnectorBackendApiTester $I): UserTransfer
    {
        $userTransfer = $I->haveUser([
            UserTransfer::PASSWORD => static::TEST_PASSWORD,
        ]);

        $merchantTransfer = $I->haveMerchant([
            MerchantTransfer::IS_ACTIVE => true,
            MerchantTransfer::STATUS => static::MERCHANT_STATUS_APPROVED,
            MerchantTransfer::MERCHANT_PROFILE => new MerchantProfileTransfer(),
        ]);
        $I->haveMerchantUser($merchantTransfer, $userTransfer);

        return $userTransfer->setPassword(static::TEST_PASSWORD);
    }

    /**
     * @param \PyzTest\Glue\OauthUserConnector\OauthUserConnectorBackendApiTester $I
     *
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    protected function createWarehouseUserTransfer(OauthUserConnectorBackendApiTester $I): UserTransfer
    {
        $userTransfer = $I->haveUser([
            UserTransfer::IS_WAREHOUSE_USER => true,
            UserTransfer::PASSWORD => static::TEST_PASSWORD,
        ]);

        $stockTransfer = $I->haveStock();
        $I->haveWarehouseUserAssignment($userTransfer, $stockTransfer);

        return $userTransfer->setPassword(static::TEST_PASSWORD);
    }
}
