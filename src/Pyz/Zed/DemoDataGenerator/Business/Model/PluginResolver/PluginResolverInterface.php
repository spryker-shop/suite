<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DemoDataGenerator\Business\Model\PluginResolver;

use Pyz\Zed\DemoDataGenerator\Dependency\Plugin\DemoDataGeneratorPluginInterface;

interface PluginResolverInterface
{
    /**
     * @param string $type
     *
     * @throws \Pyz\Zed\DemoDataGenerator\Business\Model\Exception\DemoDataGeneratorPluginNotFoundException
     *
     * @return \Pyz\Zed\DemoDataGenerator\Dependency\Plugin\DemoDataGeneratorPluginInterface
     */
    public function getPluginByType(string $type): DemoDataGeneratorPluginInterface;
}
