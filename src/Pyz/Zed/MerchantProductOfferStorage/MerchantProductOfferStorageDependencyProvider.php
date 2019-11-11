<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\MerchantProductOfferStorage;

use Spryker\Zed\MerchantProductOfferStorage\MerchantProductOfferStorageDependencyProvider as SprykerMerchantProductOfferStorageDependencyProvider;
use Spryker\Zed\PriceProductOfferStorage\Communication\Plugin\MerchantProductOfferStorage\MerchantProductOfferStorageExpanderPlugin;

/**
 * @method \Spryker\Zed\MerchantProductOfferStorage\MerchantProductOfferStorageConfig getConfig()
 */
class MerchantProductOfferStorageDependencyProvider extends SprykerMerchantProductOfferStorageDependencyProvider
{
    /**
     * @return \Spryker\Zed\MerchantProductOfferStorageExtension\Dependency\Plugin\MerchantProductOfferStorageExpanderPluginInterface[]
     */
    protected function getMerchantProductOfferStorageExpanderPlugins()
    {
        return [
            new MerchantProductOfferStorageExpanderPlugin(),
        ];
    }
}
