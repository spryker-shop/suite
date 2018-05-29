<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DemoDataGenerator\Business\Model;

use Pyz\Zed\DemoDataGenerator\Business\Model\FileManager\FileManagerInterface;
use Pyz\Zed\DemoDataGenerator\DemoDataGeneratorConfig;

abstract class AbstractGenerator
{
    /**
     * @var \Pyz\Zed\DemoDataGenerator\Business\Model\FileManager\FileManagerInterface
     */
    protected $fileManager;

    /**
     * @var \Pyz\Zed\DemoDataGenerator\DemoDataGeneratorConfig
     */
    protected $config;

    /**
     * @param \Pyz\Zed\DemoDataGenerator\Business\Model\FileManager\FileManagerInterface $fileManager
     * @param \Pyz\Zed\DemoDataGenerator\DemoDataGeneratorConfig $config
     */
    public function __construct(
        FileManagerInterface $fileManager,
        DemoDataGeneratorConfig $config
    ) {
        $this->fileManager = $fileManager;
        $this->config = $config;
    }

    /**
     * @return \Pyz\Zed\DemoDataGenerator\Business\Model\FileManager\FileManagerInterface
     */
    protected function getFileManager(): FileManagerInterface
    {
        return $this->fileManager;
    }

    /**
     * @return \Pyz\Zed\DemoDataGenerator\DemoDataGeneratorConfig
     */
    protected function getConfig(): DemoDataGeneratorConfig
    {
        return $this->config;
    }
}
