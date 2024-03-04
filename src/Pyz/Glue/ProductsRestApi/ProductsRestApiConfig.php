<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Glue\ProductsRestApi;

use Spryker\Glue\ProductsRestApi\ProductsRestApiConfig as SprykerProductsRestApiConfig;

class ProductsRestApiConfig extends SprykerProductsRestApiConfig
{
    /**
     * @var bool
     */
    public const ALLOW_PRODUCT_CONCRETE_EAGER_RELATIONSHIP = false;
}
