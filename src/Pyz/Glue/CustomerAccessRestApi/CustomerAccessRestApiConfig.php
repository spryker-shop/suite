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
use Spryker\Shared\CustomerAccess\CustomerAccessConfig;

class CustomerAccessRestApiConfig extends SprykerCustomerAccessRestApiConfig
{
    protected const RESOURCE_TYPE_PERMISSION_PLUGIN = [
        ProductPricesRestApiConfig::RESOURCE_ABSTRACT_PRODUCT_PRICES => SeePricePermissionPlugin::KEY,
        ProductPricesRestApiConfig::RESOURCE_CONCRETE_PRODUCT_PRICES => SeePricePermissionPlugin::KEY,
        CheckoutRestApiConfig::RESOURCE_CHECKOUT => SeeOrderPlaceSubmitPermissionPlugin::KEY,
        CheckoutRestApiConfig::RESOURCE_CHECKOUT_DATA => SeeOrderPlaceSubmitPermissionPlugin::KEY,
        CartsRestApiConfig::RESOURCE_GUEST_CARTS_ITEMS => SeeAddToCartPermissionPlugin::KEY,
        WishlistsRestApiConfig::RESOURCE_WISHLISTS => SeeWishlistPermissionPlugin::KEY,
        WishlistsRestApiConfig::RESOURCE_WISHLIST_ITEMS => SeeWishlistPermissionPlugin::KEY,
    ];

    protected const CUSTOMER_ACCESS_CONTENT_TYPE_RESOURCE_TYPE = [
        CustomerAccessConfig::CONTENT_TYPE_PRICE => [
            ProductPricesRestApiConfig::RESOURCE_ABSTRACT_PRODUCT_PRICES,
            ProductPricesRestApiConfig::RESOURCE_CONCRETE_PRODUCT_PRICES,
        ],
        CustomerAccessConfig::CONTENT_TYPE_ORDER_PLACE_SUBMIT => [
            CheckoutRestApiConfig::RESOURCE_CHECKOUT,
            CheckoutRestApiConfig::RESOURCE_CHECKOUT_DATA,
        ],
        CustomerAccessConfig::CONTENT_TYPE_ADD_TO_CART => [
            CartsRestApiConfig::RESOURCE_CART_ITEMS,
        ],
        CustomerAccessConfig::CONTENT_TYPE_WISHLIST => [
            WishlistsRestApiConfig::RESOURCE_WISHLISTS,
            WishlistsRestApiConfig::RESOURCE_WISHLIST_ITEMS,
        ],
    ];
}
