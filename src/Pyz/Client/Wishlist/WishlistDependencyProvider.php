<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\Wishlist;

use Spryker\Client\MerchantProductOfferWishlist\Plugin\Wishlist\WishlistProductOfferCollectionToRemoveExpanderPlugin;
use Spryker\Client\MerchantProductOfferWishlist\Plugin\Wishlist\WishlistProductOfferPostMoveToCartCollectionExpanderPlugin;
use Spryker\Client\MerchantProductWishlist\Plugin\Wishlist\WishlistMerchantProductCollectionToRemoveExpanderPlugin;
use Spryker\Client\MerchantProductWishlist\Plugin\Wishlist\WishlistMerchantProductPostMoveToCartCollectionExpanderPlugin;
use Spryker\Client\ProductConfigurationWishlist\Plugin\Wishlist\ProductConfigurationWishlistCollectionToRemoveExpanderPlugin;
use Spryker\Client\ProductConfigurationWishlist\Plugin\Wishlist\ProductConfigurationWishlistPostMoveToCartCollectionExpanderPlugin;
use Spryker\Client\Wishlist\WishlistDependencyProvider as SprykerWishlistDependencyProvider;

class WishlistDependencyProvider extends SprykerWishlistDependencyProvider
{
    /**
     * @return array<\Spryker\Client\WishlistExtension\Dependency\Plugin\WishlistPostMoveToCartCollectionExpanderPluginInterface>
     */
    protected function getWishlistPostMoveToCartCollectionExpanderPlugins(): array
    {
        return [
            new WishlistProductOfferPostMoveToCartCollectionExpanderPlugin(),
            new WishlistMerchantProductPostMoveToCartCollectionExpanderPlugin(),
            new ProductConfigurationWishlistPostMoveToCartCollectionExpanderPlugin(),
        ];
    }

    /**
     * @return array<\Spryker\Client\WishlistExtension\Dependency\Plugin\WishlistCollectionToRemoveExpanderPluginInterface>
     */
    protected function getWishlistCollectionToRemoveExpanderPlugins(): array
    {
        return [
            new WishlistProductOfferCollectionToRemoveExpanderPlugin(),
            new WishlistMerchantProductCollectionToRemoveExpanderPlugin(),
            new ProductConfigurationWishlistCollectionToRemoveExpanderPlugin(),
        ];
    }
}
