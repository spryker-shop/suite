<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\CmsSearchPage;

use SprykerShop\Yves\CmsSearchPage\CmsSearchPageDependencyProvider as SprykerCmsSearchPageDependencyProvider;
use SprykerShop\Yves\TabsWidget\Plugin\ShopApplication\FullTextSearchTabsWidgetPlugin;

/**
 * Class CmsSearchPageDependencyProvider
 * @package Pyz\Yves\CmsSearchPage
 */
class CmsSearchPageDependencyProvider extends SprykerCmsSearchPageDependencyProvider
{
    /**
     * @return string[]
     */
    protected function getCmsSearchPageWidgetPlugins(): array
    {
        return [
            FullTextSearchTabsWidgetPlugin::class,
        ];
    }
}
