<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\StorageDatabase;

use Spryker\Client\StorageDatabase\Plugin\PostgreSqlStorageReaderProviderPlugin;
use Spryker\Client\StorageDatabase\StorageDatabaseDependencyProvider as SprykerStorageDatabaseDependencyProvider;
use Spryker\Client\StorageDatabaseExtension\Dependency\Plugin\StorageReaderProviderPluginInterface;

class StorageDatabaseDependencyProvider extends SprykerStorageDatabaseDependencyProvider
{
    /**
     * @return \Spryker\Client\StorageDatabaseExtension\Dependency\Plugin\StorageReaderProviderPluginInterface|null
     */
    protected function getStorageReaderProviderPlugin(): ?StorageReaderProviderPluginInterface
    {
        return new PostgreSqlStorageReaderProviderPlugin();
    }
}
