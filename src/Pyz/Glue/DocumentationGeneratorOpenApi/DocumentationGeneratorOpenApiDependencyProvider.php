<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Glue\DocumentationGeneratorOpenApi;

use Spryker\Glue\DocumentationGeneratorOpenApi\DocumentationGeneratorOpenApiDependencyProvider as SprykerDocumentationGeneratorOpenApiDependencyProvider;

class DocumentationGeneratorOpenApiDependencyProvider extends SprykerDocumentationGeneratorOpenApiDependencyProvider
{
    /**
     * @project Only needed internal non-split project, not in public split project.
     *
     * @return array<string>
     */
    protected function getCoreAnnotationSourceDirectoryPatterns(): array
    {
        return [
            APPLICATION_VENDOR_DIR . '/spryker/spryker/Bundles/%1$s/src/Spryker/Glue/%1$s/Controller/',
            APPLICATION_VENDOR_DIR . '/spryker/spryker/Bundles/%1$s/src/Spryker/Glue/%1$s/Plugin/',
        ];
    }
}
