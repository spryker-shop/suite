<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Propel\Business\Cte;

interface CteSupportCheckerInterface
{
    /**
     * @return bool
     */
    public function checkCteSupport(): bool;
}
