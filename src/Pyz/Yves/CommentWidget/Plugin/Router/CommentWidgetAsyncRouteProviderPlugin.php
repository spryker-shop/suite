<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\CommentWidget\Plugin\Router;

use Spryker\Yves\Router\Plugin\RouteProvider\AbstractRouteProviderPlugin;
use Spryker\Yves\Router\Route\RouteCollection;
use Symfony\Component\HttpFoundation\Request;

class CommentWidgetAsyncRouteProviderPlugin extends AbstractRouteProviderPlugin
{
    /**
     * @var string
     */
    public const ROUTE_NAME_COMMENT_ASYNC_ADD = 'comment/async/add';

    /**
     * @var string
     */
    public const ROUTE_NAME_COMMENT_ASYNC_UPDATE = 'comment/async/update';

    /**
     * @var string
     */
    public const ROUTE_NAME_COMMENT_ASYNC_REMOVE = 'comment/async/remove';

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
        $routeCollection = $this->addAddAsyncCommentRoute($routeCollection);
        $routeCollection = $this->addUpdateAsyncCommentRoute($routeCollection);
        $routeCollection = $this->addRemoveAsyncCommentRoute($routeCollection);

        return $routeCollection;
    }

    /**
     * @uses \Pyz\Yves\CommentWidget\Controller\CommentAsyncController::addAction()
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addAddAsyncCommentRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/comment/async/add', 'CommentWidget', 'CommentAsync', 'addAction');
        $route = $route->setMethods(Request::METHOD_POST);
        $routeCollection->add(static::ROUTE_NAME_COMMENT_ASYNC_ADD, $route);

        return $routeCollection;
    }

    /**
     * @uses \Pyz\Yves\CommentWidget\Controller\CommentAsyncController::updateAction()
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addUpdateAsyncCommentRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/comment/async/update', 'CommentWidget', 'CommentAsync', 'updateAction');
        $route = $route->setMethods(Request::METHOD_POST);
        $routeCollection->add(static::ROUTE_NAME_COMMENT_ASYNC_UPDATE, $route);

        return $routeCollection;
    }

    /**
     * @uses \Pyz\Yves\CommentWidget\Controller\CommentAsyncController::removeAction()
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addRemoveAsyncCommentRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/comment/async/remove', 'CommentWidget', 'CommentAsync', 'removeAction');
        $route = $route->setMethods(Request::METHOD_POST);
        $routeCollection->add(static::ROUTE_NAME_COMMENT_ASYNC_REMOVE, $route);

        return $routeCollection;
    }
}
