<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Shared\Application;

use Spryker\Shared\Application\ApplicationConstants as SprykerApplicationConstants;

interface ApplicationConstants extends SprykerApplicationConstants
{
    public const ENABLE_ZED_HOST_URL_VALIDATION = 'APPLICATION:ENABLE_ZED_HOST_URL_VALIDATION';

    public const ENABLE_API_APPLICATION_SERVICE_DEBUG = 'APPLICATION:ENABLE_API_APPLICATION_SERVICE_DEBUG';
}
