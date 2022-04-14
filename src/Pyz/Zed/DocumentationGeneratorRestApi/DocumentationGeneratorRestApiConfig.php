<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DocumentationGeneratorRestApi;

use Spryker\Zed\DocumentationGeneratorRestApi\DocumentationGeneratorRestApiConfig as SprykerDocumentationGeneratorRestApiConfig;

class DocumentationGeneratorRestApiConfig extends SprykerDocumentationGeneratorRestApiConfig
{
    /**
     * @return string
     */
    public function getPathVersionPrefix(): string
    {
        return 'v';
    }

    /**
     * @return bool
     */
    public function getPathVersionResolving(): bool
    {
        return true;
    }
}
