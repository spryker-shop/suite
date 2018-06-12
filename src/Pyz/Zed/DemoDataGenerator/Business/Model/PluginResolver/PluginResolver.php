<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DemoDataGenerator\Business\Model\PluginResolver;

use Pyz\Zed\DemoDataGenerator\Business\Model\Exception\DemoDataGeneratorPluginNotFoundException;
use Pyz\Zed\DemoDataGenerator\Dependency\Plugin\DemoDataGeneratorPluginInterface;

class PluginResolver implements PluginResolverInterface
{
    /**
     * @var \Pyz\Zed\DemoDataGenerator\Dependency\Plugin\DemoDataGeneratorPluginInterface[]
     */
    protected $configurationPluginStack;

    /**
     * @var \Pyz\Zed\DemoDataGenerator\Dependency\Plugin\DemoDataGeneratorPluginInterface[]
     */
    protected $processPluginStack;

    /**
     * @param array $configurationPluginsStack
     */
    public function __construct(array $configurationPluginsStack)
    {
        $this->configurationPluginStack = $configurationPluginsStack;
    }

    /**
     * @param string $type
     *
     * @throws \Pyz\Zed\DemoDataGenerator\Business\Model\Exception\DemoDataGeneratorPluginNotFoundException
     *
     * @return \Pyz\Zed\DemoDataGenerator\Dependency\Plugin\DemoDataGeneratorPluginInterface
     */
    public function getPluginByType(string $type): DemoDataGeneratorPluginInterface
    {
        $this->collectProcessPluginStack();

        if (isset($this->processPluginStack[$type])) {
            return $this->processPluginStack[$type];
        }

        throw new DemoDataGeneratorPluginNotFoundException();
    }

    /**
     * @return void
     */
    protected function collectProcessPluginStack(): void
    {
        if (!$this->processPluginStack) {
            foreach ($this->configurationPluginStack as $plugin) {
                $this->processPluginStack[$plugin->getType()] = $plugin;
            }
        }
    }
}
