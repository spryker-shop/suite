<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Pyz\Zed\Router;

use Spryker\Zed\Api\Communication\Plugin\Router\ApiRouterPlugin;
use Spryker\Zed\Router\Communication\Plugin\Router\BackendGatewayRouterPlugin;
use Spryker\Zed\Router\Communication\Plugin\Router\BackofficeRouterPlugin;
use Spryker\Zed\Router\Communication\Plugin\Router\MerchantPortalRouterPlugin;
use Spryker\Zed\Router\Communication\Plugin\Router\ZedDevelopmentRouterPlugin;
use Spryker\Zed\Router\Communication\Plugin\Router\ZedRouterPlugin;
use Spryker\Zed\Router\RouterDependencyProvider as SprykerRouterDependencyProvider;

class RouterDependencyProvider extends SprykerRouterDependencyProvider
{
    /**
     * @return array<\Spryker\Zed\RouterExtension\Dependency\Plugin\RouterPluginInterface>
     */
    protected function getBackofficeRouterPlugins(): array
    {
        return [
            new BackofficeRouterPlugin(),
        ];
    }

    /**
     * @return array<\Spryker\Zed\RouterExtension\Dependency\Plugin\RouterPluginInterface>
     */
    protected function getMerchantPortalRouterPlugins(): array
    {
        return [
            new MerchantPortalRouterPlugin(),
            new ZedDevelopmentRouterPlugin(), //This plugin is used to handle Profiler routes.
        ];
    }

    /**
     * @return array<\Spryker\Zed\RouterExtension\Dependency\Plugin\RouterPluginInterface>
     */
    protected function getBackendGatewayRouterPlugins(): array
    {
        return [
            new BackendGatewayRouterPlugin(),
        ];
    }

    /**
     * @return array<\Spryker\Zed\RouterExtension\Dependency\Plugin\RouterPluginInterface>
     */
    protected function getBackendApiRouterPlugins(): array
    {
        return [
            new ApiRouterPlugin(),
        ];
    }

    /**
     * @return array<\Spryker\Zed\RouterExtension\Dependency\Plugin\RouterPluginInterface>
     */
    protected function getRouterPlugins(): array
    {
        return [
            new ZedRouterPlugin(),
            new ZedDevelopmentRouterPlugin(),
        ];
    }
}
