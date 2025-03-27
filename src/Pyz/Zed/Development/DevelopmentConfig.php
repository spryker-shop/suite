<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Pyz\Zed\Development;

use Spryker\Zed\Development\DevelopmentConfig as SprykerDevelopmentConfig;

class DevelopmentConfig extends SprykerDevelopmentConfig
{
    /**
     * @project Only needed in Project, not in demoshop
     *
     * @phpstan-var array<string, string>
     *
     * @var array<string>
     */
    protected const INTERNAL_NAMESPACES_TO_PATH_MAPPING = [
        self::NAMESPACE_SPRYKER => APPLICATION_ROOT_DIR . DIRECTORY_SEPARATOR . 'vendor/spryker/spryker/Bundles/',
        self::NAMESPACE_SPRYKER_SHOP => APPLICATION_ROOT_DIR . DIRECTORY_SEPARATOR . 'vendor/spryker/spryker-shop/Bundles/',
    ];

    /**
     * @project Only needed in Project, not in demoshop
     *
     * @phpstan-return array<string, string>
     *
     * @return array<string>
     */
    public function getIdeAutoCompletionSourceDirectoryGlobPatterns(): array
    {
        $globPatterns = parent::getIdeAutoCompletionSourceDirectoryGlobPatterns();
        $globPatterns[APPLICATION_VENDOR_DIR . '/spryker/spryker/Bundles/*/src/'] = 'Spryker/*/';
        $globPatterns[APPLICATION_VENDOR_DIR . '/spryker/spryker/Features/*/src/'] = 'SprykerFeature/*/';

        return $globPatterns;
    }

    /**
     * @project Only needed in Project, not in demoshop
     *
     * @return string
     */
    public function getPathToCore(): string
    {
        return $this->getPathToRoot() . 'vendor/spryker/spryker/Bundles/';
    }

    /**
     * @project Only needed in Project, not in demoshop
     *
     * @return string
     */
    public function getPathToFeature(): string
    {
        return $this->getPathToRoot() . 'vendor/spryker/spryker/Features/';
    }

    /**
     * @project Only needed in Project, not in demoshop
     *
     * @return string
     */
    public function getPathToShop(): string
    {
        return $this->getPathToRoot() . 'vendor/spryker/spryker-shop/Bundles/';
    }

    /**
     * @return string
     */
    public function getCodingStandard(): string
    {
        return APPLICATION_ROOT_DIR . DIRECTORY_SEPARATOR . 'phpcs.xml';
    }

    /**
     * @param string $namespace
     *
     * @return string|null
     */
    public function getPathToInternalNamespace(string $namespace): ?string
    {
        $pathToSprykerRoot = $this->checkPathToSprykerRoot($namespace);
        if ($pathToSprykerRoot) {
            return $pathToSprykerRoot;
        }

        if (array_key_exists($namespace, $this->getPathsToInternalNamespace())) {
            $mergedInternalNamespacesToPathMapping = static::INTERNAL_NAMESPACES_TO_PATH_MAPPING + parent::INTERNAL_NAMESPACES_TO_PATH_MAPPING;
            $mergedInternalNamespacesToPathMapping[self::NAMESPACE_SPRYKER_FEATURE] = APPLICATION_ROOT_DIR . DIRECTORY_SEPARATOR . 'vendor/spryker/spryker/Features/';

            return $mergedInternalNamespacesToPathMapping[$namespace];
        }

        return null;
    }

    /**
     * @return array<string>
     */
    public function getPathsToInternalNamespace(): array
    {
        $pathToSprykerRoot = $this->checkPathToSprykerRoot(static::NAMESPACE_SPRYKER);
        $sprykerNamespacePath = $pathToSprykerRoot ? [static::NAMESPACE_SPRYKER => $pathToSprykerRoot] : [];

        $pathsToInternalNamespace = $sprykerNamespacePath + static::INTERNAL_NAMESPACES_TO_PATH_MAPPING + parent::INTERNAL_NAMESPACES_TO_PATH_MAPPING;
        $pathsToInternalNamespace[self::NAMESPACE_SPRYKER_FEATURE] = APPLICATION_ROOT_DIR . DIRECTORY_SEPARATOR . 'vendor/spryker/spryker/Features/';

        return $pathsToInternalNamespace;
    }

    /**
     * @return array<string>
     */
    public function getOrganizationPathMap(): array
    {
        return [
            'Spryker' => $this->getPathToCore(),
            'SprykerFeature' => $this->getPathToFeature(),
            'SprykerEco' => $this->getPathToEco(),
        ];
    }
}
