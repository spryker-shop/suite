<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\MerchantProfileStorage;

use Pyz\Zed\Synchronization\SynchronizationConfig;
use Spryker\Zed\MerchantProfileStorage\MerchantProfileStorageConfig as SpykerMerchantProfileStorageConfig;

class MerchantProfileStorageConfig extends SpykerMerchantProfileStorageConfig
{
    /**
     * @return string|null
     */
    public function getMerchantProfileSynchronizationPoolName(): ?string
    {
        return SynchronizationConfig::DEFAULT_SYNCHRONIZATION_POOL_NAME;
    }
}
