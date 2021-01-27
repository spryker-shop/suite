<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Propel\Business;

use Pyz\Zed\Propel\Business\CTE\CteSupportChecker;
use Pyz\Zed\Propel\Business\CTE\CteSupportCheckerInterface;
use Spryker\Zed\Propel\Business\PropelBusinessFactory as SprykerPropelBusinessFactory;

/**
 * @method \Pyz\Zed\Propel\PropelConfig getConfig()
 */
class PropelBusinessFactory extends SprykerPropelBusinessFactory
{
    /**
     * @return \Pyz\Zed\Propel\Business\CTE\CteSupportCheckerInterface
     */
    public function createCteSupportChecker(): CteSupportCheckerInterface
    {
        return new CteSupportChecker($this->getConfig());
    }
}
