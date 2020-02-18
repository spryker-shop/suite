<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\ShopContext;

use SprykerShop\Yves\MerchantSwitcherWidget\Plugin\ShopApplication\SelectedMerchantShopContextExpanderPlugin;
use SprykerShop\Yves\ShopContext\ShopContextDependencyProvider as SprykerShopShopContextDependencyProvider;

class ShopContextDependencyProvider extends SprykerShopShopContextDependencyProvider
{
    /**
     * @return \SprykerShop\Yves\ShopContextExtension\Dependency\Plugin\ShopContextExpanderPluginInterface[]
     */
    protected function getShopContextExpanderPlugins(): array
    {
        return [
            new SelectedMerchantShopContextExpanderPlugin(),
            new MerchantShopContextExpanderPlugin(),
        ];
    }
}
