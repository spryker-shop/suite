<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductConfiguration;

use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Zed\ProductConfiguration\ProductConfigurationConfig as SprykerProductConfigurationConfig;

class ProductConfigurationConfig extends SprykerProductConfigurationConfig
{
    /**
     * @return string[]
     */
    public function getItemFieldsForIsSameItemComparison()
    {
        return array_merge(parent::getItemFieldsForIsSameItemComparison(), [
            ItemTransfer::MERCHANT_REFERENCE,
            ItemTransfer::PRODUCT_OFFER_REFERENCE,
        ]);
    }
}
