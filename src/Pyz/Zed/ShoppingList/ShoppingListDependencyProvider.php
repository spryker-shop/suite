<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ShoppingList;

use Spryker\Zed\MerchantProduct\Communication\Plugin\ShoppingList\MerchantProductAddItemPreCheckPlugin;
use Spryker\Zed\MerchantProduct\Communication\Plugin\ShoppingList\MerchantProductShoppingListItemBulkPostSavePlugin;
use Spryker\Zed\MerchantProduct\Communication\Plugin\ShoppingListExtension\MerchantProductShoppingListItemCollectionExpanderPlugin;
use Spryker\Zed\MerchantProductOffer\Communication\Plugin\ShoppingList\MerchantProductOfferAddItemPreCheckPlugin;
use Spryker\Zed\MerchantProductOffer\Communication\Plugin\ShoppingList\MerchantProductOfferShoppingListItemBulkPostSavePlugin;
use Spryker\Zed\MerchantProductOffer\Communication\Plugin\ShoppingList\MerchantProductOfferShoppingListItemCollectionExpanderPlugin;
use Spryker\Zed\ProductApproval\Communication\Plugin\ShoppingList\ProductApprovalAddItemPreCheckPlugin;
use Spryker\Zed\ProductBundle\Communication\Plugin\ShoppingList\ReplaceBundledQuoteItemsPreConvertPlugin;
use Spryker\Zed\ProductConfigurationShoppingList\Communication\Plugin\ShoppingList\ItemProductConfigurationItemToShoppingListItemMapperPlugin;
use Spryker\Zed\ProductConfigurationShoppingList\Communication\Plugin\ShoppingList\ProductConfigurationShoppingListAddItemPreCheckPlugin;
use Spryker\Zed\ProductConfigurationShoppingList\Communication\Plugin\ShoppingList\ProductConfigurationShoppingListItemBulkPostSavePlugin;
use Spryker\Zed\ProductConfigurationShoppingList\Communication\Plugin\ShoppingList\ProductConfigurationShoppingListItemCollectionExpanderPlugin;
use Spryker\Zed\ProductDiscontinued\Communication\Plugin\ShoppingList\ProductDiscontinuedAddItemPreCheckPlugin;
use Spryker\Zed\ProductOfferShoppingList\Communication\Plugin\ShoppingList\ProductOfferItemToShoppingListItemMapperPlugin;
use Spryker\Zed\ProductOfferShoppingList\Communication\Plugin\ShoppingList\ProductOfferShoppingListAddItemPreCheckPlugin;
use Spryker\Zed\ShoppingList\Communication\Plugin\ShoppingList\ShoppingListItemProductConcreteHasValidStoreAddItemPreCheckPlugin;
use Spryker\Zed\ShoppingList\Communication\Plugin\ShoppingListItemProductConcreteActiveAddItemPreCheckPlugin;
use Spryker\Zed\ShoppingList\ShoppingListDependencyProvider as SprykerShoppingListDependencyProvider;
use Spryker\Zed\ShoppingListNote\Communication\Plugin\ItemCartNoteToShoppingListItemNoteMapperPlugin;
use Spryker\Zed\ShoppingListNote\Communication\Plugin\ShoppingListItemCollectionNoteExpanderPlugin;
use Spryker\Zed\ShoppingListNote\Communication\Plugin\ShoppingListItemNoteBeforeDeletePlugin;
use Spryker\Zed\ShoppingListNote\Communication\Plugin\ShoppingListItemNoteBulkPostSavePlugin;
use Spryker\Zed\ShoppingListProductOptionConnector\Communication\Plugin\ShoppingList\CartItemProductOptionToShoppingListItemProductOptionMapperPlugin;
use Spryker\Zed\ShoppingListProductOptionConnector\Communication\Plugin\ShoppingList\ShoppingListItemCollectionProductOptionExpanderPlugin;
use Spryker\Zed\ShoppingListProductOptionConnector\Communication\Plugin\ShoppingList\ShoppingListItemProductOptionBeforeDeletePlugin;
use Spryker\Zed\ShoppingListProductOptionConnector\Communication\Plugin\ShoppingList\ShoppingListItemProductOptionBulkPostSavePlugin;

