<?php

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
    protected $oauthResponseTransfer;

    /**
     * @var \Generated\Shared\Transfer\CustomerTransfer
     */
    protected $customerTransferWithCompanyUser;

    /**
     * @var \Generated\Shared\Transfer\CustomerTransfer
     */
    protected $customerTransferWithoutCompanyUser;

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
        $this->customerTransferWithoutCompanyUser = $this->createCustomerTransfer($I);
        $this->companyUserTransfer = $this->createCompanyUserTransfer($I);
        $this->oauthResponseTransfer = $this->createOauthResponseTransfer($I);

        return $this;
    }

    /**
     * @return \Generated\Shared\Transfer\OauthResponseTransfer
     */
    public function getOauthResponseTransfer(): OauthResponseTransfer
    {
        return $this->oauthResponseTransfer;
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
     * @return CustomerTransfer
     */
    public function getCustomerTransferWithoutCompanyUser(): CustomerTransfer
    {
        return $this->customerTransferWithoutCompanyUser;
    }

    /**
     * @param \PyzTest\Glue\CompanyUserAuth\CompanyUserAuthRestApiTester $I
     *
     * @return \Generated\Shared\Transfer\OauthResponseTransfer
     */
    protected function createOauthResponseTransfer(CompanyUserAuthRestApiTester $I): OauthResponseTransfer
    {
        return $I->haveAuth($this->customerTransferWithCompanyUser);
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
     * @param CompanyUserAuthRestApiTester $I
     *
     * @return \Generated\Shared\Transfer\CompanyUserTransfer
     */
    protected function createCompanyUserTransfer(CompanyUserAuthRestApiTester $I): CompanyUserTransfer
    {
        $companyTransfer = $I->haveActiveCompany();

        return $I->haveCompanyUser([
            CompanyUserTransfer::CUSTOMER => $this->customerTransferWithCompanyUser,
            CompanyUserTransfer::FK_COMPANY => $companyTransfer->getIdCompany(),
            CompanyUserTransfer::FK_COMPANY_BUSINESS_UNIT => $this->createCompanyBusinessUnit($companyTransfer, $I)->getIdCompanyBusinessUnit(),
        ]);
    }

    /**
     * @param CompanyTransfer $companyTransfer
     * @param CompanyUserAuthRestApiTester $I
     *
     * @return \Generated\Shared\Transfer\CompanyBusinessUnitTransfer
     */
    public function createCompanyBusinessUnit(CompanyTransfer $companyTransfer, CompanyUserAuthRestApiTester  $I): CompanyBusinessUnitTransfer
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
