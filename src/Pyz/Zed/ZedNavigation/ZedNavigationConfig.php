<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ZedNavigation;

use Spryker\Zed\ZedNavigation\ZedNavigationConfig as SprykerZedNavigationConfig;

class ZedNavigationConfig extends SprykerZedNavigationConfig
{
    /**
     * @uses \Spryker\Zed\MerchantPortalApplication\Communication\Plugin\Twig\MerchantNavigationTypeTwigPlugin::NAVIGATION_TYPE_MAIN_MERCHANT_PORTAL
     *
     * @var string
     */
    protected const NAVIGATION_TYPE_MAIN_MERCHANT_PORTAL = 'main-merchant-portal';

    /**
     * @uses \Spryker\Zed\MerchantPortalApplication\Communication\Plugin\Twig\MerchantNavigationTypeTwigPlugin::NAVIGATION_TYPE_SECONDARY_MERCHANT_PORTAL
     *
     * @var string
     */
    protected const NAVIGATION_TYPE_SECONDARY_MERCHANT_PORTAL = 'secondary-merchant-portal';

    /**
     * @project Only needed in Project, not in demoshop
     *
     * @return array<string>
     */
    public function getNavigationSchemaPathPattern(): array
    {
        $paths = parent::getNavigationSchemaPathPattern();
        $paths = $this->addSprykerFilePath($paths);

        return $paths;
    }

    /**
     * @project Only needed in Project, not in demoshop
     *
     * @param array<string> $paths
     *
     * @return array<string>
     */
    private function addSprykerFilePath($paths): array
    {
        $paths[] = APPLICATION_VENDOR_DIR . '/spryker/spryker/Bundles/*/src/*/Zed/*/Communication';

        return $paths;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getMergeStrategy(): string
    {
        return static::BREADCRUMB_MERGE_STRATEGY;
    }

    /**
     * @return array<string>
     */
    public function getCacheFilePaths(): array
    {
        $cacheFilePaths = parent::getCacheFilePaths();
        $cacheFilePaths[static::NAVIGATION_TYPE_MAIN_MERCHANT_PORTAL] = $this->getCacheDirName() . 'navigation-main-merchant-portal.cache';
        $cacheFilePaths[static::NAVIGATION_TYPE_SECONDARY_MERCHANT_PORTAL] = $this->getCacheDirName() . 'navigation-secondary-merchant-portal.cache';

        return $cacheFilePaths;
    }

    /**
     * @return array<string>
     */
    public function getRootNavigationSchemaPaths(): array
    {
        $rootNavigationSchemaPaths = parent::getRootNavigationSchemaPaths();
        $rootNavigationSchemaPaths[static::NAVIGATION_TYPE_MAIN_MERCHANT_PORTAL] = $this->getRootNavigationSchemasDirName() . 'navigation-main-merchant-portal.xml';
        $rootNavigationSchemaPaths[static::NAVIGATION_TYPE_SECONDARY_MERCHANT_PORTAL] = $this->getRootNavigationSchemasDirName() . 'navigation-secondary-merchant-portal.xml';

        return $rootNavigationSchemaPaths;
    }

    /**
     * @return array<string>
     */
    public function getNavigationSchemaFileNamePatterns(): array
    {
        $navigationSchemaFileNamePatterns = parent::getNavigationSchemaFileNamePatterns();
        $navigationSchemaFileNamePatterns[static::NAVIGATION_TYPE_MAIN_MERCHANT_PORTAL] = 'navigation-main-merchant-portal.xml';
        $navigationSchemaFileNamePatterns[static::NAVIGATION_TYPE_SECONDARY_MERCHANT_PORTAL] = 'navigation-secondary-merchant-portal.xml';

        return $navigationSchemaFileNamePatterns;
    }
}
