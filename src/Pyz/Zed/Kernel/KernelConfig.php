<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Pyz\Zed\Kernel;

use FilesystemIterator;
use Spryker\Zed\Kernel\KernelConfig as SprykerKernelConfig;

class KernelConfig extends SprykerKernelConfig
{
    /**
     * @return array<string>
     */
    public function getPathsToCoreModules(): array
    {
        $pathsToCoreModules = [
            APPLICATION_VENDOR_DIR . '/spryker/*/src/*/*/',
            APPLICATION_VENDOR_DIR . '/spryker-shop/*/src/*/*/',
            APPLICATION_VENDOR_DIR . '/spryker-eco/*/src/*/*/',
        ];

        if ($this->featureExists()) {
            $pathsToCoreModules[] = APPLICATION_VENDOR_DIR . '/spryker/spryker/Features/*/src/*/*/';
        }

        return $pathsToCoreModules;
    }

    /**
     * @project Only needed in nonsplit projects.
     *
     * @return bool
     */
    protected function featureExists(): bool
    {
        $featuresDir = APPLICATION_VENDOR_DIR . '/spryker/spryker/Features/';

        if (is_dir($featuresDir)) {
            $iterator = new FilesystemIterator($featuresDir, FilesystemIterator::SKIP_DOTS);
            foreach ($iterator as $fileinfo) {
                if ($fileinfo->isDir()) {
                    return true;
                }
            }
        }

        return false;
    }
}
