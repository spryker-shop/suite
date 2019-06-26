<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Pyz\Zed\FileManagerStorage;

use Pyz\Zed\Synchronization\SynchronizationConfig;
use Spryker\Zed\FileManagerStorage\FileManagerStorageConfig as SprykerFileManagerStorageConfig;

class FileManagerStorageConfig extends SprykerFileManagerStorageConfig
{
    /**
     * @return string|null
     */
    public function getFileManagerSynchronizationPoolName(): ?string
    {
        return SynchronizationConfig::DEFAULT_SYNCHRONIZATION_POOL_NAME;
    }
}
