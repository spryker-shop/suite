<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\WishlistsRestApi;

use Spryker\Zed\MerchantProductOfferWishlistRestApi\Communication\Plugin\ProductOfferRestWishlistItemsAttributesDeleteStrategyPlugin;
use Spryker\Zed\ProductConfigurationWishlistsRestApi\Communication\Plugin\WishlistsRestApi\ProductConfigurationRestWishlistItemsAttributesDeleteStrategyPlugin;
use Spryker\Zed\ProductConfigurationWishlistsRestApi\Communication\Plugin\WishlistsRestApi\ProductConfigurationRestWishlistItemsAttributesUpdateStrategyPlugin;
use Spryker\Zed\WishlistsRestApi\WishlistsRestApiDependencyProvider as SprykerWishlistsRestApiDependencyProvider;

class WishlistsRestApiDependencyProvider extends SprykerWishlistsRestApiDependencyProvider
{
    /**
     * @return array<\Spryker\Zed\WishlistsRestApiExtension\Dependency\Plugin\RestWishlistItemsAttributesDeleteStrategyPluginInterface>
     */
    protected function getRestWishlistItemsAttributesDeleteStrategyPlugins(): array
    {
        return [
            new ProductConfigurationRestWishlistItemsAttributesDeleteStrategyPlugin(),
            new ProductOfferRestWishlistItemsAttributesDeleteStrategyPlugin(),
        ];
    }

    /**
     * @return array<\Spryker\Zed\WishlistsRestApiExtension\Dependency\Plugin\RestWishlistItemsAttributesUpdateStrategyPluginInterface>
     */
    protected function getRestWishlistItemsAttributesUpdateStrategyPlugins(): array
    {
        return [
            new ProductConfigurationRestWishlistItemsAttributesUpdateStrategyPlugin(),
        ];
    }
}
