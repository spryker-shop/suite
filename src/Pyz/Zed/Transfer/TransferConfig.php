<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Transfer;

use Spryker\Zed\Transfer\TransferConfig as SprykerTransferConfig;

class TransferConfig extends SprykerTransferConfig
{
    /**
     * @project Only needed in Project, not in demoshop
     *
     * @return array<string>
     */
    protected function getCoreSourceDirectoryGlobPatterns(): array
    {
        $directoryGlobPatterns = parent::getCoreSourceDirectoryGlobPatterns();
        $directoryGlobPatterns[] = APPLICATION_VENDOR_DIR . '/spryker/spryker/Bundles/*/src/Spryker/Shared/*/Transfer/';
        $directoryGlobPatterns[] = APPLICATION_VENDOR_DIR . '/spryker/spryker-shop/Bundles/*/src/SprykerShop/Shared/*/Transfer/';
        $directoryGlobPatterns[] = APPLICATION_VENDOR_DIR . '/spryker/rabbit-mq/src/*/Shared/*/Transfer/';
        $directoryGlobPatterns[] = APPLICATION_VENDOR_DIR . '/spryker-eco/*/src/*/Shared/*/Transfer/';
        $directoryGlobPatterns[] = APPLICATION_VENDOR_DIR . '/spryker-sdk/*/src/*/Shared/*/Transfer/';

        return $directoryGlobPatterns;
    }

    /**
     * @project Only needed in Project, not in demoshop
     *
     * @return array<string>
     */
    public function getDataBuilderSourceDirectories(): array
    {
        $globPatterns = parent::getDataBuilderSourceDirectories();
        $globPatterns[] = APPLICATION_VENDOR_DIR . '/spryker/spryker/Bundles/*/tests/_data/';

        return $globPatterns;
    }

    /**
     * We use strict name validation for core internal usage and enable this by default for all
     * new projects.
     *
     * @return bool
     */
    public function isTransferNameValidated(): bool
    {
        return true;
    }

    /**
     * We use strict validation for case sensitive declaration for all new projects.
     *
     * @return bool
     */
    public function isCaseValidated(): bool
    {
        return true;
    }

    /**
     * We use strict validation for collections and singular definition for all new projects.
     *
     * @return bool
     */
    public function isSingularRequired(): bool
    {
        return true;
    }

    /**
     * @return bool
     */
    public function isTransferXmlValidationEnabled(): bool
    {
        return true;
    }
}
