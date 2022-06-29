<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Glue\GlueRestApiConvention;

use Spryker\Glue\GlueRestApiConvention\GlueRestApiConventionDependencyProvider as SprykerGlueRestApiConventionDependencyProvider;
use Spryker\Glue\GlueRestApiConvention\Plugin\GlueApplication\AcceptFormatRequestValidatorPlugin;
use Spryker\Glue\GlueRestApiConvention\Plugin\GlueApplication\AttributesRequestBuilderPlugin;
use Spryker\Glue\GlueRestApiConvention\Plugin\GlueApplication\ConventionIdentifierRequestBuilderPlugin;
use Spryker\Glue\GlueRestApiConvention\Plugin\GlueApplication\FilterFieldRequestBuilderPlugin;
use Spryker\Glue\GlueRestApiConvention\Plugin\GlueApplication\FormatRequestBuilderPlugin;
use Spryker\Glue\GlueRestApiConvention\Plugin\GlueApplication\PaginationRequestBuilderPlugin;
use Spryker\Glue\GlueRestApiConvention\Plugin\GlueApplication\RestApiResponseFormatterPlugin;
use Spryker\Glue\GlueRestApiConvention\Plugin\GlueApplication\SortRequestBuilderPlugin;
use Spryker\Glue\GlueRestApiConvention\Plugin\GlueApplication\SparseFieldRequestBuilderPlugin;
use Spryker\Glue\GlueRestApiConvention\Plugin\GlueRestApiConvention\JsonResponseEncoderPlugin;

class GlueRestApiConventionDependencyProvider extends SprykerGlueRestApiConventionDependencyProvider
{
    /**
     * @return array<\Spryker\Glue\GlueRestApiConventionExtension\Dependency\Plugin\ResponseEncoderPluginInterface>
     */
    protected function getResponseEncoderPlugins(): array
    {
        return [
            new JsonResponseEncoderPlugin(),
        ];
    }

    /**
     * @return array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\RequestBuilderPluginInterface>
     */
    protected function getRequestBuilderPlugins(): array
    {
        return [
            new ConventionIdentifierRequestBuilderPlugin(),
            new FormatRequestBuilderPlugin(),
            new PaginationRequestBuilderPlugin(),
            new SortRequestBuilderPlugin(),
            new FilterFieldRequestBuilderPlugin(),
            new SparseFieldRequestBuilderPlugin(),
            new AttributesRequestBuilderPlugin(),
        ];
    }

    /**
     * @return array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\RequestValidatorPluginInterface>
     */
    protected function getRequestValidatorPlugins(): array
    {
        return [
            new AcceptFormatRequestValidatorPlugin(),
        ];
    }

    /**
     * @return array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResponseFormatterPluginInterface>
     */
    protected function getResponseFormatterPlugins(): array
    {
        return [
            new RestApiResponseFormatterPlugin(),
        ];
    }
}
