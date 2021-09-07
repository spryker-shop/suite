<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\SalesReturn;

use Spryker\Zed\SalesReturn\SalesReturnConfig as SprykerSalesReturnConfig;

class SalesReturnConfig extends SprykerSalesReturnConfig
{
    /**
     * @var array
     */
    protected const RETURNABLE_STATE_NAMES = [
        'shipped',
        'delivered',
        'shipped by merchant',
    ];
}
