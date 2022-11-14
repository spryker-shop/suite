<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Glue\DocumentationGeneratorOpenApi;

use Spryker\Glue\DocumentationGeneratorOpenApi\DocumentationGeneratorOpenApiConfig as SprykerDocumentationGeneratorOpenApiConfig;

class DocumentationGeneratorOpenApiConfig extends SprykerDocumentationGeneratorOpenApiConfig
{
    /**
     * @var string
     */
    protected const APPLICATION_CORE_ANNOTATION_SOURCE_DIRECTORY_CONTROLLER_PATTERN = '/*/*/*/*/src/*/Glue/%1$s/Controller/';

    /**
     * @var string
     */
    protected const APPLICATION_CORE_ANNOTATION_SOURCE_DIRECTORY_PLUGIN_PATTERN = '/*/*/*/*/src/*/Glue/%1$s/Plugin/';
}
