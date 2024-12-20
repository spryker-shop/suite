<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Pyz\Shared\MerchantSwitcher;

use Spryker\Shared\MerchantSwitcher\MerchantSwitcherConfig as SprykerMerchantSwitcherConfig;

class MerchantSwitcherConfig extends SprykerMerchantSwitcherConfig
{
    /**
     * @var bool
     */
    public const ENABLE_MERCHANT_SWITCHER = false;
}
