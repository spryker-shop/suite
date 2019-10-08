<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Glue\CompanyUserAuth\RestApi;

use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\CompanyTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\OauthResponseTransfer;
use PyzTest\Glue\CompanyUserAuth\CompanyUserAuthRestApiTester;
use SprykerTest\Shared\Testify\Fixtures\FixturesBuilderInterface;
use SprykerTest\Shared\Testify\Fixtures\FixturesContainerInterface;

class CompanyUserAuthAccessTokensRestApiFixtures implements FixturesBuilderInterface, FixturesContainerInterface
{
    public const TEST_PASSWORD = 'Test password';

    /**
     * @var \Generated\Shared\Transfer\OauthResponseTransfer
     */
    protected $oauthResponseTransferForCompanyUser;

    /**
     * @var \Generated\Shared\Transfer\OauthResponseTransfer
     */
    protected $oauthResponseTransferForNonCompanyUser;

    /**
     * @var \Generated\Shared\Transfer\OauthResponseTransfer
     */
    protected $oauthResponseTransferForCustomerWithTwoCompanyUsers;

    /**
     * @var \Generated\Shared\Transfer\CompanyUserTransfer
     */
    protected $nonDefaultCompanyUserTransfer;

    /**
     * @param \PyzTest\Glue\CompanyUserAuth\CompanyUserAuthRestApiTester $I
     *
     * @return \SprykerTest\Shared\Testify\Fixtures\FixturesContainerInterface
     */
    public function buildFixtures(CompanyUserAuthRestApiTester $I): FixturesContainerInterface
    {
        $I->haveCompanyMailConnectorToMailDependency();

        $this->oauthResponseTransferForCompanyUser = $this->createOauthResponseTransferForCompanyUser($I);
        $this->oauthResponseTransferForNonCompanyUser = $this->createOauthResponseTransferForNotCompanyUser($I);
        $this->oauthResponseTransferForCustomerWithTwoCompanyUsers = $this->createOauthResponseTransferForCustomerWithTwoCompanyUsers($I);

        return $this;
    }

    /**
     * @return \Generated\Shared\Transfer\OauthResponseTransfer
     */
    public function getOauthResponseTransferForCompanyUser(): OauthResponseTransfer
    {
        return $this->oauthResponseTransferForCompanyUser;
    }

    /**
     * @return \Generated\Shared\Transfer\OauthResponseTransfer
     */
    public function getOauthResponseTransferForNonCompanyUser(): OauthResponseTransfer
    {
        return $this->oauthResponseTransferForNonCompanyUser;
    }

    /**
     * @return \Generated\Shared\Transfer\OauthResponseTransfer
     */
    public function getOauthResponseTransferForCustomerWithTwoCompanyUsers(): OauthResponseTransfer
    {
        return $this->oauthResponseTransferForCustomerWithTwoCompanyUsers;
    }

    /**
     * @return \Generated\Shared\Transfer\CompanyUserTransfer
     */
    public function getNonDefaultCompanyUserTransfer(): CompanyUserTransfer
    {
        return $this->nonDefaultCompanyUserTransfer;
    }

    /**
     * @param \PyzTest\Glue\CompanyUserAuth\CompanyUserAuthRestApiTester $I
     *
     * @return \Generated\Shared\Transfer\OauthResponseTransfer
     */
    protected function createOauthResponseTransferForCompanyUser(CompanyUserAuthRestApiTester $I): OauthResponseTransfer
    {
        $customerTransfer = $this->createCustomerTransferWithCompanyUser($I);

        return $I->haveAuth($customerTransfer);
    }

    /**
     * @param \PyzTest\Glue\CompanyUserAuth\CompanyUserAuthRestApiTester $I
     *
     * @return \Generated\Shared\Transfer\OauthResponseTransfer
     */
    protected function createOauthResponseTransferForNotCompanyUser(CompanyUserAuthRestApiTester $I): OauthResponseTransfer
    {
        $customerTransfer = $this->createCustomerTransferWithoutCompanyUser($I);

        return $I->haveAuth($customerTransfer);
    }

