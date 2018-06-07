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
     * @var \Generated\Shared\Transfer\DemoDataGeneratorTransfer
     */
    protected $demoDataGeneratorTransfer;

    /**
     * @var \Pyz\Zed\DemoDataGenerator\Business\Model\PluginResolver\PluginResolverInterface
     */
    protected $pluginResolver;

    /**
     * DemoDataGenerator constructor.
     *
     * @param \Generated\Shared\Transfer\DemoDataGeneratorTransfer $demoDataGeneratorTransfer
     * @param \Pyz\Zed\DemoDataGenerator\Business\Model\PluginResolver\PluginResolverInterface $pluginResolver
     */
    public function __construct(
        DemoDataGeneratorTransfer $demoDataGeneratorTransfer,
        PluginResolverInterface $pluginResolver
    ) {
        $this->demoDataGeneratorTransfer = $demoDataGeneratorTransfer;
        $this->pluginResolver = $pluginResolver;
    }

    /**
     * @return void
     */
    public function generate(): void
    {
        $processPlugin = $this->pluginResolver
            ->getPluginByType($this->demoDataGeneratorTransfer->getType());

        $processPlugin->generateDemoData($this->demoDataGeneratorTransfer);
    }
}
