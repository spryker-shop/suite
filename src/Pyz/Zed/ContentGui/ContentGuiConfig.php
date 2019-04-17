<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ContentGui;

use Spryker\Shared\ContentBannerGui\ContentBannerGuiConfig;
use Spryker\Zed\ContentGui\ContentGuiConfig as SprykerContentGuiConfig;

class ContentGuiConfig extends SprykerContentGuiConfig
{
    /**
     * @return array
     */
    public function getEnabledContentTypesForEditor(): array
    {
        return [
            ContentBannerGuiConfig::CONTENT_TYPE_BANNER,
        ];
    }
}
