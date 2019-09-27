<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Glue\Auth\RestApi;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\OauthResponseTransfer;
use PyzTest\Glue\Auth\AuthRestApiTester;
use SprykerTest\Shared\Testify\Fixtures\FixturesBuilderInterface;
use SprykerTest\Shared\Testify\Fixtures\FixturesContainerInterface;

class RefreshTokensRestApiFixtures implements FixturesBuilderInterface, FixturesContainerInterface
{
    public const TEST_PASSWORD = 'Test password';

    /**
     * @var \Generated\Shared\Transfer\OauthResponseTransfer
     */
    protected $oauthResponseTransfer;

    /**
     * @var \Generated\Shared\Transfer\CustomerTransfer
     */
    protected $customerTransfer;

    /**
     * @param \PyzTest\Glue\Auth\AuthRestApiTester $I
     *
     * @return \SprykerTest\Shared\Testify\Fixtures\FixturesContainerInterface
     */
    public function buildFixtures(AuthRestApiTester $I): FixturesContainerInterface
    {
        $this->customerTransfer = $this->createCustomerTransfer($I);
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
    public function getCustomerTransfer(): CustomerTransfer
    {
        return $this->customerTransfer;
    }

    /**
     * @param \PyzTest\Glue\Auth\AuthRestApiTester $I
     *
     * @return \Generated\Shared\Transfer\OauthResponseTransfer
     */
    protected function createOauthResponseTransfer(AuthRestApiTester $I): OauthResponseTransfer
    {
        return $I->haveAuth($this->customerTransfer);
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
