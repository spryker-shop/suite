<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Benchmark\Glue\Product\PageObject;

class ProductPage
{
    /**
     * @see \Spryker\Glue\ProductsRestApi\ProductsRestApiConfig::RESOURCE_ABSTRACT_PRODUCTS
     * @var string
     */
    public const ENDPOINT_PRODUCT_ABSTRACT = '/abstract-products/%s';

    /**
     * @see \Spryker\Glue\ProductsRestApi\ProductsRestApiConfig::RESOURCE_CONCRETE_PRODUCTS
     * @var string
     */
    public const ENDPOINT_PRODUCT_CONCRETE = '/concrete-products/%s';
}
