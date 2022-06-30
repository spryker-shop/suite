<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\CartPage\Plugin\Router;

use Spryker\Yves\Router\Plugin\RouteProvider\AbstractRouteProviderPlugin;
use Spryker\Yves\Router\Route\RouteCollection;
use Symfony\Component\HttpFoundation\Request;

class CartPageAsyncRouteProviderPlugin extends AbstractRouteProviderPlugin
{
    /**
     * @var string
     */
    public const ROUTE_NAME_CART_ASYNC_REMOVE = 'cart/async/remove';

    /**
     * @var string
     */
    public const ROUTE_NAME_CART_ASYNC_QUICK_ADD = 'cart/async/quick-add';

    /**
     * @var string
     */
    protected const SKU_PATTERN = '[a-zA-Z0-9-_\.]+';

    /**
     * Specification:
     * - Adds Routes to the RouteCollection.
     *
     * @api
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    public function addRoutes(RouteCollection $routeCollection): RouteCollection
    {
        $routeCollection = $this->addCartRemoveRoute($routeCollection);
        $routeCollection = $this->addCartQuickAddRoute($routeCollection);

        return $routeCollection;
    }

    /**
     * @uses \Pyz\Yves\CartPage\Controller\CartAsyncController::removeAction()
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addCartRemoveRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/cart/async/remove/{sku}/{groupKey}', 'CartPage', 'CartAsync', 'removeAction');
        $route = $route->setRequirement('sku', static::SKU_PATTERN);
        $route = $route->setDefault('groupKey', '');
        $route = $route->setMethods(Request::METHOD_POST);

        $routeCollection->add(static::ROUTE_NAME_CART_ASYNC_REMOVE, $route);

        return $routeCollection;
    }

    /**
     * @uses \Pyz\Yves\CartPage\Controller\CartAsyncController::quickAddAction()
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addCartQuickAddRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/cart/async/quick-add', 'CartPage', 'CartAsync', 'quickAddAction');
        $routeCollection->add(static::ROUTE_NAME_CART_ASYNC_QUICK_ADD, $route);

        return $routeCollection;
    }
}
