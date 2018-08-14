<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DemoDataGenerator\Business\Model\Generator;

use Generated\Shared\Transfer\DemoDataGeneratorTransfer;
use Pyz\Zed\DemoDataGenerator\Business\Model\PluginResolver\PluginResolverInterface;

class DemoDataGenerator implements DemoDataGeneratorInterface
{
    /**
     * @param \Generated\Shared\Transfer\DemoDataGeneratorTransfer $demoDataGeneratorTransfer
     * @param \Pyz\Zed\DemoDataGenerator\Business\Model\PluginResolver\PluginResolverInterface $pluginResolver
     *
     * @return void
     */
    public function generate(
        DemoDataGeneratorTransfer $demoDataGeneratorTransfer,
        PluginResolverInterface $pluginResolver
    ): void {
        $processPlugin = $pluginResolver->getPluginByType($demoDataGeneratorTransfer->getType());
        $processPlugin->generateDemoData($demoDataGeneratorTransfer);
    }
}
