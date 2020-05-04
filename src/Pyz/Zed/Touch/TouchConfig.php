<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Touch;

use Spryker\Zed\Touch\TouchConfig as SprykerTouchConfig;

class TouchConfig extends SprykerTouchConfig
{
    /**
     * @api
     *
     * @return bool
     */
    public function isTouchOperationsEnabled(): bool
    {
        return false;
    }
}
