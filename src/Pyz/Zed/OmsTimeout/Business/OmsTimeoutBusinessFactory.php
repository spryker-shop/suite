<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\OmsTimeout\Business;

use Pyz\Zed\OmsTimeout\Business\Calculator\Timeout\InitiationTimeoutCalculator;
use Pyz\Zed\OmsTimeout\Business\Calculator\Timeout\TimeoutCalculatorInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

class OmsTimeoutBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Pyz\Zed\OmsTimeout\Business\Calculator\Timeout\TimeoutCalculatorInterface
     */
    public function createInitiationTimeoutCalculator(): TimeoutCalculatorInterface
    {
        return new InitiationTimeoutCalculator();
    }
}
