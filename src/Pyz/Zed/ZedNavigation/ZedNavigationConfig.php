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
     * @project Only needed in Project, not in demoshop
     *
     * @return string[]
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
     * @param string[] $paths
     *
     * @return string[]
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
}
