<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Wishlist;

use Spryker\Zed\Availability\Communication\Plugin\Wishlist\AvailabilityWishlistItemExpanderPlugin;
use Spryker\Zed\Availability\Communication\Plugin\Wishlist\SellableWishlistItemExpanderPlugin;
use Spryker\Zed\MerchantProductOfferWishlist\Communication\Plugin\Wishlist\MerchantProductOfferAddItemPreCheckPlugin;
use Spryker\Zed\MerchantProductOfferWishlist\Communication\Plugin\Wishlist\WishlistProductOfferPreAddItemPlugin;
use Spryker\Zed\MerchantProductWishlist\Communication\Plugin\Wishlist\WishlistMerchantProductPreAddItemPlugin;
use Spryker\Zed\MerchantSwitcher\Communication\Plugin\Wishlist\SingleMerchantWishlistItemsValidatorPlugin;
use Spryker\Zed\MerchantSwitcher\Communication\Plugin\Wishlist\SingleMerchantWishlistReloadItemsPlugin;
use Spryker\Zed\PriceProduct\Communication\Plugin\Wishlist\PriceProductWishlistItemExpanderPlugin;
use Spryker\Zed\PriceProductOffer\Communication\Plugin\Wishlist\PriceProductOfferWishlistItemExpanderPlugin;
use Spryker\Zed\ProductDiscontinued\Communication\Plugin\Wishlist\ProductDiscontinuedAddItemPreCheckPlugin;
use Spryker\Zed\Wishlist\WishlistDependencyProvider as SprykerWishlistDependencyProvider;

class WishlistDependencyProvider extends SprykerWishlistDependencyProvider
{
    /**
     * @return array<\Spryker\Zed\Wishlist\Dependency\Plugin\ItemExpanderPluginInterface>
     */
    protected function getItemExpanderPlugins()
    {
        return [];
    }

    /**
     * @return array<\Spryker\Zed\WishlistExtension\Dependency\Plugin\AddItemPreCheckPluginInterface>
     */
    protected function getAddItemPreCheckPlugins(): array
    {
        return [
            new ProductDiscontinuedAddItemPreCheckPlugin(), #ProductDiscontinuedFeature
            new MerchantProductOfferAddItemPreCheckPlugin(),
        ];
    }

    /**
     * @return array<\Spryker\Zed\WishlistExtension\Dependency\Plugin\WishlistReloadItemsPluginInterface>
     */
    protected function getWishlistReloadItemsPlugins(): array
    {
        return [
            new SingleMerchantWishlistReloadItemsPlugin(),
        ];
    }

    /**
     * @return array<\Spryker\Zed\WishlistExtension\Dependency\Plugin\WishlistItemsValidatorPluginInterface>
     */
    protected function getWishlistItemsValidatorPlugins(): array
    {
        return [
            new SingleMerchantWishlistItemsValidatorPlugin(),
        ];
    }

    /**
     * @return array<\Spryker\Zed\WishlistExtension\Dependency\Plugin\WishlistPreAddItemPluginInterface>
     */
    protected function getWishlistPreAddItemPlugins(): array
    {
        return [
            new WishlistMerchantProductPreAddItemPlugin(),
            new WishlistProductOfferPreAddItemPlugin(),
        ];
    }

    /**
     * @return array<\Spryker\Zed\WishlistExtension\Dependency\Plugin\WishlistItemExpanderPluginInterface>
     */
    protected function getWishlistItemExpanderPlugins(): array
    {
        return [
            new PriceProductWishlistItemExpanderPlugin(),
            new PriceProductOfferWishlistItemExpanderPlugin(),
            new AvailabilityWishlistItemExpanderPlugin(),
            new SellableWishlistItemExpanderPlugin(),
        ];
    }
}
