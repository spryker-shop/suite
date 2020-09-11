<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\ProductConfiguration;

use Spryker\Client\ProductConfiguration\ProductConfigurationConfig as SprykerProductConfigurationConfig;
use Spryker\Shared\Application\ApplicationConstants;

class ProductConfigurationConfig extends SprykerProductConfigurationConfig
{
    /**
     * @api
     *
     * @return string
     */
    public function getYvesBaseUrl(): string
    {
        return $this->get(ApplicationConstants::BASE_URL_YVES);
    }
}
