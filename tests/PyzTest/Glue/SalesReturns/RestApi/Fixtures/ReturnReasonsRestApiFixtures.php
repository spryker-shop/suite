<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Glue\SalesReturns\RestApi\Fixtures;

use PyzTest\Glue\SalesReturns\SalesReturnsApiTester;
use SprykerTest\Shared\Testify\Fixtures\FixturesBuilderInterface;
use SprykerTest\Shared\Testify\Fixtures\FixturesContainerInterface;

/**
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Glue
 * @group SalesReturns
 * @group RestApi
 * @group ReturnReasonsRestApiFixtures
 * Add your own group annotations below this line
 * @group EndToEnd
 */
class ReturnReasonsRestApiFixtures implements FixturesBuilderInterface, FixturesContainerInterface
{
    /**
     * @param \PyzTest\Glue\SalesReturns\SalesReturnsApiTester $I
     *
     * @return \SprykerTest\Shared\Testify\Fixtures\FixturesContainerInterface
     */
    public function buildFixtures(SalesReturnsApiTester $I): FixturesContainerInterface
    {
        $I->haveReturnReasons([
            'return.return_reasons.damaged.name',
            'return.return_reasons.wrong-item.name',
            'return.return_reasons.no_longer_needed.name',
        ]);

        return $this;
    }
}
