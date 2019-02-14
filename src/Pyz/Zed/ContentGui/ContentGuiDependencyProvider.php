<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ContentGui;

use Spryker\Zed\ContentBannerGui\Communication\Plugin\ContentGui\ContentBannerFormPlugin;
use Spryker\Zed\ContentGui\ContentGuiDependencyProvider as SprykerContentGuiDependencyProvider;

class ContentGuiDependencyProvider extends SprykerContentGuiDependencyProvider
{
    /**
     * @return \Spryker\Zed\ContentGuiExtension\Plugin\ContentPluginInterface[]
     */
    protected function getContentPlugins(): array
    {
        return [
            new ContentBannerFormPlugin(),
        ];
    }
}
