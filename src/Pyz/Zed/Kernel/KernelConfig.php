<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Kernel;

use Spryker\Zed\Kernel\KernelConfig as SprykerKernelConfig;

class KernelConfig extends SprykerKernelConfig
{
    /**
     * @return string[]
     */
    public function getPathsToCoreModules(): array
    {
        return [
            APPLICATION_VENDOR_DIR . '/spryker/*/src/*/*/',
            APPLICATION_VENDOR_DIR . '/spryker/spryker/Bundles/*/src/*/*/',
            APPLICATION_VENDOR_DIR . '/spryker/spryker-shop/Bundles/*/src/*/*/',
            APPLICATION_VENDOR_DIR . '/spryker-eco/*/src/*/*/',
        ];
    }
}
