<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\MerchantGui;

use Spryker\Zed\MerchantGui\MerchantGuiDependencyProvider as SprykerMerchantGuiDependencyProvider;
use Spryker\Zed\MerchantProfileGui\Communication\Plugin\MerchantGui\MerchantProfileFormExpanderPlugin;
use Spryker\Zed\MerchantProfileGui\Communication\Plugin\MerchantGui\Table\MerchantProfileMerchantTableActionExpanderPlugin;
use Spryker\Zed\MerchantProfileGui\Communication\Plugin\MerchantGui\Table\MerchantProfileMerchantTableConfigExpanderPlugin;
use Spryker\Zed\MerchantProfileGui\Communication\Plugin\MerchantGui\Table\MerchantProfileMerchantTableDataExpanderPlugin;
use Spryker\Zed\MerchantProfileGui\Communication\Plugin\MerchantGui\Table\MerchantProfileMerchantTableHeaderExpanderPlugin;
use Spryker\Zed\MerchantProfileGui\Communication\Plugin\MerchantGui\Tabs\MerchantProfileContactPersonFormTabExpanderPlugin;
use Spryker\Zed\MerchantProfileGui\Communication\Plugin\MerchantGui\Tabs\MerchantProfileFormTabExpanderPlugin;
use Spryker\Zed\MerchantProfileGui\Communication\Plugin\MerchantGui\Tabs\MerchantProfileLegalInformationFormTabExpanderPlugin;
use Spryker\Zed\MerchantStockGui\Communication\Plugin\MerchantGui\MerchantStockMerchantFormExpanderPlugin;
use Spryker\Zed\MerchantUserGui\Communication\Plugin\MerchantGui\MerchantUserTabMerchantFormTabExpanderPlugin;
use Spryker\Zed\MerchantUserGui\Communication\Plugin\MerchantGui\MerchantUserViewMerchantUpdateFormViewExpanderPlugin;

class MerchantGuiDependencyProvider extends SprykerMerchantGuiDependencyProvider
{
    /**
     * @return \Spryker\Zed\MerchantGuiExtension\Dependency\Plugin\MerchantFormExpanderPluginInterface[]
     */
    protected function getMerchantProfileFormExpanderPlugins(): array
    {
        return [
            new MerchantProfileFormExpanderPlugin(),
            new MerchantStockMerchantFormExpanderPlugin(),
        ];
    }

    /**
     * @return \Spryker\Zed\MerchantGuiExtension\Dependency\Plugin\MerchantTableDataExpanderPluginInterface[]
     */
    protected function getMerchantTableDataExpanderPlugins(): array
    {
        return [
            new MerchantProfileMerchantTableDataExpanderPlugin(),
        ];
    }

    /**
     * @return \Spryker\Zed\MerchantGuiExtension\Dependency\Plugin\MerchantTableActionExpanderPluginInterface[]
     */
    protected function getMerchantTableActionExpanderPlugins(): array
    {
        return [
            new MerchantProfileMerchantTableActionExpanderPlugin(),
        ];
    }

    /**
     * @return \Spryker\Zed\MerchantGuiExtension\Dependency\Plugin\MerchantTableHeaderExpanderPluginInterface[]
     */
    protected function getMerchantTableHeaderExpanderPlugins(): array
    {
        return [
            new MerchantProfileMerchantTableHeaderExpanderPlugin(),
        ];
    }

    /**
     * @return \Spryker\Zed\MerchantGuiExtension\Dependency\Plugin\MerchantTableConfigExpanderPluginInterface[]
     */
    protected function getMerchantTableConfigExpanderPlugins(): array
    {
        return [
            new MerchantProfileMerchantTableConfigExpanderPlugin(),
        ];
    }

    /**
     * @return \Spryker\Zed\MerchantGuiExtension\Dependency\Plugin\MerchantFormTabExpanderPluginInterface[]
     */
    protected function getMerchantFormTabsExpanderPlugins(): array
    {
        return [
            new MerchantProfileContactPersonFormTabExpanderPlugin(),
            new MerchantProfileFormTabExpanderPlugin(),
            new MerchantProfileLegalInformationFormTabExpanderPlugin(),
            new MerchantUserTabMerchantFormTabExpanderPlugin(),
        ];
    }

    /**
     * @return \Spryker\Zed\MerchantGuiExtension\Dependency\Plugin\MerchantUpdateFormViewExpanderPluginInterface[]
     */
    protected function getMerchantUpdateFormViewExpanderPlugins(): array
    {
        return [
            new MerchantUserViewMerchantUpdateFormViewExpanderPlugin(),
        ];
    }
}
