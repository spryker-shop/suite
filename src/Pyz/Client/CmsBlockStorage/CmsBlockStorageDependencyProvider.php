<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\CmsBlockStorage;

use Spryker\Client\CmsBlockCategoryStorage\Plugin\CmsBlockStorage\CmsBlockCategoryCmsBlockStorageBlocksFinderPlugin;
use Spryker\Client\CmsBlockProductStorage\Plugin\CmsBlockStorage\CmsBlockProductCmsBlockStorageBlocksFinderPlugin;
use Spryker\Client\CmsBlockStorage\CmsBlockStorageDependencyProvider as SprykerCmsBlockStorageDependencyProvider;

class CmsBlockStorageDependencyProvider extends SprykerCmsBlockStorageDependencyProvider
{
    /**
     * @return \Spryker\Client\CmsBlockStorageExtension\Dependency\Plugin\CmsBlockStorageBlocksFinderPluginInterface[]
     */
    protected function getCmsBlockStorageBlocksFinderPlugins(): array
    {
        return [
            new CmsBlockCategoryCmsBlockStorageBlocksFinderPlugin(),
            new CmsBlockProductCmsBlockStorageBlocksFinderPlugin(),
        ];
    }
}
