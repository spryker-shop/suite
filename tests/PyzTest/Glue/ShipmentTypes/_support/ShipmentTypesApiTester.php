<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Glue\ShipmentTypes;

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
 * @method void pause()
 *
 * @SuppressWarnings(\PyzTest\Glue\ShipmentTypes\PHPMD)
 */
class ShipmentTypesApiTester extends ApiEndToEndTester
{
    use _generated\ShipmentTypesApiTesterActions;

    /**
     * @param list<string> $shipmentTypeKeys
     *
     * @return string
     */
    public function getShipmentTypeKeysSorting(array $shipmentTypeKeys): string
    {
        $firstKey = reset($shipmentTypeKeys);
        $lastKey = end($shipmentTypeKeys);

        if ($firstKey > $lastKey) {
            return 'DESC';
        }

        if ($firstKey < $lastKey) {
            return 'ASC';
        }

        return 'UNORDERED';
    }
}
