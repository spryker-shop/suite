<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\RestApiDocumentationGenerator;

use Spryker\Zed\RestApiDocumentationGenerator\RestApiDocumentationGeneratorConfig as SprykerRestApiDocumentationGeneratorConfig;

class RestApiDocumentationGeneratorConfig extends SprykerRestApiDocumentationGeneratorConfig
{
    protected const APPLICATION_VENDOR_ANNOTATIONS_SOURCE_DIRECTORY_PATTERN = '/spryker/spryker/Bundles/%1$s/src/Spryker/Glue/%1$s/Controller/';

    /**
     * @project Only needed internal non-split project, not in public split project.
     *
     * @return array
     */
    protected function getCoreAnnotationsSourceDirectoryPatterns(): array
    {
        $directoryPatterns = parent::getCoreAnnotationsSourceDirectoryPatterns();
        $directoryPatterns[] = APPLICATION_VENDOR_DIR . static::APPLICATION_VENDOR_ANNOTATIONS_SOURCE_DIRECTORY_PATTERN;

        return $directoryPatterns;
    }
}
