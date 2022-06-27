<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Glue\GlueStorefrontApiApplication;

use Spryker\Glue\GlueApplication\Plugin\GlueApplication\RequestResourceFilterPlugin;
use Spryker\Glue\GlueApplication\Plugin\GlueApplication\ResourceRouteMatcherPlugin;
use Spryker\Glue\GlueHttp\Plugin\GlueStorefrontApiApplication\CorsHeaderExistenceRequestAfterRoutingValidatorPlugin;
use Spryker\Glue\GlueStorefrontApiApplication\GlueStorefrontApiApplicationDependencyProvider as SprykerGlueStorefrontApiApplicationDependencyProvider;
use Spryker\Glue\GlueStorefrontApiApplication\Plugin\GlueStorefrontApiApplication\CustomRouteRouteMatcherPlugin;
use Spryker\Glue\GlueStorefrontApiApplication\Plugin\GlueStorefrontApiApplication\CustomRouteRouterPlugin;
use Spryker\Glue\GlueStorefrontApiApplication\Plugin\GlueStorefrontApiApplication\LocaleRequestBuilderPlugin;
use Spryker\Glue\GlueStorefrontApiApplication\Plugin\GlueStorefrontApiApplication\RequestCorsValidatorPlugin;
use Spryker\Glue\GlueStorefrontApiApplication\Plugin\GlueStorefrontApiApplication\ScopeRequestAfterRoutingValidatorPlugin;
use Spryker\Glue\GlueStorefrontApiApplication\Plugin\GlueStorefrontApiApplication\SecurityHeaderResponseFormatterPlugin;
use Spryker\Glue\GlueStorefrontApiApplicationAuthorizationConnector\Plugin\GlueStorefrontApiApplicationAuthorizationConnector\AuthorizationRequestAfterRoutingValidatorPlugin;
use Spryker\Glue\GlueStorefrontApiApplicationExtension\Dependency\Plugin\RequestResourceFilterPluginInterface;
use Spryker\Glue\OauthApi\Plugin\AccessTokenValidatorPlugin;
use Spryker\Glue\OauthApi\Plugin\CustomerRequestBuilderPlugin;
use Spryker\Glue\OauthApi\Plugin\TokenResource;

class GlueStorefrontApiApplicationDependencyProvider extends SprykerGlueStorefrontApiApplicationDependencyProvider
{
    /**
     * @return array<\Spryker\Glue\GlueStorefrontApiApplicationExtension\Dependency\Plugin\RequestBuilderPluginInterface>
     */
    protected function getRequestBuilderPlugins(): array
    {
        return [
            new LocaleRequestBuilderPlugin(),
            new CustomerRequestBuilderPlugin(),
        ];
    }

    /**
     * @return array<\Spryker\Glue\GlueStorefrontApiApplicationExtension\Dependency\Plugin\RequestValidatorPluginInterface>
     */
    protected function getRequestValidatorPlugins(): array
    {
        return [
            new AccessTokenValidatorPlugin(),
        ];
    }

    /**
     * @return array<\Spryker\Glue\GlueStorefrontApiApplicationExtension\Dependency\Plugin\RequestAfterRoutingValidatorPluginInterface>
     */
    protected function getRequestAfterRoutingValidatorPlugins(): array
    {
        return [
            new CorsHeaderExistenceRequestAfterRoutingValidatorPlugin(),
            new RequestCorsValidatorPlugin(),
            new ScopeRequestAfterRoutingValidatorPlugin(),
            new AuthorizationRequestAfterRoutingValidatorPlugin(),
        ];
    }

    /**
     * @return array<\Spryker\Glue\GlueStorefrontApiApplicationExtension\Dependency\Plugin\ResponseFormatterPluginInterface>
     */
    protected function getResponseFormatterPlugins(): array
    {
        return [
            new SecurityHeaderResponseFormatterPlugin(),
        ];
    }

    /**
     * @return array<\Spryker\Glue\GlueStorefrontApiApplicationExtension\Dependency\Plugin\RouterPluginInterface>
     */
    protected function getRouterPlugins(): array
    {
        return [
            new CustomRouteRouterPlugin(),
        ];
    }

    /**
     * @return array<\Spryker\Glue\GlueStorefrontApiApplicationExtension\Dependency\Plugin\RouteMatcherPluginInterface>
     */
    protected function getRouteMatcherPlugins(): array
    {
        return [
            new CustomRouteRouteMatcherPlugin(),
            new ResourceRouteMatcherPlugin(),
        ];
    }

    /**
     * @return array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceInterface>
     */
    protected function getResourcePlugins(): array
    {
        return [
            new TokenResource(),
        ];
    }

    /**
     * @return \Spryker\Glue\GlueStorefrontApiApplicationExtension\Dependency\Plugin\RequestResourceFilterPluginInterface
     */
    public function getRequestResourceFilterPlugin(): RequestResourceFilterPluginInterface
    {
        return new RequestResourceFilterPlugin();
    }

    /**
     * @return array<\Spryker\Glue\GlueStorefrontApiApplicationExtension\Dependency\Plugin\RouteProviderPluginInterface>
     */
    protected function getRouteProviderPlugins(): array
    {
        return [];
    }
}
