<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DemoDataGenerator\Business\Model\Generator;

interface DemoDataGeneratorInterface
{
    /**
     * @return void
     */
    public function generate(): void;
}
