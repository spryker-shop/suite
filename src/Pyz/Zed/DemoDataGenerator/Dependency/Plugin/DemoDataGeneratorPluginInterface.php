<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DemoDataGenerator\Dependency\Plugin;

use Generated\Shared\Transfer\DemoDataGeneratorTransfer;

interface DemoDataGeneratorPluginInterface
{
    /**
     * @return string
     */
    public function getType(): string;

    /**
     * @param \Generated\Shared\Transfer\DemoDataGeneratorTransfer $demoDataGeneratorTransfer
     *
     * @return void
     */
    public function generateDemoData(DemoDataGeneratorTransfer $demoDataGeneratorTransfer): void;
}
