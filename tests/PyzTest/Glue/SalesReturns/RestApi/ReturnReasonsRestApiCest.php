<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Glue\SalesReturns\RestApi;

use PyzTest\Glue\SalesReturns\RestApi\Fixtures\ReturnReasonsRestApiFixtures;
use PyzTest\Glue\SalesReturns\SalesReturnsApiTester;

/**
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Glue
 * @group SalesReturns
 * @group RestApi
 * @group ReturnReasonsRestApiCest
 * Add your own group annotations below this line
 * @group EndToEnd
 */
class ReturnReasonsRestApiCest
{
    /**
     * @var \PyzTest\Glue\SalesReturns\RestApi\Fixtures\ReturnReasonsRestApiFixtures
     */
    protected $fixtures;

    /**
     * @param \PyzTest\Glue\SalesReturns\SalesReturnsApiTester $I
     *
     * @return void
     */
    public function loadFixtures(SalesReturnsApiTester $I): void
    {
        /** @var \PyzTest\Glue\SalesReturns\RestApi\Fixtures\ReturnReasonsRestApiFixtures $fixtures */
        $fixtures = $I->loadFixtures(ReturnReasonsRestApiFixtures::class);

        $this->fixtures = $fixtures;
    }
}
