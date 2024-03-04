<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\ProductDiscontinuedStorage;

use Spryker\Client\ProductDiscontinuedStorage\ProductDiscontinuedStorageConfig as SprykerProductDiscontinuedStorageConfig;

class ProductDiscontinuedStorageConfig extends SprykerProductDiscontinuedStorageConfig
{
    /**
     * @return bool
     */
    public function isOnlyDiscontinuedVariantAttributesPostfixEnabled(): bool
    {
        return true;
    }
}
