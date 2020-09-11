<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\ProductConfiguratorGatewayPage\Plugin\Router;

use Spryker\Yves\Router\Route\RouteCollection;
use SprykerShop\Yves\ProductConfiguratorGatewayPage\Plugin\Router\ProductConfiguratorGatewayPageRouteProviderPlugin as SprykerProductConfiguratorGatewayPageRouteProviderPlugin;

/**
 * @TODO this file will be removed in 2nd phase of configurable product. Will keep it for now as demo version
 */
class ProductConfiguratorGatewayPageRouteProviderPlugin extends SprykerProductConfiguratorGatewayPageRouteProviderPlugin
{
    protected const PRODUCT_CONFIGURATOR_PAGE_ROUTE = 'product-configurator-page';
    protected const PRODUCT_CONFIGURATOR_PAGE_URL = '/dummy-configurator';

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    public function addRoutes(RouteCollection $routeCollection): RouteCollection
    {
        $routeCollection = parent::addRoutes($routeCollection);
        $routeCollection = $this->addProductConfiguratorPage($routeCollection);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addProductConfiguratorPage(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute(
            static::PRODUCT_CONFIGURATOR_PAGE_URL,
            'ProductConfiguratorGatewayPage',
            'ProductConfiguratorPage',
            'indexAction'
        );

        $routeCollection->add(static::PRODUCT_CONFIGURATOR_PAGE_ROUTE, $route);

        return $routeCollection;
    }
}
