<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Glue\CustomerAccessRestApi;

use Pyz\Glue\CheckoutRestApi\CheckoutRestApiConfig;
use Spryker\Client\CustomerAccessPermission\Plugin\SeeAddToCartPermissionPlugin;
use Spryker\Client\CustomerAccessPermission\Plugin\SeeOrderPlaceSubmitPermissionPlugin;
use Spryker\Client\CustomerAccessPermission\Plugin\SeePricePermissionPlugin;
use Spryker\Client\CustomerAccessPermission\Plugin\SeeWishlistPermissionPlugin;
use Spryker\Glue\CartsRestApi\CartsRestApiConfig;
use Spryker\Glue\CustomerAccessRestApi\CustomerAccessRestApiConfig as SprykerCustomerAccessRestApiConfig;
use Spryker\Glue\ProductPricesRestApi\ProductPricesRestApiConfig;
use Spryker\Glue\WishlistsRestApi\WishlistsRestApiConfig;

class CustomerAccessRestApiConfig extends SprykerCustomerAccessRestApiConfig
{
    protected const RESOURCE_TYPE_PERMISSION_PLUGIN = [
        ProductPricesRestApiConfig::RESOURCE_ABSTRACT_PRODUCT_PRICES => SeePricePermissionPlugin::KEY,
        ProductPricesRestApiConfig::RESOURCE_CONCRETE_PRODUCT_PRICES => SeePricePermissionPlugin::KEY,
        CheckoutRestApiConfig::RESOURCE_CHECKOUT => SeeOrderPlaceSubmitPermissionPlugin::KEY,
        CheckoutRestApiConfig::RESOURCE_CHECKOUT_DATA => SeeOrderPlaceSubmitPermissionPlugin::KEY,
        CartsRestApiConfig::RESOURCE_CART_ITEMS => SeeAddToCartPermissionPlugin::KEY,
        WishlistsRestApiConfig::RESOURCE_WISHLISTS => SeeWishlistPermissionPlugin::KEY,
        WishlistsRestApiConfig::RESOURCE_WISHLIST_ITEMS => SeeWishlistPermissionPlugin::KEY,
    ];
}
