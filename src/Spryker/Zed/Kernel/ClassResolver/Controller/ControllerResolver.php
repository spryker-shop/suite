<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Kernel\ClassResolver\Controller;

use Spryker\Shared\Kernel\ClassResolver\Controller\AbstractControllerResolver;

class ControllerResolver extends AbstractControllerResolver
{
    use \Spryker\Shared\SymfonyContainer\ContainerTrait;

    /**
     * @var string
     */
    public const CLASS_NAME_PATTERN = '\\%s\\Zed\\%s%s\\Communication\\Controller\\%sController';

    /**
     * @return string
     */
    protected function getClassNamePattern()
    {
        return static::CLASS_NAME_PATTERN;
    }

    /**
     * @param string|null $resolvedClassName
     *
     * @return object
     */
    protected function createInstance(?string $resolvedClassName = null)
    {
        if ($resolvedClassName === null) {
            $resolvedClassName = $this->resolvedClassName;
        }

        if ($this->has($resolvedClassName)) {
            return $this->create($resolvedClassName);
        }

        return new $resolvedClassName();
    }

    protected function getResolvedClassInstance()
    {
        if (!$this->canUseCaching()) {
            return $this->createInstance($this->resolvedClassName);
        }

        $cacheKey = $this->getCacheKey();

        if (!isset(static::$cachedInstances[$cacheKey])) {
            static::$cachedInstances[$cacheKey] = new $this->resolvedClassName();
        }

        return static::$cachedInstances[$cacheKey];
    }
}
