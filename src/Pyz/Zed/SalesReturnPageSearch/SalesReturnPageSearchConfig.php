<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\SalesReturnPageSearch;

use Pyz\Zed\Synchronization\SynchronizationConfig;
use Spryker\Zed\SalesReturnPageSearch\SalesReturnPageSearchConfig as SprykerSalesReturnPageSearchConfig;

class SalesReturnPageSearchConfig extends SprykerSalesReturnPageSearchConfig
{
    /**
     * @return string|null
     */
    public function getReturnReasonSearchSynchronizationPoolName(): ?string
    {
        return SynchronizationConfig::DEFAULT_SYNCHRONIZATION_POOL_NAME;
    }
}
