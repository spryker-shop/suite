<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\MerchantSalesOrderMerchantUserGui;

use Spryker\Zed\MerchantSalesOrderMerchantUserGui\MerchantSalesOrderMerchantUserGuiConfig as SprykerMerchantSalesOrderMerchantUserGuiConfig;

class MerchantSalesOrderMerchantUserGuiConfig extends SprykerMerchantSalesOrderMerchantUserGuiConfig
{
    /**
     * @return array<string>
     */
    public function getMerchantSalesOrderDetailExternalBlocksUrls(): array
    {
        return [
            'cart_note' => '/cart-note-merchant-sales-order-gui/merchant-sales-order/list',
            'discount' => '/discount-merchant-sales-order-gui/merchant-sales-order/list',
        ];
    }
}
