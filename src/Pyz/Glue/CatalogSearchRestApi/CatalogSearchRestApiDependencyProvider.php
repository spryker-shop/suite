<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Glue\CatalogSearchRestApi;

use Spryker\Glue\CatalogSearchRestApi\CatalogSearchRestApiDependencyProvider as SprykerCatalogSearchRestApiDependencyProvider;
use Spryker\Glue\CatalogSearchRestApi\Plugin\CatalogSearchRequestValidatorPlugin;

class CatalogSearchRestApiDependencyProvider extends SprykerCatalogSearchRestApiDependencyProvider
{
    /**
     * @return \Spryker\Glue\CatalogSearchRestApiExtension\Dependency\Plugin\CatalogSearchRequestValidatorPluginInterface[]
     */
    protected function getCatalogSearchRequestValidatorPlugins(): array
    {
        return [
            new CatalogSearchRequestValidatorPlugin(),
        ];
    }
}
