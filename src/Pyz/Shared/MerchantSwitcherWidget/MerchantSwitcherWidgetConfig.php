<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Shared\MerchantSwitcherWidget;

use Pyz\Shared\MerchantSwitcher\MerchantSwitcherConfig;
use SprykerShop\Shared\MerchantSwitcherWidget\MerchantSwitcherWidgetConfig as SprykerMerchantSwitcherWidgetConfig;

class MerchantSwitcherWidgetConfig extends SprykerMerchantSwitcherWidgetConfig
{
    protected const ENABLE_MERCHANT_SWITCHER = MerchantSwitcherConfig::ENABLE_MERCHANT_SWITCHER;
}
