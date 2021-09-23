<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductOption;

use Spryker\Zed\Kernel\Container;
use Spryker\Zed\MerchantGui\Communication\Plugin\ProductOptionGui\MerchantProductOptionListActionViewDataExpanderPlugin;
use Spryker\Zed\MerchantProductOption\Communication\Plugin\ProductOption\MerchantProductOptionGroupExpanderPlugin;
use Spryker\Zed\MerchantProductOptionGui\Communication\Plugin\ProductOptionGui\MerchantProductOptionListTableQueryCriteriaExpanderPlugin;
use Spryker\Zed\Money\Communication\Plugin\Form\MoneyCollectionFormTypePlugin;
use Spryker\Zed\ProductOption\ProductOptionDependencyProvider as SprykerProductOptionDependencyProvider;
use Spryker\Zed\ShoppingListProductOptionConnector\Communication\Plugin\ProductOption\ShoppingListItemsProductOptionValuesPreRemovePlugin;

class ProductOptionDependencyProvider extends SprykerProductOptionDependencyProvider
{
    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Communication\Form\FormTypeInterface
     */
    protected function createMoneyCollectionFormTypePlugin(Container $container)
    {
        return new MoneyCollectionFormTypePlugin();
    }

    /**
     * @return array<\Spryker\Zed\ProductOptionExtension\Dependency\Plugin\ProductOptionValuesPreRemovePluginInterface>
     */
    protected function getProductOptionValuesPreRemovePlugins(): array
    {
        return [
            new ShoppingListItemsProductOptionValuesPreRemovePlugin(),
        ];
    }

    /**
     * @return array<\Spryker\Zed\ProductOptionGuiExtension\Dependency\Plugin\ProductOptionListActionViewDataExpanderPluginInterface>
     */
    protected function getProductOptionListActionViewDataExpanderPlugins(): array
    {
        return [
            new MerchantProductOptionListActionViewDataExpanderPlugin(),
        ];
    }

    /**
     * @return array<\Spryker\Zed\ProductOptionGuiExtension\Dependency\Plugin\ProductOptionListTableQueryCriteriaExpanderPluginInterface>
     */
    protected function getProductOptionListTableQueryCriteriaExpanderPlugins(): array
    {
        return [
            new MerchantProductOptionListTableQueryCriteriaExpanderPlugin(),
        ];
    }

    /**
     * @return array<\Spryker\Zed\ProductOptionExtension\Dependency\Plugin\ProductOptionGroupExpanderPluginInterface>
     */
    protected function getProductOptionGroupExpanderPlugins(): array
    {
        return [
            new MerchantProductOptionGroupExpanderPlugin(),
        ];
    }
}
