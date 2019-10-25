<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductListGui;

use Spryker\Zed\ConfigurableBundleGui\Communication\Plugin\ProductListGui\ConfigurableBundleTemplateListProductListTopButtonsExpanderPlugin;
use Spryker\Zed\ConfigurableBundleGui\Communication\Plugin\ProductListGui\ConfigurableBundleTemplateProductListUsedByTableDataExpanderPlugin;
use Spryker\Zed\MerchantRelationshipProductListGui\Communication\Plugin\ProductListGui\MerchantRelationListProductListTopButtonsExpanderPlugin;
use Spryker\Zed\MerchantRelationshipProductListGui\Communication\Plugin\ProductListGui\MerchantRelationshipProductListUsedByTableDataExpanderPlugin;
use Spryker\Zed\ProductListGui\ProductListGuiDependencyProvider as SprykerProductListGuiDependencyProvider;

class ProductListGuiDependencyProvider extends SprykerProductListGuiDependencyProvider
{
    /**
     * @return \Spryker\Zed\ProductListGuiExtension\Dependency\Plugin\ProductListTopButtonsExpanderPluginInterface[]
     */
    protected function getProductListTopButtonsExpanderPlugins(): array
    {
        return [
            new ConfigurableBundleTemplateListProductListTopButtonsExpanderPlugin(),
            new MerchantRelationListProductListTopButtonsExpanderPlugin(),
        ];
    }

    /**
     * @return \Spryker\Zed\ProductListGuiExtension\Dependency\Plugin\ProductListUsedByTableDataExpanderPluginInterface[]
     */
    protected function getProductListUsedByTableDataExpanderPlugins(): array
    {
        return [
            new ConfigurableBundleTemplateProductListUsedByTableDataExpanderPlugin(),
            new MerchantRelationshipProductListUsedByTableDataExpanderPlugin(),
        ];
    }
}
