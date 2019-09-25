<?php

namespace PyzTest\Glue\Auth\RestApi;

use Generated\Shared\Transfer\CustomerTransfer;
use PyzTest\Glue\Auth\AuthRestApiTester;
use SprykerTest\Shared\Testify\Fixtures\FixturesBuilderInterface;
use SprykerTest\Shared\Testify\Fixtures\FixturesContainerInterface;

class AccessTokensApiFixtures implements FixturesBuilderInterface, FixturesContainerInterface
{
    public const TEST_PASSWORD = 'Test password';

    /**
     * @var \Generated\Shared\Transfer\CustomerTransfer
     */
    protected $customerTransfer;

    /**i
     * @param AuthRestApiTester $I
     *
     * @return FixturesContainerInterface
     */
    public function buildFixtures(AuthRestApiTester $I): FixturesContainerInterface
    {
        $this->customerTransfer = $this->createCustomerTransfer($I);

        return $this;
    }

    /**
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function getCustomerTransfer(): CustomerTransfer
    {
        return $this->customerTransfer;
    }

    /**
     * @param \PyzTest\Glue\Auth\AuthRestApiTester $I
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    protected function createCustomerTransfer(AuthRestApiTester $I): CustomerTransfer
    {
        return $I->haveCustomer([
            'password' => static::TEST_PASSWORD,
            'newPassword' => static::TEST_PASSWORD,
        ]);
    }
}
