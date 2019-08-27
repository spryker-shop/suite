<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\CmsBlockStorage;

use Spryker\Client\CmsBlockStorage\CmsBlockStorageDependencyProvider as SprykerCmsBlockStorageDependencyProvider;
use Spryker\Zed\CmsBlockCategoryStorage\Communication\Plugin\CmsBlockStorage\CmsBlockCategoryCmsBlockStorageRelatedBlocksFinderPlugin;
use Spryker\Zed\CmsBlockProductStorage\Communication\Plugin\CmsBlockStorage\CmsBlockProductCmsBlockStorageRelatedBlocksFinderPlugin;

class CmsBlockStorageDependencyProvider extends SprykerCmsBlockStorageDependencyProvider
{
    /**
     * @return \Spryker\Zed\CmsBlockStorageExtension\Dependency\Plugin\CmsBlockStorageRelatedBlocksFinderPluginInterface[]
     */
    protected function getCmsBlockStorageRelatedBlocksFinderPlugins(): array
    {
        return [
            new CmsBlockCategoryCmsBlockStorageRelatedBlocksFinderPlugin(),
            new CmsBlockProductCmsBlockStorageRelatedBlocksFinderPlugin(),
        ];
    }
}
