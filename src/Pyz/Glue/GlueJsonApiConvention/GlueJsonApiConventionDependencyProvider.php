<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Glue\GlueJsonApiConvention;

use Spryker\Glue\GlueJsonApiConvention\GlueJsonApiConventionDependencyProvider as SprykerGlueJsonApiConventionDependencyProvider;
use Spryker\Glue\GlueJsonApiConvention\Plugin\GlueJsonApiConvention\AttributesRequestBuilderPlugin;
use Spryker\Glue\GlueJsonApiConvention\Plugin\GlueJsonApiConvention\FilterFieldRequestBuilderPlugin;
use Spryker\Glue\GlueJsonApiConvention\Plugin\GlueJsonApiConvention\JsonApiResponseFormatterPlugin;
use Spryker\Glue\GlueJsonApiConvention\Plugin\GlueJsonApiConvention\PaginationRequestBuilderPlugin;
use Spryker\Glue\GlueJsonApiConvention\Plugin\GlueJsonApiConvention\RelationshipRequestBuilderPlugin;
use Spryker\Glue\GlueJsonApiConvention\Plugin\GlueJsonApiConvention\RelationshipResponseFormatterPlugin;
use Spryker\Glue\GlueJsonApiConvention\Plugin\GlueJsonApiConvention\SortRequestBuilderPlugin;
use Spryker\Glue\GlueJsonApiConvention\Plugin\GlueJsonApiConvention\SparseFieldRequestBuilderPlugin;
use Spryker\Glue\GlueStorefrontApiApplicationGlueJsonApiConventionConnector\Plugin\GlueStorefrontApiApplication\StorefrontApiRelationshipProviderPlugin;

class GlueJsonApiConventionDependencyProvider extends SprykerGlueJsonApiConventionDependencyProvider
{
    /**
     * @return array<\Spryker\Glue\GlueJsonApiConventionExtension\Dependency\Plugin\RelationshipProviderPluginInterface>
     */
    public function getRelationshipProviderPlugins(): array
    {
        return [
            new StorefrontApiRelationshipProviderPlugin(),
        ];
    }

    /**
     * @return array<\Spryker\Glue\GlueJsonApiConventionExtension\Dependency\Plugin\RequestBuilderPluginInterface>
     */
    protected function getRequestBuilderPlugins(): array
    {
        return [
            new SparseFieldRequestBuilderPlugin(),
            new AttributesRequestBuilderPlugin(),
            new RelationshipRequestBuilderPlugin(),
            new PaginationRequestBuilderPlugin(),
            new SortRequestBuilderPlugin(),
            new FilterFieldRequestBuilderPlugin(),
        ];
    }

    /**
     * @return array<\Spryker\Glue\GlueJsonApiConventionExtension\Dependency\Plugin\ResponseFormatterPluginInterface>
     */
    protected function getResponseFormatterPlugins(): array
    {
        return [
            new RelationshipResponseFormatterPlugin(),
            new JsonApiResponseFormatterPlugin(),
        ];
    }
}
