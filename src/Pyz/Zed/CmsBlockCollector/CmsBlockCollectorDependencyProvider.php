<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\CmsBlockCollector;

use Spryker\Zed\CmsBlockCollector\CmsBlockCollectorDependencyProvider as SprykerCmsBlockCollectorDependencyProvider;
use Spryker\Zed\CmsContentWidget\Communication\Plugin\CmsBlockCollector\CmsBlockCollectorParameterMapExpanderPlugin;

class CmsBlockCollectorDependencyProvider extends SprykerCmsBlockCollectorDependencyProvider
{
    /**
     * Stack of plugins which run during data collection for each item.
     *
     * @return array<\Spryker\Zed\CmsBlockCollector\Dependency\Plugin\CmsBlockCollectorDataExpanderPluginInterface>
     */
    protected function getCollectorDataExpanderPlugins(): array
    {
        return [
             new CmsBlockCollectorParameterMapExpanderPlugin(),
        ];
    }
}
