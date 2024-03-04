<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Service\ProductConfiguration;

use Generated\Shared\Transfer\ProductConfigurationInstanceTransfer;
use Spryker\Service\ProductConfiguration\ProductConfigurationConfig as SprykerProductConfigurationConfig;

class ProductConfigurationConfig extends SprykerProductConfigurationConfig
{
    /**
     * @return list<string>
     */
    public function getConfigurationFieldsNotAllowedForEncoding(): array
    {
        return [
            ProductConfigurationInstanceTransfer::QUANTITY,
        ];
    }
}
