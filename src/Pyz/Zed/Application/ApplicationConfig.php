<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Application;

use Pyz\Shared\Application\ApplicationConstants;
use Spryker\Zed\Application\ApplicationConfig as SprykerApplicationConfig;

class ApplicationConfig extends SprykerApplicationConfig
{
    /**
     * @return bool
     */
    public function isZedHostUrlValidationEnabled(): bool
    {
        return $this->get(ApplicationConstants::ENABLE_ZED_HOST_URL_VALIDATION, $this->getEnvironmentName() === 'development');
    }

    /**
     * @return bool
     */
    public function isApiApplicationServiceDebugEnabled(): bool
    {
        return $this->get(ApplicationConstants::ENABLE_API_APPLICATION_SERVICE_DEBUG, $this->getEnvironmentName() === 'development');
    }
}
