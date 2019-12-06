<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\MerchantProductOfferStorage;

use Pyz\Zed\Synchronization\SynchronizationConfig;
use Spryker\Zed\MerchantProductOfferStorage\MerchantProductOfferStorageDependencyProvider as SprykerMerchantProductOfferStorageDependencyProvider;

class MerchantProductOfferStorageDependencyProvider extends SprykerMerchantProductOfferStorageDependencyProvider
{
    /**
     * @return array
     */
    protected function getMerchantProductOfferStoragePrePublishPlugins(): array
    {
        return [

        ];
    }
}
