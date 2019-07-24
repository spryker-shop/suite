<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Glue\UrlIdentifiersRestApi;

use Spryker\Glue\CategoriesRestApi\Plugin\UrlIdentifiersRestApi\CategoryNodeResourceIdentifierProviderPlugin;
use Spryker\Glue\ProductsRestApi\Plugin\UrlIdentifiersRestApi\ProductAbstractResourceIdentifierProviderPlugin;
use Spryker\Glue\UrlIdentifiersRestApi\UrlIdentifiersRestApiDependencyProvider as SprykerUrlIdentifiersRestApiDependencyProvider;

class UrlIdentifiersRestApiDependencyProvider extends SprykerUrlIdentifiersRestApiDependencyProvider
{
    /**
     * @return \Spryker\Glue\UrlIdentifiersRestApiExtension\Dependency\Plugin\ResourceIdentifierProviderPluginInterface[]
     */
    protected function getResourceIdentifierProviderPlugins(): array
    {
        return [
            new ProductAbstractResourceIdentifierProviderPlugin(),
            new CategoryNodeResourceIdentifierProviderPlugin(),
        ];
    }
}
