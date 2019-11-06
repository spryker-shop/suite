<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Glue\Customer\RestApi;

use PyzTest\Glue\Carts\CartsApiTester;
use PyzTest\Glue\Customer\CustomerApiTester;

/**
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Glue
 * @group Customer
 * @group RestApi
 * @group DeleteMethodRestApiCest
 * Add your own group annotations below this line
 * @group EndToEnd
 */
class DeleteMethodRestApiCest
{
    /**
     * @var \PyzTest\Glue\Customer\RestApi\CustomerRestApiFixtures
     */
    protected $fixtures;

    /**
     * @param \PyzTest\Glue\Carts\CartsApiTester $I
     *
     * @return void
     */
    public function _before(CartsApiTester $I): void
    {
        $this->fixtures = $I->loadFixtures(CustomerRestApiFixtures::class);
    }

    /**
     * @param \PyzTest\Glue\Customer\CustomerApiTester $I
     *
     * When SymfonyListener is not enabled Glue returns 204 with content inside, this test is to check it doesn't happen
     * Jira ticket GLUE-9691
     *
     * @return void
     */
    public function ensureDeleteRequestHasNoBody(CustomerApiTester $I): void
    {
        $token = $I->haveAuthorizationToGlue(
            $this->fixtures->getCustomerTransfer()
        )->getAccessToken();

        $headers = [
            "Accept: */*",
            "Authorization: Bearer " . $token,
            "Cache-Control: no-cache",
            "Content-Type: application/json",
        ];

        /** @var \SprykerTest\Glue\Testify\Helper\GlueRest $glueModule */
        $glueModule = $I->findModule('\SprykerTest\Glue\Testify\Helper\GlueRest');
        $url = sprintf('%s/customers/%s', $glueModule->_getConfig('url'), $this->fixtures->getCustomerTransfer()->getCustomerReference());
        $result = file_get_contents(
            $url,
            false,
            stream_context_create(
                [
                    'http' => [
                        'method' => 'DELETE',
                        "header" => implode("\r\n", $headers),
                    ],
                ]
            )
        );
        $I->assertSame('', $result);
    }
}
