<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Glue\Customer\RestApi;

use Generated\Shared\Transfer\CustomerTransfer;
use PyzTest\Glue\Customer\CustomerApiTester;
use SprykerTest\Shared\Testify\Fixtures\FixturesBuilderInterface;
use SprykerTest\Shared\Testify\Fixtures\FixturesContainerInterface;

/**
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Glue
 * @group Customer
 * @group RestApi
 * @group CustomerRestApiFixtures
 * Add your own group annotations below this line
 * @group EndToEnd
 */
class CustomerRestApiFixtures implements FixturesBuilderInterface, FixturesContainerInterface
{
    protected const TEST_PASSWORD = 'test password';

    /**
     * @var \Generated\Shared\Transfer\CustomerTransfer
     */
    protected $customerTransfer;

    /**
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function getCustomerTransfer(): CustomerTransfer
    {
        return $this->customerTransfer;
    }

    /**
     * @param \PyzTest\Glue\Customer\CustomerApiTester $I
     *
     * @return \SprykerTest\Shared\Testify\Fixtures\FixturesContainerInterface
     */
    public function buildFixtures(CustomerApiTester $I): FixturesContainerInterface
    {
        $this->createCustomer($I);

        return $this;
    }

    /**
     * @param \PyzTest\Glue\Customer\CustomerApiTester $I
     *
     * @return void
     */
    protected function createCustomer(CustomerApiTester $I): void
    {
        $customerTransfer = $I->haveCustomer([
            CustomerTransfer::PASSWORD => static::TEST_PASSWORD,
        ]);

        $this->customerTransfer = $customerTransfer;
    }
}
