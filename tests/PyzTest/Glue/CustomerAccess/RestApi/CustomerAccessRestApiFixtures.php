<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Glue\CustomerAccess\RestApi;

use PyzTest\Glue\CustomerAccess\CustomerAccessApiTester;
use SprykerTest\Shared\Testify\Fixtures\FixturesBuilderInterface;
use SprykerTest\Shared\Testify\Fixtures\FixturesContainerInterface;

/**
 * Auto-generated group annotations
 * @group PyzTest
 * @group Glue
 * @group CustomerAccess
 * @group RestApi
 * @group CustomerAccessRestFixtures
 * Add your own group annotations below this line
 * @group EndToEnd
 */
class CustomerAccessRestApiFixtures implements FixturesBuilderInterface, FixturesContainerInterface
{
    protected const TEST_REST_RESOURCE_RESTRICTED = 'rest-resource-1';
    protected const TEST_REST_RESOURCE_NOT_RESTRICTED = 'rest-resource-2';

    protected const TEST_CUSTOMER_ACCESS_RESOURCE_RESTRICTED = 'test content 1';
    protected const TEST_CUSTOMER_ACCESS_RESOURCE_NOT_RESTRICTED = 'test content 2';

    /**
     * @var string[][]
     */
    protected $customerAccessContentTypeResourceType;

    /**
     * @return string[][]
     */
    public function getCustomerAccessContentTypeResourceType(): array
    {
        return $this->customerAccessContentTypeResourceType;
    }

    /**
     * @param \PyzTest\Glue\CustomerAccess\CustomerAccessApiTester $I
     *
     * @return \SprykerTest\Shared\Testify\Fixtures\FixturesContainerInterface
     */
    public function buildFixtures(CustomerAccessApiTester $I): FixturesContainerInterface
    {
        $this->createCustomerAccessContentTypeResourceType($I);

        return $this;
    }

    /**
     * @param \PyzTest\Glue\CustomerAccess\CustomerAccessApiTester $I
     *
     * @return void
     */
    protected function createCustomerAccessContentTypeResourceType(CustomerAccessApiTester $I): void
    {
        $I->haveCustomerAccess();

        $this->customerAccessContentTypeResourceType = [
            static::TEST_CUSTOMER_ACCESS_RESOURCE_RESTRICTED => [
                static::TEST_REST_RESOURCE_RESTRICTED,
            ],
            static::TEST_CUSTOMER_ACCESS_RESOURCE_NOT_RESTRICTED => [
                static::TEST_REST_RESOURCE_NOT_RESTRICTED,
            ],
        ];
    }
}
