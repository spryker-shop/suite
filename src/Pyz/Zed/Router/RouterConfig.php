<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Router;

use Laminas\Filter\FilterChain;
use Laminas\Filter\StringToLower;
use Laminas\Filter\Word\CamelCaseToDash;
use Spryker\Shared\Kernel\KernelConstants;
use Spryker\Zed\Router\RouterConfig as SprykerRouterConfig;

class RouterConfig extends SprykerRouterConfig
{
    /**
     * @project Only required for nonsplit-repositories, do not use this in project.
     *
     * @return array<string>
     */
    public function getControllerDirectories(): array
    {
        $controllerDirectories = parent::getControllerDirectories();

        $filterChain = new FilterChain();
        $filterChain
            ->attach(new CamelCaseToDash())
            ->attach(new StringToLower());

        foreach ($this->get(KernelConstants::CORE_NAMESPACES) as $coreNamespace) {
            $controllerDirectories[] = sprintf('%s/spryker/%s/Bundles/*/src/%s/Zed/*/Communication/Controller/', APPLICATION_VENDOR_DIR, $filterChain->filter($coreNamespace), $coreNamespace);
        }

        return array_filter($controllerDirectories, 'glob');
    }
}
