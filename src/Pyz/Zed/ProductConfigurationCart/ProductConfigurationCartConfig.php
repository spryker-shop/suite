<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Pyz\Zed\ProductConfigurationCart;

use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Zed\ProductConfigurationCart\ProductConfigurationCartConfig as SprykerProductConfigurationCartConfig;

class ProductConfigurationCartConfig extends SprykerProductConfigurationCartConfig
{
    /**
     * @return array<string>
     */
    public function getItemFieldsForIsSameItemComparison(): array
    {
        return array_merge(parent::getItemFieldsForIsSameItemComparison(), [
            ItemTransfer::MERCHANT_REFERENCE,
            ItemTransfer::PRODUCT_OFFER_REFERENCE,
        ]);
    }
}