    /**
     * @param \PyzTest\Glue\CompanyUserAuth\CompanyUserAuthRestApiTester $I
     *
     * @return \Generated\Shared\Transfer\OauthResponseTransfer
     */
    protected function createOauthResponseTransferForCustomerWithTwoCompanyUsers(CompanyUserAuthRestApiTester $I): OauthResponseTransfer
    {
        $customerTransfer = $this->createCustomerTransferWithTwoCompanyUsers($I);

        return $I->haveAuth($customerTransfer);
    }

    /**
     * @param \PyzTest\Glue\CompanyUserAuth\CompanyUserAuthRestApiTester $I
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    protected function createCustomerTransferWithCompanyUser(CompanyUserAuthRestApiTester $I): CustomerTransfer
    {
        $customerTransfer = $I->haveCustomer([
            CustomerTransfer::PASSWORD => static::TEST_PASSWORD,
            CustomerTransfer::NEW_PASSWORD => static::TEST_PASSWORD,
        ]);

        $this->createCompanyUserTransfer($I, $customerTransfer);

        return $customerTransfer;
    }

    /**
     * @param \PyzTest\Glue\CompanyUserAuth\CompanyUserAuthRestApiTester $I
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    protected function createCustomerTransferWithTwoCompanyUsers(CompanyUserAuthRestApiTester $I): CustomerTransfer
    {
        $customerTransfer = $I->haveCustomer([
            CustomerTransfer::PASSWORD => static::TEST_PASSWORD,
            CustomerTransfer::NEW_PASSWORD => static::TEST_PASSWORD,
        ]);

        $this->createCompanyUserTransfer($I, $customerTransfer, [
            CompanyUserTransfer::IS_DEFAULT => true,
        ]);

        $this->nonDefaultCompanyUserTransfer = $this->createCompanyUserTransfer($I, $customerTransfer);

        return $customerTransfer;
    }

    /**
     * @param \PyzTest\Glue\CompanyUserAuth\CompanyUserAuthRestApiTester $I
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    protected function createCustomerTransferWithoutCompanyUser(CompanyUserAuthRestApiTester $I): CustomerTransfer
    {
        return $I->haveCustomer([
            CustomerTransfer::PASSWORD => static::TEST_PASSWORD,
            CustomerTransfer::NEW_PASSWORD => static::TEST_PASSWORD,
        ]);
    }

    /**
     * @param \PyzTest\Glue\CompanyUserAuth\CompanyUserAuthRestApiTester $I
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     * @param array $seed
     *
     * @return \Generated\Shared\Transfer\CompanyUserTransfer
     */
    protected function createCompanyUserTransfer(CompanyUserAuthRestApiTester $I, CustomerTransfer $customerTransfer, array $seed = []): CompanyUserTransfer
    {
        $companyTransfer = $I->haveActiveCompany([
            CompanyTransfer::STATUS => 'approved',
        ]);

        return $I->haveCompanyUser([
            CompanyUserTransfer::CUSTOMER => $customerTransfer,
            CompanyUserTransfer::FK_COMPANY => $companyTransfer->getIdCompany(),
            CompanyUserTransfer::FK_COMPANY_BUSINESS_UNIT => $this->createCompanyBusinessUnit($companyTransfer, $I)->getIdCompanyBusinessUnit(),
        ] + $seed);
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyTransfer $companyTransfer
     * @param \PyzTest\Glue\CompanyUserAuth\CompanyUserAuthRestApiTester $I
     *
     * @return \Generated\Shared\Transfer\CompanyBusinessUnitTransfer
     */
    protected function createCompanyBusinessUnit(CompanyTransfer $companyTransfer, CompanyUserAuthRestApiTester $I): CompanyBusinessUnitTransfer
    {
        return $I->haveCompanyBusinessUnit(
            [
                CompanyBusinessUnitTransfer::NAME => 'test business unit',
                CompanyBusinessUnitTransfer::EMAIL => 'test@spryker.com',
                CompanyBusinessUnitTransfer::PHONE => '1234567890',
                CompanyBusinessUnitTransfer::FK_COMPANY => $companyTransfer->getIdCompany(),
            ]
        );
    }
}
