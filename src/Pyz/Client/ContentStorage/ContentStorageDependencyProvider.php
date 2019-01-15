<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\ContentStorage;

use Spryker\Client\ContentProductConnector\Plugin\AbstractProductListTermPlugin;
use Spryker\Client\ContentStorage\ContentStorageDependencyProvider as SprykerContentStorageDependencyProvider;

class ContentStorageDependencyProvider extends SprykerContentStorageDependencyProvider
{
    /**
     * @return \Spryker\Client\ContentStorageExtension\Plugin\ContentTermExecutorPluginInterface[]
     */
    protected function getContentItemPlugins(): array
    {
        return [
            new AbstractProductListTermPlugin(),
        ];
    }
}
