<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Pyz\Zed\Propel;

use FilesystemIterator;
use Spryker\Zed\Propel\PropelConfig as SprykerPropelConfig;

class PropelConfig extends SprykerPropelConfig
{
    /**
     * @project Only needed in nonsplit projects.
     *
     * @return array<string>
     */
    public function getCorePropelSchemaPathPatterns(): array
    {
        $nonsplitDirectories = [APPLICATION_VENDOR_DIR . '/spryker/spryker/Bundles/*/src/*/Zed/*/Persistence/Propel/Schema/'];

        if ($this->featureExists()) {
            $nonsplitDirectories[] = APPLICATION_VENDOR_DIR . '/spryker/spryker/Features/*/src/*/Zed/*/Persistence/Propel/Schema/';
        }

        return array_merge(parent::getCorePropelSchemaPathPatterns(), $nonsplitDirectories);
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
