<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Pyz\Zed\Twig;

use Spryker\Zed\Twig\TwigConfig as SprykerTwigConfig;

class TwigConfig extends SprykerTwigConfig
{
    /**
     * @project Only needed in Project, not in demoshop
     *
     * @param array<string> $paths
     *
     * @return array<string>
     */
    protected function addCoreTemplatePaths(array $paths): array
    {
        $paths = parent::addCoreTemplatePaths($paths);
        $paths[] = $this->getBundlesDirectory() . '/%2$s/src/Spryker/Zed/%1$s/Presentation/';

        return $paths;
    }

    /**
     * @project Only needed in Project, not in demoshop
     *
     * @return array<string>
     */
    public function getZedDirectoryPathPatterns(): array
    {
        $directories = array_merge(
            glob('vendor/spryker/spryker/Bundles/*/src/*/Zed/*/Presentation', GLOB_NOSORT | GLOB_ONLYDIR) ?: [],
            glob('vendor/spryker/spryker/Features/*/src/*/Zed/*/Presentation', GLOB_NOSORT | GLOB_ONLYDIR) ?: [],
            glob('vendor/spryker/spryker-shop/Bundles/*/src/*/Zed/*/Presentation', GLOB_NOSORT | GLOB_ONLYDIR) ?: [],
        );

        $directories = array_merge(
            $directories,
            parent::getZedDirectoryPathPatterns(),
        );

        sort($directories);

        return $directories;
    }

    /**
     * @project Only needed in Project, not in demoshop
     *
     * @return array<string>
     */
    public function getYvesDirectoryPathPatterns(): array
    {
        return array_merge(
            $this->getPyzYvesDirectoryPathPatterns(),
            parent::getYvesDirectoryPathPatterns(),
        );
    }

    /**
     * @project Only needed in Project, not in demoshop
     *
     * @return array<string>
     */
    protected function getPyzYvesDirectoryPathPatterns(): array
    {
        $themeNameDefault = $this->getSharedConfig()->getYvesThemeNameDefault();

        $bundleDirectories = glob(APPLICATION_VENDOR_DIR . '/*/*/Bundles/*/src/*/Yves/*/Theme/' . $themeNameDefault, GLOB_NOSORT | GLOB_ONLYDIR) ?: [];
        $featuresDirectories = glob(APPLICATION_VENDOR_DIR . '/*/*/Features/*/src/*/Yves/*/Theme/' . $themeNameDefault, GLOB_NOSORT | GLOB_ONLYDIR) ?: [];

        return array_merge($bundleDirectories, $featuresDirectories);
    }
}
