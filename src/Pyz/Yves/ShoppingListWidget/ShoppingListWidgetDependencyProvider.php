<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\ShoppingListWidget;

use SprykerShop\Yves\ProductQuantity\Plugin\ProductViewTransferQuantityRestrictionExpanderPlugin;
use SprykerShop\Yves\ShoppingListWidget\ShoppingListWidgetDependencyProvider as SprykerShoppingListWidgetDependencyProvider;

class ShoppingListWidgetDependencyProvider extends SprykerShoppingListWidgetDependencyProvider
{
    /**
     * @return \SprykerShop\Yves\ShoppingListPageExtension\Dependency\Plugin\ProductViewTransferExpanderPluginInterface[]
     */
    protected function getProductViewExpanderPlugins(): array
    {
        return [
            new ProductViewTransferQuantityRestrictionExpanderPlugin(),
        ];
    }
}
