<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Merchant;

use Spryker\Zed\Merchant\MerchantDependencyProvider as SprykerMerchantDependencyProvider;
use Spryker\Zed\MerchantProfile\Communication\Plugin\Merchant\MerchantProfileHydratePlugin;
use Spryker\Zed\MerchantProfile\Communication\Plugin\Merchant\MerchantProfilePostSavePlugin;

class MerchantDependencyProvider extends SprykerMerchantDependencyProvider
{
    /**
     * @return \Spryker\Zed\MerchantExtension\Dependency\Plugin\MerchantPostSavePluginInterface[]
     */
    protected function getMerchantPostSavePlugins(): array
    {
        return [
            new MerchantProfilePostSavePlugin(),
        ];
    }

    /**
     * @return \Spryker\Zed\MerchantExtension\Dependency\Plugin\MerchantHydrationPluginInterface[]
     */
    protected function getMerchantHydratePlugins(): array
    {
        return [
            new MerchantProfileHydratePlugin(),
        ];
    }
}
