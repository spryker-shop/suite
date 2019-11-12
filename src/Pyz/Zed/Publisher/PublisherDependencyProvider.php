<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Publisher;

use Pyz\Zed\GlossaryStorage\Communication\Plugin\Publisher\GlossaryStoragePublisherRegistryPlugin;
use Spryker\Zed\GlossaryStorage\Communication\Plugin\Publisher\GlossaryPublisherDataPlugin;
use Spryker\Zed\Publisher\PublisherDependencyProvider as SprykerPublisherDependencyProvider;

class PublisherDependencyProvider extends SprykerPublisherDependencyProvider
{
    /**
     * @return \Spryker\Zed\PublisherExtension\Dependency\Plugin\PublisherDataPluginInterface[]
     */
    public function getPublisherDataPluginsForEventBehavior()
    {
        return $this->getPublisherDataPlugins();
    }

    /**
     * @return \Spryker\Zed\PublisherExtension\Dependency\Plugin\PublisherRegistryPluginInterface[]
     */
    protected function getPublisherRegistryPlugins(): array
    {
        return [
            new GlossaryStoragePublisherRegistryPlugin(),
        ];
    }

    /**
     * @return \Spryker\Zed\PublisherExtension\Dependency\Plugin\PublisherDataPluginInterface[]
     */
    protected function getPublisherDataPlugins(): array
    {
        return [
            new GlossaryPublisherDataPlugin(),
        ];
    }
}
