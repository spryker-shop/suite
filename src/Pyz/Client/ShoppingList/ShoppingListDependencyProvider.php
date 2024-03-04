<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\ShoppingList;

use Spryker\Client\Merchant\Plugin\ShoppingList\MerchantShoppingListItemToItemMapperPlugin;
use Spryker\Client\ProductConfigurationShoppingList\Plugin\ShoppingList\ProductConfigurationShoppingListExpanderPlugin;
use Spryker\Client\ProductConfigurationShoppingList\Plugin\ShoppingList\ProductConfigurationShoppingListItemMapperPlugin;
use Spryker\Client\ProductConfigurationShoppingList\Plugin\ShoppingList\ProductConfigurationShoppingListItemToItemMapperPlugin;
use Spryker\Client\ProductOfferShoppingList\Plugin\ShoppingList\ProductOfferShoppingListItemMapperPlugin;
use Spryker\Client\ProductOfferShoppingList\Plugin\ShoppingList\ProductOfferShoppingListItemToItemMapperPlugin;
use Spryker\Client\ShoppingList\ShoppingListDependencyProvider as SprykerShoppingListDependencyProvider;
use Spryker\Client\ShoppingListNote\Plugin\ShoppingListItemNoteToItemCartNoteMapperPlugin;
use Spryker\Client\ShoppingListProductOptionConnector\ShoppingList\ProductOptionQuoteItemToItemMapperPlugin;
use Spryker\Client\ShoppingListProductOptionConnector\ShoppingList\ShoppingListItemProductOptionRequestMapperPlugin;
use Spryker\Client\ShoppingListProductOptionConnector\ShoppingList\ShoppingListItemProductOptionToItemProductOptionMapperPlugin;

class ShoppingListDependencyProvider extends SprykerShoppingListDependencyProvider
{
    /**
     * @return array<\Spryker\Client\ShoppingListExtension\Dependency\Plugin\ShoppingListItemMapperPluginInterface>
     */
    protected function getAddItemShoppingListItemMapperPlugins(): array
    {
        return [
            new ShoppingListItemProductOptionRequestMapperPlugin(),
            new ProductOfferShoppingListItemMapperPlugin(),
            new ProductConfigurationShoppingListItemMapperPlugin(),
        ];
    }

    /**
     * @return array<\Spryker\Client\ShoppingListExtension\Dependency\Plugin\ShoppingListItemToItemMapperPluginInterface>
     */
    protected function getShoppingListItemToItemMapperPlugins(): array
    {
        return [
            new ShoppingListItemNoteToItemCartNoteMapperPlugin(),
            new ShoppingListItemProductOptionToItemProductOptionMapperPlugin(),
            new ProductOfferShoppingListItemToItemMapperPlugin(),
            new MerchantShoppingListItemToItemMapperPlugin(),
            new ProductConfigurationShoppingListItemToItemMapperPlugin(),
        ];
    }

    /**
     * @return array<\Spryker\Client\ShoppingListExtension\Dependency\Plugin\QuoteItemToItemMapperPluginInterface>
     */
    protected function getQuoteItemToItemMapperPlugins(): array
    {
        return [
            new ProductOptionQuoteItemToItemMapperPlugin(),
        ];
    }

    /**
     * @return array<\Spryker\Client\ShoppingListExtension\Dependency\Plugin\ShoppingListExpanderPluginInterface>
     */
    protected function getShoppingListExpanderPlugins(): array
    {
        return [
            new ProductConfigurationShoppingListExpanderPlugin(),
        ];
    }
}
