<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ContentGui;

use Spryker\Shared\ContentBanner\ContentBannerConfig;
use Spryker\Shared\ContentProduct\ContentProductConfig;
use Spryker\Zed\ContentGui\ContentGuiConfig as SprykerContentGuiConfig;

class ContentGuiConfig extends SprykerContentGuiConfig
{
    /**
     * @return array
     */
    public function getEnabledContentTypesForEditor(): array
    {
        return [
            ContentBannerConfig::CONTENT_TYPE_BANNER,
            ContentProductConfig::CONTENT_TYPE_PRODUCT_ABSTRACT_LIST,
        ];
    }
}
