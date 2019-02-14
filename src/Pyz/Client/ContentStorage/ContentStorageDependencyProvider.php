<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\ContentStorage;

use Spryker\Client\ContentBanner\Plugin\ContentStorage\BannerTermExecutorPlugin;
use Spryker\Client\ContentStorage\ContentStorageDependencyProvider as SprykerContentStorageDependencyProvider;

class ContentStorageDependencyProvider extends SprykerContentStorageDependencyProvider
{
    /**
     * @return \Spryker\Client\ContentStorageExtension\Plugin\ContentTermExecutorPluginInterface[]
     */
    protected function getContentPlugins(): array
    {
        return [
            new BannerTermExecutorPlugin(),
        ];
    }
}
