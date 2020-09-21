<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Zed\Acl\Communication\Controller;

use PyzTest\Zed\Acl\AclCommunicationTester;

/**
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Zed
 * @group Acl
 * @group Communication
 * @group Controller
 * @group RoleControllerCest
 * Add your own group annotations below this line
 */
class RoleControllerCest
{
    /**
     * @internal This is a backwards compatibility test for camelCasedUrl's. Change URL from `rulesetTable` to `ruleset-table` when `\Spryker\Zed\Router\Communication\Plugin\Router\RouterEnhancer\BackwardsCompatibleUrlRouterEnhancerPlugin` gets removed.
     *
     * @see \Spryker\Zed\Router\Communication\Plugin\Router\RouterEnhancer\BackwardsCompatibleUrlRouterEnhancerPlugin
     *
     * @param \PyzTest\Zed\Acl\AclCommunicationTester $i
     *
     * @return void
     */
    public function rulesetTableCanBeOpenedWithCamelCasedUrl(AclCommunicationTester $i)
    {
        $i->amOnPage('/acl/role/rulesetTable?id-role=1');
        $i->seeResponseCodeIs(200);
    }
}
