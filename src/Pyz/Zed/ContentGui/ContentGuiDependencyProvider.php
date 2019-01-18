<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ContentGui;

use Spryker\Zed\ContentGui\ContentGuiDependencyProvider as SprykerContentGuiDependencyProvider;
use Spryker\Zed\ContentProductConnector\Communication\Plugin\ContentProductConnectorPlugin;

class ContentGuiDependencyProvider extends SprykerContentGuiDependencyProvider
{
    /**
     * @return \Spryker\Zed\ContentGuiExtension\Plugin\ContentPluginInterface[]
     */
    protected function getContentPlugins(): array
    {
        return [
            new ContentProductConnectorPlugin(),
        ];
    }
}
