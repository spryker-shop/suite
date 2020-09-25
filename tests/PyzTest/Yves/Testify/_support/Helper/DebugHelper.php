<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Yves\Testify\Helper;

use Codeception\Module;
use Spryker\Shared\Config\Config;
use Spryker\Shared\ZedRequest\ZedRequestConstants;
use SprykerTest\Shared\Testify\Helper\ConfigHelperTrait;

/**
 * This helper enables cookie forwarding to debug Zed during yves - zed requests.
 */
class DebugHelper extends Module
{
    use ConfigHelperTrait;

    /**
     * @param array $settings
     *
     * @return void
     */
    public function _beforeSuite($settings = []): void
    {
        $_COOKIE[Config::get(ZedRequestConstants::TRANSFER_DEBUG_SESSION_NAME)] = 'XDEBUG_ECLIPSE';
        $this->getConfigHelper()->setConfig(ZedRequestConstants::TRANSFER_DEBUG_SESSION_FORWARD_ENABLED, true);
    }
}
