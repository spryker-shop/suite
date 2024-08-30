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
