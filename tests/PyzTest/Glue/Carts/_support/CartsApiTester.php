<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Glue\Carts;

use SprykerTest\Glue\Testify\Tester\ApiEndToEndTester;

/**
 * Inherited Methods
 *
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = null)
 *
 * @SuppressWarnings(PHPMD)
 */
class CartsApiTester extends ApiEndToEndTester
{
    use _generated\CartsApiTesterActions;

    /**
     * @param int $quantity
     * @param string $resourceName
     * @param string $itemSku
     *
     * @return void
     */
    public function seeCartItemQuantityEqualsToQuantityInRequest(int $quantity, string $resourceName, string $itemSku): void
    {
        $includedByTypeAndId = $this->grabIncludedByTypeAndId($resourceName, $itemSku);

        $this->assertArrayHasKey('quantity', $includedByTypeAndId);
        $this->assertEquals($quantity, $includedByTypeAndId['quantity']);
    }
}
