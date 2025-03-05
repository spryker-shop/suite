<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Pyz\Zed\Sitemap;

use Spryker\Zed\CategoryStorage\Communication\Plugin\Sitemap\CategoryNodeSitemapDataProviderPlugin;
use Spryker\Zed\CmsStorage\Communication\Plugin\Sitemap\CmsPageSitemapDataProviderPlugin;
use Spryker\Zed\MerchantStorage\Communication\Plugin\Sitemap\MerchantSitemapDataProviderPlugin;
use Spryker\Zed\ProductSetStorage\Communication\Plugin\Sitemap\ProductSetSitemapDataProviderPlugin;
use Spryker\Zed\ProductStorage\Communication\Plugin\Sitemap\ProductAbstractSitemapDataProviderPlugin;
use Spryker\Zed\Sitemap\SitemapDependencyProvider as SprykerSitemapDependencyProvider;

class SitemapDependencyProvider extends SprykerSitemapDependencyProvider
{
    /**
     * @return array<\Spryker\Zed\SitemapExtension\Dependency\Plugin\SitemapDataProviderPluginInterface>
     */
    protected function getSitemapDataProviderPlugins(): array
    {
        return [
            new ProductAbstractSitemapDataProviderPlugin(),
            new CategoryNodeSitemapDataProviderPlugin(),
            new CmsPageSitemapDataProviderPlugin(),
            new ProductSetSitemapDataProviderPlugin(),
            new MerchantSitemapDataProviderPlugin(),
        ];
    }
}
