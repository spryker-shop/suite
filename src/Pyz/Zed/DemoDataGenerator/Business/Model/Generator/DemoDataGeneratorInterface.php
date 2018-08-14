<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DemoDataGenerator\Business\Model\Generator;

use Generated\Shared\Transfer\DemoDataGeneratorTransfer;
use Pyz\Zed\DemoDataGenerator\Business\Model\PluginResolver\PluginResolverInterface;

interface DemoDataGeneratorInterface
{
    /**
     * @param \Generated\Shared\Transfer\DemoDataGeneratorTransfer $demoDataGeneratorTransfer
     * @param \Pyz\Zed\DemoDataGenerator\Business\Model\Generator\PluginResolverInterface $pluginResolver
     *
     * @return void
     */
    public function generate(
        DemoDataGeneratorTransfer $demoDataGeneratorTransfer,
        PluginResolverInterface $pluginResolver
    ): void;
}