class ShoppingListDependencyProvider extends SprykerShoppingListDependencyProvider
{
    /**
     * @return array<\Spryker\Zed\ShoppingListExtension\Dependency\Plugin\AddItemPreCheckPluginInterface>
     */
    protected function getAddItemPreCheckPlugins(): array
    {
        return [
            new ShoppingListItemProductConcreteActiveAddItemPreCheckPlugin(),
            new ProductDiscontinuedAddItemPreCheckPlugin(), #ProductDiscontinuedFeature
            new ProductOfferShoppingListAddItemPreCheckPlugin(),
            new ShoppingListItemProductConcreteHasValidStoreAddItemPreCheckPlugin(),
            new MerchantProductOfferAddItemPreCheckPlugin(),
            new MerchantProductAddItemPreCheckPlugin(),
            new ProductApprovalAddItemPreCheckPlugin(),
            new ProductConfigurationShoppingListAddItemPreCheckPlugin(),
        ];
    }

    /**
     * @return array<\Spryker\Zed\ShoppingListExtension\Dependency\Plugin\QuoteItemsPreConvertPluginInterface>
     */
    protected function getQuoteItemExpanderPlugins(): array
    {
        return [
            new ReplaceBundledQuoteItemsPreConvertPlugin(),
        ];
    }

    /**
     * @return array<\Spryker\Zed\ShoppingListExtension\Dependency\Plugin\ShoppingListItemBulkPostSavePluginInterface>
     */
    protected function getShoppingListItemBulkPostSavePlugins(): array
    {
        return [
            new ShoppingListItemNoteBulkPostSavePlugin(), #ShoppingListNoteFeature
            new ShoppingListItemProductOptionBulkPostSavePlugin(),
            new MerchantProductShoppingListItemBulkPostSavePlugin(),
            new MerchantProductOfferShoppingListItemBulkPostSavePlugin(),
            new ProductConfigurationShoppingListItemBulkPostSavePlugin(),
        ];
    }

    /**
     * @return array<\Spryker\Zed\ShoppingListExtension\Dependency\Plugin\ShoppingListItemBeforeDeletePluginInterface>
     */
    protected function getBeforeDeleteShoppingListItemPlugins(): array
    {
        return [
            new ShoppingListItemNoteBeforeDeletePlugin(), #ShoppingListNoteFeature
            new ShoppingListItemProductOptionBeforeDeletePlugin(),
        ];
    }

    /**
     * @return array<\Spryker\Zed\ShoppingListExtension\Dependency\Plugin\ShoppingListItemCollectionExpanderPluginInterface>
     */
    protected function getItemCollectionExpanderPlugins(): array
    {
        return [
            new ShoppingListItemCollectionNoteExpanderPlugin(),
            new ShoppingListItemCollectionProductOptionExpanderPlugin(),
            new MerchantProductOfferShoppingListItemCollectionExpanderPlugin(),
            new MerchantProductShoppingListItemCollectionExpanderPlugin(),
            new ProductConfigurationShoppingListItemCollectionExpanderPlugin(),
        ];
    }

    /**
     * @return array<\Spryker\Zed\ShoppingListExtension\Dependency\Plugin\ItemToShoppingListItemMapperPluginInterface>
     */
    protected function getItemToShoppingListItemMapperPlugins(): array
    {
        return [
            new ItemCartNoteToShoppingListItemNoteMapperPlugin(),
            new CartItemProductOptionToShoppingListItemProductOptionMapperPlugin(),
            new ProductOfferItemToShoppingListItemMapperPlugin(),
            new ItemProductConfigurationItemToShoppingListItemMapperPlugin(),
        ];
    }
}
