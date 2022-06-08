<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Glue\DocumentationGeneratorApi;

use Spryker\Glue\DocumentationGeneratorApi\DocumentationGeneratorApiDependencyProvider as SprykerDocumentationGeneratorApiDependencyProvider;
use Spryker\Glue\DocumentationGeneratorApi\Expander\ContextExpanderCollectionInterface;
use Spryker\Glue\DocumentationGeneratorApiExtension\Dependency\Plugin\ContentGeneratorStrategyPluginInterface;
use Spryker\Glue\DocumentationGeneratorOpenApi\Plugin\DocumentationGeneratorApi\ControllerAnnotationsContextExpanderPlugin;
use Spryker\Glue\DocumentationGeneratorOpenApi\Plugin\DocumentationGeneratorApi\CustomRouteControllerAnnotationsContextExpanderPlugin;
use Spryker\Glue\DocumentationGeneratorOpenApi\Plugin\DocumentationGeneratorApi\DocumentationGeneratorOpenApiContentGeneratorStrategyPlugin;
use Spryker\Glue\DocumentationGeneratorOpenApi\Plugin\DocumentationGeneratorApi\DocumentationGeneratorOpenApiSchemaFormatterPlugin;
use Spryker\Glue\DocumentationGeneratorOpenApi\Plugin\DocumentationGeneratorApi\RelationshipPluginAnnotationsContextExpanderPlugin;
use Spryker\Glue\GlueBackendApiApplication\Plugin\GlueBackendApiApplication\BackendApiApplicationProviderPlugin;
use Spryker\Glue\GlueBackendApiApplication\Plugin\GlueBackendApiApplication\BackendCustomRoutesContextExpanderPlugin;
use Spryker\Glue\GlueBackendApiApplication\Plugin\GlueBackendApiApplication\BackendHostContextExpanderPlugin;
use Spryker\Glue\GlueBackendApiApplication\Plugin\GlueBackendApiApplication\BackendResourcesContextExpanderPlugin;
use Spryker\Glue\GlueJsonApiConvention\Plugin\GlueJsonApiConvention\JsonApiSchemaFormatterPlugin;
use Spryker\Glue\GlueRestApiConvention\Plugin\GlueRestApiConvention\RestApiSchemaFormatterPlugin;
use Spryker\Glue\GlueStorefrontApiApplication\Plugin\GlueStorefrontApiApplication\StorefrontApiApplicationProviderPlugin;
use Spryker\Glue\GlueStorefrontApiApplication\Plugin\GlueStorefrontApiApplication\StorefrontCustomRoutesContextExpanderPlugin;
use Spryker\Glue\GlueStorefrontApiApplication\Plugin\GlueStorefrontApiApplication\StorefrontHostContextExpanderPlugin;
use Spryker\Glue\GlueStorefrontApiApplication\Plugin\GlueStorefrontApiApplication\StorefrontResourcesContextExpanderPlugin;
use Spryker\Glue\GlueStorefrontApiApplicationGlueJsonApiConventionConnector\Plugin\GlueStorefrontApiApplication\RelationshipPluginsContextExpanderPlugin;

class DocumentationGeneratorApiDependencyProvider extends SprykerDocumentationGeneratorApiDependencyProvider
{
    /**
     * @var string
     */
    protected const GLUE_BACKEND_API_APPLICATION_NAME = 'backend';

    /**
     * @var string
     */
    protected const GLUE_STOREFRONT_API_APPLICATION_NAME = 'storefront';

    /**
     * @return array<\Spryker\Glue\DocumentationGeneratorApiExtension\Dependency\Plugin\ApiApplicationProviderPluginInterface>
     */
    protected function getApiApplicationProviderPlugins(): array
    {
        return [
            new StorefrontApiApplicationProviderPlugin(),
            new BackendApiApplicationProviderPlugin(),
        ];
    }

    /**
     * @return array<\Spryker\Glue\DocumentationGeneratorApiExtension\Dependency\Plugin\SchemaFormatterPluginInterface>
     */
    protected function getSchemaFormatterPlugins(): array
    {
        return [
            new DocumentationGeneratorOpenApiSchemaFormatterPlugin(),
            new JsonApiSchemaFormatterPlugin(),
            new RestApiSchemaFormatterPlugin(),
        ];
    }

    /**
     * @param \Spryker\Glue\DocumentationGeneratorApi\Expander\ContextExpanderCollectionInterface $contextExpanderCollection
     *
     * @return \Spryker\Glue\DocumentationGeneratorApi\Expander\ContextExpanderCollectionInterface
     */
    protected function getContextExpanderPlugins(ContextExpanderCollectionInterface $contextExpanderCollection): ContextExpanderCollectionInterface
    {
        $apiApplications = [];
        foreach ($this->getApiApplicationProviderPlugins() as $apiApplicationProviderPlugin) {
            $apiApplications[] = $apiApplicationProviderPlugin->getName();
        }
        $contextExpanderCollection->addApplications($apiApplications);

        $contextExpanderCollection->addExpander(new StorefrontResourcesContextExpanderPlugin(), [static::GLUE_STOREFRONT_API_APPLICATION_NAME]);
        $contextExpanderCollection->addExpander(new StorefrontCustomRoutesContextExpanderPlugin(), [static::GLUE_STOREFRONT_API_APPLICATION_NAME]);
        $contextExpanderCollection->addExpander(new RelationshipPluginsContextExpanderPlugin(), [static::GLUE_STOREFRONT_API_APPLICATION_NAME]);
        $contextExpanderCollection->addExpander(new BackendResourcesContextExpanderPlugin(), [static::GLUE_BACKEND_API_APPLICATION_NAME]);
        $contextExpanderCollection->addExpander(new BackendCustomRoutesContextExpanderPlugin(), [static::GLUE_BACKEND_API_APPLICATION_NAME]);
        $contextExpanderCollection->addExpander(new ControllerAnnotationsContextExpanderPlugin());
        $contextExpanderCollection->addExpander(new CustomRouteControllerAnnotationsContextExpanderPlugin());
        $contextExpanderCollection->addExpander(new RelationshipPluginAnnotationsContextExpanderPlugin(), [static::GLUE_STOREFRONT_API_APPLICATION_NAME]);
        $contextExpanderCollection->addExpander(new BackendHostContextExpanderPlugin(), [static::GLUE_BACKEND_API_APPLICATION_NAME]);
        $contextExpanderCollection->addExpander(new StorefrontHostContextExpanderPlugin(), [static::GLUE_STOREFRONT_API_APPLICATION_NAME]);

        return $contextExpanderCollection;
    }

    /**
     * @return \Spryker\Glue\DocumentationGeneratorApiExtension\Dependency\Plugin\ContentGeneratorStrategyPluginInterface
     */
    protected function getContentGeneratorStrategyPlugin(): ContentGeneratorStrategyPluginInterface
    {
        return new DocumentationGeneratorOpenApiContentGeneratorStrategyPlugin();
    }
}
