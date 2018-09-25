<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\RestApiDocumentationGenerator;

use Spryker\Shared\Kernel\KernelConstants;
use Spryker\Zed\RestApiDocumentationGenerator\RestApiDocumentationGeneratorConfig as SprykerRestApiDocumentationGeneratorConfig;

class RestApiDocumentationGeneratorConfig extends SprykerRestApiDocumentationGeneratorConfig
{
    protected const APPLICATION_ANNOTATIONS_SOURCE_DIRECTORY_PATTERN = '/Glue/%1$s/Controller/';
    protected const CORE_ANNOTATIONS_SOURCE_DIRECTORY_PATTERN = '/spryker/spryker/Bundles/%1$s/src/Spryker/Glue/%1$s/Controller/';

    /**
     * @return array
     */
    protected function getCoreAnnotationsSourceDirectoryGlobPatterns(): array
    {
        return [
            APPLICATION_VENDOR_DIR . static::CORE_ANNOTATIONS_SOURCE_DIRECTORY_PATTERN,
        ];
    }

    /**
     * @return array
     */
    protected function getApplicationAnnotationsSourceDirectoryGlobPattern(): array
    {
        return [
            APPLICATION_SOURCE_DIR . '/' . $this->get(KernelConstants::PROJECT_NAMESPACE) . static::APPLICATION_ANNOTATIONS_SOURCE_DIRECTORY_PATTERN,
        ];
    }
}
