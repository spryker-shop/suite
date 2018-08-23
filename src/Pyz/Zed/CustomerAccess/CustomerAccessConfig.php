<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\CustomerAccess;

use Spryker\Zed\CustomerAccess\CustomerAccessConfig as SprykerCustomerAccessConfig;

class CustomerAccessConfig extends SprykerCustomerAccessConfig
{
    /**
     * {@inheritdoc}
     *
     * @return array
     */
    public function getContentTypes(): array
    {
        return [
            static::CONTENT_TYPE_PRICE,
            static::CONTENT_TYPE_ORDER_PLACE_SUBMIT,
            static::CONTENT_TYPE_ADD_TO_CART,
            static::CONTENT_TYPE_WISHLIST,
            static::CONTENT_TYPE_SHOPPING_LIST,
        ];
    }
}
