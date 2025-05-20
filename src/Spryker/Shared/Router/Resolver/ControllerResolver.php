<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Router\Resolver;

use InvalidArgumentException;
use Spryker\Service\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ControllerResolverInterface;

class ControllerResolver implements ControllerResolverInterface
{
    use \Spryker\Shared\SymfonyContainer\ContainerTrait;

    /**
     * @var \Spryker\Service\Container\ContainerInterface
     */
    protected $container;

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return callable|false
     */
    public function getController(Request $request): callable|false
    {
        $controller = $request->attributes->get('_controller');

        if (!$controller) {
            return false;
        }

        if (is_string($controller)) {
            return $this->getControllerFromString($request, $controller);
        }

        if (is_array($controller)) {
            return $this->getControllerFromArray($request, $controller);
        }

        if (is_object($controller)) {
            return $this->getControllerFromObject($request, $controller);
        }

        return false;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string $controller
     *
     * @return callable|false
     */
    protected function getControllerFromString(Request $request, string $controller)
    {
        if (strpos($controller, ':') === false) {
            return false;
        }

        [$controllerServiceIdentifier, $actionName] = explode(':', $controller);
        if ($this->container->has($controllerServiceIdentifier)) {
            $controllerNameSpace = $this->container->get($controllerServiceIdentifier);
            $controllerInstance = $this->createInstance($controllerNameSpace);
            $controllerInstance = $this->injectContainerAndInitialize($controllerInstance);

            /** @phpstan-var callable $callable*/
            $callable = [$controllerInstance, $actionName];

            return $callable;
        }

        return false;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param array $controller
     *
     * @return callable
     */
    protected function getControllerFromArray(Request $request, array $controller)
    {
        if (is_callable($controller[0])) {
            $controllerInstance = $controller[0]();
            $controllerInstance = $this->injectContainerAndInitialize($controllerInstance);

            /** @phpstan-var callable $callable*/
            $callable = [$controllerInstance, $controller[1]];

            return $callable;
        }

        $controllerInstance = is_object($controller[0]) ? new $controller[0] : $this->createInstance($controller[0]);
        $controllerInstance = $this->injectContainerAndInitialize($controllerInstance);

        /** @phpstan-var callable $callable*/
        $callable = [$controllerInstance, $controller[1]];

        return $callable;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param object $controller
     *
     * @throws \InvalidArgumentException
     *
     * @return callable
     */
    protected function getControllerFromObject(Request $request, $controller)
    {
        if (method_exists($controller, '__invoke')) {

            /** @phpstan-var callable $controller*/
            $controller = $this->injectContainerAndInitialize($controller);

            return $controller;
        }

        throw new InvalidArgumentException(sprintf('Controller "%s" for URI "%s" is not callable.', get_class($controller), $request->getPathInfo()));
    }

    /**
     * @param object $controller
     *
     * @return object
     */
    protected function injectContainerAndInitialize($controller)
    {
        if (method_exists($controller, 'setApplication')) {
            $controller->setApplication($this->container);
        }

        if (method_exists($controller, 'initialize')) {
            $controller->initialize();
        }

        return $controller;
    }

    /**
     * @deprecated This method is deprecated as of 3.1 and will be removed in 4.0. Implement the ArgumentResolverInterface and inject it in the HttpKernel instead.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Spryker\Zed\Kernel\Communication\Controller\AbstractController $controller
     *
     * @return array
     */
    public function getArguments(Request $request, $controller)
    {
        return [];
    }

    /**
     * @param string $controllerNameSpace
     *
     * @return object
     */
    protected function createInstance(string $controllerNameSpace): object
    {
        if ($this->has($controllerNameSpace)) {
            return $this->create($controllerNameSpace);
        }

        return new $controllerNameSpace();
    }
}
