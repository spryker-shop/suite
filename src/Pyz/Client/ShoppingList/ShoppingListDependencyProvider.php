<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\ShoppingList;

use Spryker\Client\ShoppingList\ShoppingListDependencyProvider as SprykerShoppingListDependencyProvider;

class ShoppingListDependencyProvider extends SprykerShoppingListDependencyProvider
{
    /**
     * @return \Spryker\Client\ShoppingList\Dependency\Plugin\ShoppingListItemToItemMapperPluginInterface[]
     */
    protected function getShoppingListItemToItemMapperPlugins(): array
    {
        return [];
    }
}
