<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Glue\Auth\RestApi;

use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\CompanyTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use PyzTest\Glue\Auth\AuthRestApiTester;
use SprykerTest\Shared\Testify\Fixtures\FixturesBuilderInterface;
use SprykerTest\Shared\Testify\Fixtures\FixturesContainerInterface;

class AccessTokensForCompanyUserRestApiFixtures implements FixturesBuilderInterface, FixturesContainerInterface
{
    public const TEST_PASSWORD = 'Test password';

    /**
     * @var \Generated\Shared\Transfer\CustomerTransfer
     */
    protected $customerTransferWithCompanyUser;

    /**
     * @var \Generated\Shared\Transfer\CustomerTransfer
     */
    protected $customerTransferWithoutCompanyUser;

    /**
     * @var \Generated\Shared\Transfer\CustomerTransfer
     */
    protected $customerTransferWithTwoCompanyUsersWithoutDefaultOne;

    /**
     * @var \Generated\Shared\Transfer\CustomerTransfer
     */
    protected $customerTransferWithTwoCompanyUsersWithDefaultOne;

    /**
     * @param \PyzTest\Glue\Auth\AuthRestApiTester $I
     *
     * @return \SprykerTest\Shared\Testify\Fixtures\FixturesContainerInterface
     */
    public function buildFixtures(AuthRestApiTester $I): FixturesContainerInterface
    {
        $I->haveCompanyMailConnectorToMailDependency();

        $this->customerTransferWithCompanyUser = $this->createCompanyUserTransfer($I, $this->createCustomerTransfer($I))->getCustomer();
        $this->customerTransferWithoutCompanyUser = $this->createCustomerTransfer($I);
        $this->customerTransferWithTwoCompanyUsersWithoutDefaultOne = $this->createCompanyUserTransfer($I, $this->createCustomerTransfer($I))->getCustomer();
        $this->customerTransferWithTwoCompanyUsersWithDefaultOne = $this->createCompanyUserTransfer($I, $this->createCustomerTransfer($I))->getCustomer();

        $this->createCompanyUserTransfer($I, $this->customerTransferWithTwoCompanyUsersWithoutDefaultOne);
        $this->createCompanyUserTransfer($I, $this->customerTransferWithTwoCompanyUsersWithDefaultOne, [
            CompanyUserTransfer::IS_DEFAULT => true,
        ]);

        return $this;
    }

    /**
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function getCustomerTransferWithCompanyUser(): CustomerTransfer
    {
        return $this->customerTransferWithCompanyUser;
    }

    /**
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function getCustomerTransferWithoutCompanyUser(): CustomerTransfer
    {
        return $this->customerTransferWithoutCompanyUser;
    }

    /**
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function getCustomerTransferWithTwoCompanyUsersWithoutDefaultOne(): CustomerTransfer
    {
        return $this->customerTransferWithTwoCompanyUsersWithoutDefaultOne;
    }

    /**
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function getCustomerTransferWithTwoCompanyUsersWithDefaultOne(): CustomerTransfer
    {
        return $this->customerTransferWithTwoCompanyUsersWithDefaultOne;
    }

    /**
     * @param \PyzTest\Glue\Auth\AuthRestApiTester $I
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    protected function createCustomerTransfer(AuthRestApiTester $I): CustomerTransfer
    {
        return $I->haveCustomer([
            CustomerTransfer::PASSWORD => static::TEST_PASSWORD,
            CustomerTransfer::NEW_PASSWORD => static::TEST_PASSWORD,
        ]);
    }

    /**
     * @param \PyzTest\Glue\Auth\AuthRestApiTester $I
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     * @param array $seed
     *
     * @return \Generated\Shared\Transfer\CompanyUserTransfer
     */
    protected function createCompanyUserTransfer(AuthRestApiTester $I, CustomerTransfer $customerTransfer, array $seed = []): CompanyUserTransfer
    {
        $companyTransfer = $I->haveCompany([
            CompanyTransfer::IS_ACTIVE => true,
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
     * @param \PyzTest\Glue\Auth\AuthRestApiTester $I
     *
     * @return \Generated\Shared\Transfer\CompanyBusinessUnitTransfer
     */
    public function createCompanyBusinessUnit(CompanyTransfer $companyTransfer, AuthRestApiTester $I): CompanyBusinessUnitTransfer
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
