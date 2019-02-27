<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Glue\NavigationsRestApi;

use Spryker\Glue\NavigationsRestApi\NavigationsRestApiDependencyProvider as SprykerNavigationsRestApiDependencyProvider;
use Spryker\Glue\UrlsRestApi\Plugin\CategoryNodeNavigationsResourceExpanderPlugin;

class NavigationsRestApiDependencyProvider extends SprykerNavigationsRestApiDependencyProvider
{
    /**
     * @return \Spryker\Glue\NavigationsRestApiExtension\Dependency\Plugin\NavigationsResourceExpanderPluginInterface[]
     */
    protected function getNavigationsResourceExpanderPlugins(): array
    {
        return [
            new CategoryNodeNavigationsResourceExpanderPlugin(),
        ];
    }
}
