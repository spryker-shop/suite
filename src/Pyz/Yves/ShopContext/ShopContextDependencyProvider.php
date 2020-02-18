<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\ShopContext;

use Spryker\Yves\ShopContext\ShopContextDependencyProvider as SprykerShopShopContextDependencyProvider;
use SprykerShop\Yves\MerchantSwitcherWidget\Plugin\ShopApplication\MerchantShopContextExpanderPlugin;

class ShopContextDependencyProvider extends SprykerShopShopContextDependencyProvider
{
    /**
     * @return \Spryker\Yves\ShopContextExtension\Dependency\Plugin\ShopContextExpanderPluginInterface[]
     */
    protected function getShopContextExpanderPlugins(): array
    {
        return [
            new MerchantShopContextExpanderPlugin(),
        ];
    }
}
