<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\WishlistPage;

use Spryker\Client\AvailabilityStorage\Plugin\ProductViewAvailabilityStorageExpanderPlugin;
use Spryker\Client\PriceProductStorage\Plugin\ProductViewPriceExpanderPlugin;
use Spryker\Client\ProductImageStorage\Plugin\ProductViewImageExpanderPlugin;
use SprykerShop\Yves\MerchantProductOfferWidget\Plugin\WishlistPage\MerchantProductOfferWishlistItemMetaFormExpanderPlugin;
use SprykerShop\Yves\MerchantProductOfferWidget\Plugin\WishlistPage\MerchantProductOfferWishlistItemRequestExpanderPlugin;
use SprykerShop\Yves\MerchantProductWidget\Plugin\WishlistPage\MerchantProductWishlistItemMetaFormExpanderPlugin;
use SprykerShop\Yves\MerchantProductWidget\Plugin\WishlistPage\MerchantProductWishlistItemRequestExpanderPlugin;
use SprykerShop\Yves\ProductConfigurationWishlistWidget\Plugin\WishlistPage\ProductConfigurationWishlistItemRequestExpanderPlugin;
use SprykerShop\Yves\WishlistPage\WishlistPageDependencyProvider as SprykerWishlistPageDependencyProvider;

class WishlistPageDependencyProvider extends SprykerWishlistPageDependencyProvider
{
    /**
     * @return array<\Spryker\Client\ProductStorage\Dependency\Plugin\ProductViewExpanderPluginInterface>
     */
    protected function getWishlistItemExpanderPlugins(): array
    {
        return [
            new ProductViewPriceExpanderPlugin(),
            new ProductViewImageExpanderPlugin(),
            new ProductViewAvailabilityStorageExpanderPlugin(),
        ];
    }

    /**
     * @return array<\SprykerShop\Yves\WishlistPageExtension\Dependency\Plugin\WishlistItemRequestExpanderPluginInterface>
     */
    protected function getWishlistItemRequestExpanderPlugins(): array
    {
        return [
            new MerchantProductWishlistItemRequestExpanderPlugin(),
            new MerchantProductOfferWishlistItemRequestExpanderPlugin(),
            new ProductConfigurationWishlistItemRequestExpanderPlugin(),
        ];
    }

    /**
     * @return array<\SprykerShop\Yves\WishlistPageExtension\Dependency\Plugin\WishlistItemMetaFormExpanderPluginInterface>
     */
    protected function getWishlistItemMetaFormExpanderPlugins(): array
    {
        return [
            new MerchantProductWishlistItemMetaFormExpanderPlugin(),
            new MerchantProductOfferWishlistItemMetaFormExpanderPlugin(),
        ];
    }
}
