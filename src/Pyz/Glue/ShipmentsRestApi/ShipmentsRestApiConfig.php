<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Glue\ShipmentsRestApi;

use Spryker\Glue\ShipmentsRestApi\ShipmentsRestApiConfig as SprykerShipmentsRestApiConfig;

class ShipmentsRestApiConfig extends SprykerShipmentsRestApiConfig
{
    /**
     * @return bool
     */
    public function shouldExecuteShippingAddressValidationStrategyPlugins(): bool
    {
        return true;
    }
}
