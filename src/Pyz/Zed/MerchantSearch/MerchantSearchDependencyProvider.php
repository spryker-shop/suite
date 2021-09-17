<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\MerchantSearch;

use Spryker\Zed\MerchantCategorySearch\Communication\Plugin\MerchantSearch\MerchantCategoryMerchantSearchDataExpanderPlugin;
use Spryker\Zed\MerchantSearch\MerchantSearchDependencyProvider as SprykerMerchantSearchDependencyProvider;

class MerchantSearchDependencyProvider extends SprykerMerchantSearchDependencyProvider
{
    /**
     * @return array<\Spryker\Zed\MerchantSearchExtension\Dependency\Plugin\MerchantSearchDataExpanderPluginInterface>
     */
    protected function getMerchantSearchDataExpanderPlugins(): array
    {
        return [
            new MerchantCategoryMerchantSearchDataExpanderPlugin(),
        ];
    }
}
