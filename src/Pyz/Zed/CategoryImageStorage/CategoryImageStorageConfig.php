<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\CategoryImageStorage;

use Pyz\Zed\Synchronization\SynchronizationConfig;
use Spryker\Zed\CategoryImageStorage\CategoryImageStorageConfig as SprykerCategoryImageSTorageConfig;

class CategoryImageStorageConfig extends SprykerCategoryImageSTorageConfig
{
    /**
     * @return string|null
     */
    public function getCategoryImageSynchronizationPoolName(): ?string
    {
        return SynchronizationConfig::DEFAULT_SYNCHRONIZATION_POOL_NAME;
    }
}
