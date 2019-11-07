<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\SalesConfigurableBundle;

use Spryker\Zed\SalesConfigurableBundle\SalesConfigurableBundleConfig as SprykerSalesConfigurableBundleConfig;

class SalesConfigurableBundleConfig extends SprykerSalesConfigurableBundleConfig
{
    /**
     * @see \Spryker\Zed\SalesConfigurableBundle\SalesConfigurableBundleConfig::CONFIGURABLE_BUNDLE_ITEM_NONSPLIT_QUANTITY_THRESHOLD
     */
    protected const CONFIGURABLE_BUNDLE_ITEM_NONSPLIT_QUANTITY_THRESHOLD = 10;
}
