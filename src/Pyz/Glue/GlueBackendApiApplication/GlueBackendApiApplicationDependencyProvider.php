<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Glue\GlueBackendApiApplication;

use Spryker\Glue\CategoriesBackendApi\Plugin\GlueApplication\CategoriesBackendApiResource;
use Spryker\Glue\DynamicEntityBackendApi\Plugin\GlueApplication\DynamicEntityRouteProviderPlugin;
use Spryker\Glue\EventDispatcher\Plugin\Application\EventDispatcherApplicationPlugin;
use Spryker\Glue\GlueBackendApiApplication\GlueBackendApiApplicationDependencyProvider as SprykerGlueBackendApiApplicationDependencyProvider;
use Spryker\Glue\GlueBackendApiApplication\Plugin\GlueApplication\ApplicationIdentifierRequestBuilderPlugin;
use Spryker\Glue\GlueBackendApiApplication\Plugin\GlueApplication\LocaleRequestBuilderPlugin;
use Spryker\Glue\GlueBackendApiApplication\Plugin\GlueApplication\RequestCorsValidatorPlugin;
use Spryker\Glue\GlueBackendApiApplication\Plugin\GlueApplication\ScopeRequestAfterRoutingValidatorPlugin;
use Spryker\Glue\GlueBackendApiApplication\Plugin\GlueApplication\SecurityHeaderResponseFormatterPlugin;
use Spryker\Glue\GlueBackendApiApplication\Plugin\GlueApplication\StrictTransportSecurityHeaderResponseFormatterPlugin;
use Spryker\Glue\GlueBackendApiApplicationAuthorizationConnector\Plugin\GlueBackendApiApplication\AuthorizationRequestAfterRoutingValidatorPlugin;
use Spryker\Glue\Http\Plugin\Application\HttpApplicationPlugin;
use Spryker\Glue\OauthBackendApi\Plugin\AccessTokenValidatorPlugin;
use Spryker\Glue\OauthBackendApi\Plugin\GlueApplication\OauthBackendApiTokenResource;
use Spryker\Glue\OauthBackendApi\Plugin\GlueApplication\UserRequestValidatorPlugin;
use Spryker\Glue\OauthBackendApi\Plugin\GlueBackendApiApplication\UserRequestBuilderPlugin;
use Spryker\Glue\PickingListsBackendApi\Plugin\GlueBackendApiApplication\PickingListItemsBackendResourcePlugin;
use Spryker\Glue\PickingListsBackendApi\Plugin\GlueBackendApiApplication\PickingListsBackendResourcePlugin;
use Spryker\Glue\PickingListsBackendApi\Plugin\GlueBackendApiApplication\PickingListStartPickingBackendResourcePlugin;
use Spryker\Glue\ProductAttributesBackendApi\Plugin\GlueApplication\ProductAttributesBackendResourcePlugin;
use Spryker\Glue\ProductsBackendApi\Plugin\GlueApplication\ProductsBackendResourcePlugin;
use Spryker\Glue\PushNotificationsBackendApi\Plugin\GlueBackendApiApplication\PushNotificationProvidersBackendResourcePlugin;
use Spryker\Glue\PushNotificationsBackendApi\Plugin\GlueBackendApiApplication\PushNotificationSubscriptionsBackendResourcePlugin;
use Spryker\Glue\Router\Plugin\Application\RouterApplicationPlugin;
use Spryker\Glue\ServicePointsBackendApi\Plugin\GlueBackendApiApplication\ServicePointAddressesBackendResourcePlugin;
use Spryker\Glue\ServicePointsBackendApi\Plugin\GlueBackendApiApplication\ServicePointsBackendResourcePlugin;
use Spryker\Glue\ServicePointsBackendApi\Plugin\GlueBackendApiApplication\ServicesBackendResourcePlugin;
use Spryker\Glue\ServicePointsBackendApi\Plugin\GlueBackendApiApplication\ServiceTypesBackendResourcePlugin;
use Spryker\Glue\ShipmentTypesBackendApi\Plugin\GlueBackendApiApplication\ShipmentTypesBackendResourcePlugin;
use Spryker\Glue\StoresRestApi\Plugin\Application\StoreHttpHeaderApplicationPlugin;
use Spryker\Glue\WarehouseOauthBackendApi\Plugin\GlueBackendApiApplication\WarehouseRequestBuilderPlugin;
use Spryker\Glue\WarehouseOauthBackendApi\Plugin\GlueBackendApiApplication\WarehouseRequestValidatorPlugin;
use Spryker\Glue\WarehouseOauthBackendApi\Plugin\GlueBackendApiApplication\WarehouseTokensBackendResourcePlugin;
use Spryker\Glue\WarehouseUsersBackendApi\Plugin\GlueApplication\WarehouseUserAssignmentsBackendResourcePlugin;
use Spryker\Zed\Propel\Communication\Plugin\Application\PropelApplicationPlugin;

