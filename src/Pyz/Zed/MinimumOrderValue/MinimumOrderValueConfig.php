<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\MinimumOrderValue;

use Spryker\Zed\MinimumOrderValue\Business\Strategy\HardThresholdStrategy;
use Spryker\Zed\MinimumOrderValue\Business\Strategy\SoftThresholdWithFixedFeeStrategy;
use Spryker\Zed\MinimumOrderValue\Business\Strategy\SoftThresholdWithFlexibleFeeStrategy;
use Spryker\Zed\MinimumOrderValue\Business\Strategy\SoftThresholdWithMessageStrategy;
use Spryker\Zed\MinimumOrderValue\MinimumOrderValueConfig as SprykerMinimumOrderValueConfig;

class MinimumOrderValueConfig extends SprykerMinimumOrderValueConfig
{
    /**
     * @return \Spryker\Zed\MinimumOrderValue\Business\Strategy\MinimumOrderValueStrategyInterface[]
     */
    public function getMinimumOrderValueStrategies(): array
    {
        return [
            new HardThresholdStrategy(),
            new SoftThresholdWithMessageStrategy(),
            new SoftThresholdWithFixedFeeStrategy(),
            new SoftThresholdWithFlexibleFeeStrategy(),
        ];
    }
}
