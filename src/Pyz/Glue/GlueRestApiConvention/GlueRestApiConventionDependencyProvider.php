<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Glue\GlueRestApiConvention;

use Spryker\Glue\GlueRestApiConvention\GlueRestApiConventionDependencyProvider as SprykerGlueRestApiConventionDependencyProvider;
use Spryker\Glue\GlueRestApiConvention\Plugin\GlueRestApiConvention\AcceptFormatRequestValidatorPlugin;
use Spryker\Glue\GlueRestApiConvention\Plugin\GlueRestApiConvention\AttributesRequestBuilderPlugin;
use Spryker\Glue\GlueRestApiConvention\Plugin\GlueRestApiConvention\FilterFieldRequestBuilderPlugin;
use Spryker\Glue\GlueRestApiConvention\Plugin\GlueRestApiConvention\FormatRequestBuilderPlugin;
use Spryker\Glue\GlueRestApiConvention\Plugin\GlueRestApiConvention\JsonResponseEncoderPlugin;
use Spryker\Glue\GlueRestApiConvention\Plugin\GlueRestApiConvention\PaginationRequestBuilderPlugin;
use Spryker\Glue\GlueRestApiConvention\Plugin\GlueRestApiConvention\RestApiResponseFormatterPlugin;
use Spryker\Glue\GlueRestApiConvention\Plugin\GlueRestApiConvention\SortRequestBuilderPlugin;
use Spryker\Glue\GlueRestApiConvention\Plugin\GlueRestApiConvention\SparseFieldRequestBuilderPlugin;

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
     * @return array<\Spryker\Glue\GlueRestApiConventionExtension\Dependency\Plugin\RequestBuilderPluginInterface>
     */
    protected function getRequestBuilderPlugins(): array
    {
        return [
            new FormatRequestBuilderPlugin(),
            new PaginationRequestBuilderPlugin(),
            new SortRequestBuilderPlugin(),
            new FilterFieldRequestBuilderPlugin(),
            new SparseFieldRequestBuilderPlugin(),
            new AttributesRequestBuilderPlugin(),
        ];
    }

    /**
     * @return array<\Spryker\Glue\GlueRestApiConventionExtension\Dependency\Plugin\RequestValidatorPluginInterface>
     */
    protected function getRequestValidatorPlugins(): array
    {
        return [
            new AcceptFormatRequestValidatorPlugin(),
        ];
    }

    /**
     * @return array<\Spryker\Glue\GlueRestApiConventionExtension\Dependency\Plugin\ResponseFormatterPluginInterface>
     */
    protected function getResponseFormatterPlugins(): array
    {
        return [
            new RestApiResponseFormatterPlugin(),
        ];
    }
}
