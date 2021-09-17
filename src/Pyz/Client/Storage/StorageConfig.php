<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\Storage;

use Spryker\Client\Storage\StorageConfig as SprykerStorageClientConfig;

class StorageConfig extends SprykerStorageClientConfig
{
    /**
     * @return array<string>
     */
    public function getAllowedGetParametersList(): array
    {
        return [
            'page',
            'sort',
            'ipp',
            'q',
        ];
    }
}
