<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Pyz\Zed\TaxStorage;

use Pyz\Zed\Synchronization\SynchronizationConfig;
use Spryker\Zed\TaxStorage\TaxStorageConfig as SprykerTaxStorageConfig;

class TaxStorageConfig extends SprykerTaxStorageConfig
{
    /**
     * @return string|null
     */
    public function getTaxSynchronizationPoolName(): ?string
    {
        return SynchronizationConfig::DEFAULT_SYNCHRONIZATION_POOL_NAME;
    }
}
