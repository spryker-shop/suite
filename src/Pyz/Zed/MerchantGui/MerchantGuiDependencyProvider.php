<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\MerchantGui;

use Spryker\Zed\Kernel\Communication\Form\FormTypeInterface;
use Spryker\Zed\MerchantGui\MerchantGuiDependencyProvider as SprykerMerchantGuiDependencyProvider;
use Spryker\Zed\MerchantProfileGui\Communication\Plugin\MerchantGui\MerchantProfileFormExpanderPlugin;
use Spryker\Zed\MerchantProfileGui\Communication\Plugin\MerchantGui\Tabs\MerchantProfileContactPersonFormTabExpanderPlugin;
use Spryker\Zed\MerchantProfileGui\Communication\Plugin\MerchantGui\Tabs\MerchantProfileFormTabExpanderPlugin;
use Spryker\Zed\MerchantProfileGui\Communication\Plugin\MerchantGui\Tabs\MerchantProfileLegalInformationFormTabExpanderPlugin;
use Spryker\Zed\MerchantStockGui\Communication\Plugin\MerchantGui\MerchantStockMerchantFormExpanderPlugin;
use Spryker\Zed\MerchantUserGui\Communication\Plugin\MerchantGui\MerchantUserTabMerchantFormTabExpanderPlugin;
use Spryker\Zed\MerchantUserGui\Communication\Plugin\MerchantGui\MerchantUserViewMerchantUpdateFormViewExpanderPlugin;
use Spryker\Zed\Store\Communication\Plugin\Form\StoreRelationToggleFormTypePlugin;

class MerchantGuiDependencyProvider extends SprykerMerchantGuiDependencyProvider
{
    /**
     * @return array<\Spryker\Zed\MerchantGuiExtension\Dependency\Plugin\MerchantFormExpanderPluginInterface>
     */
    protected function getMerchantFormExpanderPlugins(): array
    {
        return [
            new MerchantProfileFormExpanderPlugin(),
            new MerchantStockMerchantFormExpanderPlugin(),
        ];
    }

    /**
     * @return array<\Spryker\Zed\MerchantGuiExtension\Dependency\Plugin\MerchantFormTabExpanderPluginInterface>
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
     * @return array<\Spryker\Zed\MerchantGuiExtension\Dependency\Plugin\MerchantUpdateFormViewExpanderPluginInterface>
     */
    protected function getMerchantUpdateFormViewExpanderPlugins(): array
    {
        return [
            new MerchantUserViewMerchantUpdateFormViewExpanderPlugin(),
        ];
    }

    /**
     * @return \Spryker\Zed\Kernel\Communication\Form\FormTypeInterface
     */
    protected function getStoreRelationFormTypePlugin(): FormTypeInterface
    {
        return new StoreRelationToggleFormTypePlugin();
    }
}
