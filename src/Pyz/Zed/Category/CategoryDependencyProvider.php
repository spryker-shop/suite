<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Category;

use Spryker\Zed\Category\CategoryDependencyProvider as SprykerDependencyProvider;
use Spryker\Zed\Category\Communication\Plugin\CategoryUrlPathPrefixUpdaterPlugin;
use Spryker\Zed\CategoryImage\Communication\Plugin\CategoryAfterCreatePlugin;
use Spryker\Zed\CategoryImage\Communication\Plugin\CategoryAfterUpdatePlugin;
use Spryker\Zed\CategoryImage\Communication\Plugin\CategoryReadPlugin;
use Spryker\Zed\CategoryImageGui\Communication\Form\CategoryImageFormPlugin;
use Spryker\Zed\CategoryNavigationConnector\Communication\Plugin\UpdateNavigationRelationPlugin;
use Spryker\Zed\CmsBlockCategoryConnector\Communication\Plugin\CategoryFormPlugin;
use Spryker\Zed\CmsBlockCategoryConnector\Communication\Plugin\ReadCmsBlockCategoryRelationsPlugin;
use Spryker\Zed\ProductCategory\Communication\Plugin\ReadProductCategoryRelationPlugin;
use Spryker\Zed\ProductCategory\Communication\Plugin\RemoveProductCategoryRelationPlugin;
use Spryker\Zed\ProductCategory\Communication\Plugin\UpdateProductCategoryRelationPlugin;

class CategoryDependencyProvider extends SprykerDependencyProvider
{
    /**
     * @return \Spryker\Zed\Category\Dependency\Plugin\CategoryRelationDeletePluginInterface[]
     */
    protected function getRelationDeletePluginStack()
    {
        $deletePlugins = array_merge(
            [
                new RemoveProductCategoryRelationPlugin(),
            ],
            parent::getRelationDeletePluginStack()
        );

        return $deletePlugins;
    }

    /**
     * @return \Spryker\Zed\Category\Dependency\Plugin\CategoryRelationUpdatePluginInterface[]
     */
    protected function getRelationUpdatePluginStack()
    {
        return array_merge(
            [
                new UpdateProductCategoryRelationPlugin(),
                new CategoryFormPlugin(),
                new UpdateNavigationRelationPlugin(),
            ],
            parent::getRelationUpdatePluginStack()
        );
    }

    /**
     * @return \Spryker\Zed\Category\Dependency\Plugin\CategoryRelationReadPluginInterface[]
     */
    protected function getRelationReadPluginStack()
    {
        $readPlugins = array_merge(
            [
                new ReadProductCategoryRelationPlugin(),
                new ReadCmsBlockCategoryRelationsPlugin(),
            ],
            parent::getRelationReadPluginStack()
        );

        return $readPlugins;
    }

    /**
     * @return \Spryker\Zed\CategoryExtension\Dependency\Plugin\CategoryTransferExpanderPluginInterface[]
     */
    protected function getCategoryPostReadPlugins(): array
    {
        return [
            new CategoryReadPlugin(),
        ];
    }

    /**
     * @return array
     */
    protected function getCategoryFormPlugins()
    {
        return array_merge(parent::getCategoryFormPlugins(), [
            new CategoryFormPlugin(),
            new CategoryImageFormPlugin(),
        ]);
    }

    /**
     * @return \Spryker\Zed\Category\Dependency\Plugin\CategoryUrlPathPluginInterface[]
     */
    protected function getCategoryUrlPathPlugins()
    {
        return [
            new CategoryUrlPathPrefixUpdaterPlugin(),
        ];
    }

    /**
     * @return \Spryker\Zed\CategoryExtension\Dependency\Plugin\CategoryUpdateAfterPluginInterface[]
     */
    protected function getCategoryPostUpdatePlugins(): array
    {
        return [
            new CategoryAfterUpdatePlugin(),
        ];
    }

    /**
     * @return \Spryker\Zed\CategoryExtension\Dependency\Plugin\CategoryCreateAfterPluginInterface[]
     */
    protected function getCategoryPostCreatePlugins(): array
    {
        return [
            new CategoryAfterCreatePlugin(),
        ];
    }
}
