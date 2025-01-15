<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Pyz\Service\Synchronization;

use Spryker\Service\Synchronization\SynchronizationConfig as SprykerSynchronizationConfig;

class SynchronizationConfig extends SprykerSynchronizationConfig
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return bool
     */
    public function isSingleKeyFormatNormalized(): bool
    {
        return true;
    }
}
