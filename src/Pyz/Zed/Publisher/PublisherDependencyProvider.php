<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Publisher;

use Spryker\Zed\GlossaryStorage\Communication\Plugin\Publisher\GlossaryStoragePublisherRegistryPlugin;
use Spryker\Zed\Publisher\PublisherDependencyProvider as SprykerPublisherDependencyProvider;

class PublisherDependencyProvider extends SprykerPublisherDependencyProvider
{
    /**
     * @return \Spryker\Zed\PublisherExtension\Dependency\Plugin\PublisherRegistryPluginInterface[]
     */
    public function getPublisherRegistryPlugins(): array
    {
        return [
            new GlossaryStoragePublisherRegistryPlugin(),
        ];
    }
}
