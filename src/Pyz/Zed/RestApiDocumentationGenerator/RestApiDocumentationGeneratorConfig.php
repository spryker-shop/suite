<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\RestApiDocumentationGenerator;

use Spryker\Zed\RestApiDocumentationGenerator\RestApiDocumentationGeneratorConfig as SprykerRestApiDocumentationGeneratorConfig;

class RestApiDocumentationGeneratorConfig extends SprykerRestApiDocumentationGeneratorConfig
{
    protected const APPLICATION_ANNOTATIONS_SOURCE_DIRECTORY_PATTERN = APPLICATION_SOURCE_DIR . '/*/Glue/*/Controller/';
    protected const CORE_ANNOTATIONS_SOURCE_DIRECTORY_PATTERN = APPLICATION_VENDOR_DIR . '/spryker/spryker/Bundles/*/src/Spryker/Glue/*/Controller/';

    /**
     * @return array
     */
    protected function getCoreAnnotationsSourceDirectoryGlobPatterns(): array
    {
        return [
            static::CORE_ANNOTATIONS_SOURCE_DIRECTORY_PATTERN,
        ];
    }

    /**
     * @return array
     */
    protected function getApplicationAnnotationsSourceDirectoryGlobPattern(): array
    {
        return [
            static::APPLICATION_ANNOTATIONS_SOURCE_DIRECTORY_PATTERN,
        ];
    }
}
