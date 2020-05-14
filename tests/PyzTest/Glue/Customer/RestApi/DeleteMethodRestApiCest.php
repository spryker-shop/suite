<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Glue\Customer\RestApi;

use Codeception\Util\HttpCode;
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
     * @param \PyzTest\Glue\Carts\CartsApiTester $i
     *
     * @return void
     */
    public function _before(CartsApiTester $i): void
    {
        /** @var \PyzTest\Glue\Customer\RestApi\CustomerRestApiFixtures $fixtures */
        $fixtures = $i->loadFixtures(CustomerRestApiFixtures::class);

        $this->fixtures = $fixtures;
    }

    /**
     * @param \PyzTest\Glue\Customer\CustomerApiTester $i
     *
     * When SymfonyListener is not enabled Glue returns 204 with content inside, this test is to check it doesn't happen
     * Jira ticket GLUE-9691
     *
     * @return void
     */
    public function ensureDeleteRequestHasNoBody(CustomerApiTester $i): void
    {
        $token = $i->haveAuthorizationToGlue(
            $this->fixtures->getCustomerTransfer()
        )->getAccessToken();

        $headers = [
            'Accept: */*',
            'Authorization: Bearer ' . $token,
            'Cache-Control: no-cache',
            'Content-Type: application/json',
        ];

        $url = $i->formatFullUrl(
            'customers/{CustomerReference}',
            ['CustomerReference' => $this->fixtures->getCustomerTransfer()->getCustomerReference()]
        );
        $result = file_get_contents(
            $url,
            false,
            stream_context_create(
                [
                    'http' => [
                        'method' => 'DELETE',
                        'header' => implode("\r\n", $headers),
                    ],
                ]
            )
        );
        $responseCode = substr($http_response_header[0], 9, 3);

        $i->assertEquals(HttpCode::NO_CONTENT, $responseCode);
        $i->assertSame('', $result, 'Content in 204 response');
    }
}
