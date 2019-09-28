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
     * @var \Generated\Shared\Transfer\CustomerTransfer
     */
    protected $customerTransferWithCompanyUser;

    /**
     * @var \Generated\Shared\Transfer\CustomerTransfer
     */
    protected $anotherCustomerTransferWithoutCompanyUser;

    /**
     * @var \Generated\Shared\Transfer\CompanyUserTransfer
     */
    protected $companyUserTransfer;

    /**
     * @param \PyzTest\Glue\CompanyUserAuth\CompanyUserAuthRestApiTester $I
     *
     * @return \SprykerTest\Shared\Testify\Fixtures\FixturesContainerInterface
     */
    public function buildFixtures(CompanyUserAuthRestApiTester $I): FixturesContainerInterface
    {
        $this->customerTransferWithCompanyUser = $this->createCustomerTransfer($I);
        $this->anotherCustomerTransferWithoutCompanyUser = $this->createCustomerTransfer($I);
        $this->companyUserTransfer = $this->createCompanyUserTransfer($I);
        $this->oauthResponseTransferForCompanyUser = $this->createOauthResponseTransfer($I, $this->customerTransferWithCompanyUser);
        $this->oauthResponseTransferForNonCompanyUser = $this->createOauthResponseTransfer($I, $this->anotherCustomerTransferWithoutCompanyUser);

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
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function getCustomerTransferWithCompanyUser(): CustomerTransfer
    {
        return $this->customerTransferWithCompanyUser;
    }

    /**
     * @return \Generated\Shared\Transfer\CompanyUserTransfer
     */
    public function getCompanyUserTransfer(): CompanyUserTransfer
    {
        return $this->companyUserTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function getAnotherCustomerTransferWithoutCompanyUser(): CustomerTransfer
    {
        return $this->anotherCustomerTransferWithoutCompanyUser;
    }

    /**
     * @param \PyzTest\Glue\CompanyUserAuth\CompanyUserAuthRestApiTester $I
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\OauthResponseTransfer
     */
    protected function createOauthResponseTransfer(CompanyUserAuthRestApiTester $I, CustomerTransfer $customerTransfer): OauthResponseTransfer
    {
        return $I->haveAuth($customerTransfer);
    }

    /**
     * @param \PyzTest\Glue\CompanyUserAuth\CompanyUserAuthRestApiTester $I
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    protected function createCustomerTransfer(CompanyUserAuthRestApiTester $I): CustomerTransfer
    {
        return $I->haveCustomer([
            'password' => static::TEST_PASSWORD,
            'newPassword' => static::TEST_PASSWORD,
        ]);
    }

    /**
     * @param \PyzTest\Glue\CompanyUserAuth\CompanyUserAuthRestApiTester $I
     *
     * @return \Generated\Shared\Transfer\CompanyUserTransfer
     */
    protected function createCompanyUserTransfer(CompanyUserAuthRestApiTester $I): CompanyUserTransfer
    {
        $companyTransfer = $I->haveCompany([
            CompanyTransfer::IS_ACTIVE => true,
            CompanyTransfer::STATUS => 'approved',
        ]);

        return $I->haveCompanyUser([
            CompanyUserTransfer::CUSTOMER => $this->customerTransferWithCompanyUser,
            CompanyUserTransfer::FK_COMPANY => $companyTransfer->getIdCompany(),
            CompanyUserTransfer::FK_COMPANY_BUSINESS_UNIT => $this->createCompanyBusinessUnit($companyTransfer, $I)->getIdCompanyBusinessUnit(),
        ]);
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyTransfer $companyTransfer
     * @param \PyzTest\Glue\CompanyUserAuth\CompanyUserAuthRestApiTester $I
     *
     * @return \Generated\Shared\Transfer\CompanyBusinessUnitTransfer
     */
    public function createCompanyBusinessUnit(CompanyTransfer $companyTransfer, CompanyUserAuthRestApiTester $I): CompanyBusinessUnitTransfer
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
