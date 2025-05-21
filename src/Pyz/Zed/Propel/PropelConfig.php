<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Pyz\Zed\Propel;

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
        $nonsplitDirectories[] = APPLICATION_VENDOR_DIR . '/spryker/spryker/Features/*/src/*/Zed/*/Persistence/Propel/Schema/';

        return array_merge(parent::getCorePropelSchemaPathPatterns(), $nonsplitDirectories);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return bool
     */
    public function isSharedLoggerEnabled(): bool
    {
        return true;
    }
}
