<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\ProductConfigurationStorage;

use Spryker\Client\ProductConfigurationStorage\Plugin\ProductConfigurationStorageVolumePriceExtractorPlugin;
use Spryker\Client\ProductConfigurationStorage\ProductConfigurationStorageDependencyProvider as SprykerProductConfigurationStorageDependencyProvider;

class ProductConfigurationStorageDependencyProvider extends SprykerProductConfigurationStorageDependencyProvider
{
    /**
     * @return \Spryker\Client\ProductConfigurationStorageExtension\Dependency\Plugin\ProductConfigurationStoragePriceExtractorPluginInterface[]
     */
    protected function getProductConfigurationStoragePriceExtractorPlugins(): array
    {
        return [
            new ProductConfigurationStorageVolumePriceExtractorPlugin(),
        ];
    }
}