class GlueBackendApiApplicationDependencyProvider extends SprykerGlueBackendApiApplicationDependencyProvider
{
    /**
     * @return array<\Spryker\Shared\ApplicationExtension\Dependency\Plugin\ApplicationPluginInterface>
     */
    protected function getApplicationPlugins(): array
    {
        return [
            new HttpApplicationPlugin(),
            new PropelApplicationPlugin(),
            new StoreHttpHeaderApplicationPlugin(),
            new RouterApplicationPlugin(),
            new EventDispatcherApplicationPlugin(),
        ];
    }

    /**
     * @return array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\RequestBuilderPluginInterface>
     */
    protected function getRequestBuilderPlugins(): array
    {
        return [
            new ApplicationIdentifierRequestBuilderPlugin(),
            new LocaleRequestBuilderPlugin(),
            new UserRequestBuilderPlugin(),
            new WarehouseRequestBuilderPlugin(),
        ];
    }

    /**
     * @return array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\RequestValidatorPluginInterface>
     */
    protected function getRequestValidatorPlugins(): array
    {
        return [
            new AccessTokenValidatorPlugin(),
            new UserRequestValidatorPlugin(),
            new WarehouseRequestValidatorPlugin(),
        ];
    }

    /**
     * @return array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\RequestAfterRoutingValidatorPluginInterface>
     */
    protected function getRequestAfterRoutingValidatorPlugins(): array
    {
        return [
            new RequestCorsValidatorPlugin(),
            new ScopeRequestAfterRoutingValidatorPlugin(),
            new AuthorizationRequestAfterRoutingValidatorPlugin(),
        ];
    }

    /**
     * @return array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResponseFormatterPluginInterface>
     */
    protected function getResponseFormatterPlugins(): array
    {
        return [
            new SecurityHeaderResponseFormatterPlugin(),
            new StrictTransportSecurityHeaderResponseFormatterPlugin(),
        ];
    }

    /**
     * @return array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceInterface>
     */
    protected function getResourcePlugins(): array
    {
        return [
            new CategoriesBackendApiResource(),
            new OauthBackendApiTokenResource(),
            new PickingListStartPickingBackendResourcePlugin(),
            new PickingListsBackendResourcePlugin(),
            new PickingListItemsBackendResourcePlugin(),
            new ProductAttributesBackendResourcePlugin(),
            new ProductsBackendResourcePlugin(),
            new WarehouseUserAssignmentsBackendResourcePlugin(),
            new WarehouseTokensBackendResourcePlugin(),
            new PushNotificationSubscriptionsBackendResourcePlugin(),
            new PushNotificationProvidersBackendResourcePlugin(),
            new ServicePointsBackendResourcePlugin(),
            new ServicePointAddressesBackendResourcePlugin(),
            new ServiceTypesBackendResourcePlugin(),
            new ServicesBackendResourcePlugin(),
            new ShipmentTypesBackendResourcePlugin(),
        ];
    }

    /**
     * @return array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\RouteProviderPluginInterface>
     */
    protected function getRouteProviderPlugins(): array
    {
        return [
            new DynamicEntityRouteProviderPlugin(),
        ];
    }
}
