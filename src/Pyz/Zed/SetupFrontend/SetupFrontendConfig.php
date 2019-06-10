<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\SetupFrontend;

use Spryker\Zed\SetupFrontend\SetupFrontendConfig as SprykerSetupFrontendConfig;

class SetupFrontendConfig extends SprykerSetupFrontendConfig
{
    /**
     * @return string[]
     */
    public function getYvesAssetsDirectories(): array
    {
        return [
            APPLICATION_ROOT_DIR . '/public/Yves/assets/%store%',
        ];
    }
}
