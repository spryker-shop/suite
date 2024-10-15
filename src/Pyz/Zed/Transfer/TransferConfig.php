<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Transfer;

use Spryker\Shared\Transfer\TransferConstants;
use Spryker\Zed\Transfer\TransferConfig as SprykerTransferConfig;

class TransferConfig extends SprykerTransferConfig
{
    /**
     * @return array<string>
     */
    public function getEntitiesSourceDirectories(): array
    {
        return [
            APPLICATION_SOURCE_DIR . '/Orm/Propel/*/Schema/',
        ];
    }

    /**
     * We use strict name validation for core internal usage and enable this by default for all
     * new projects.
     *
     * @return bool
     */
    public function isTransferNameValidated(): bool
    {
        // This should not be merged to projects!
        // We set this to false to ensure that we do not have wrong names used in our transfer schema definitions.
        // When this is set to true, you can use "MyTransfer" as name which leads to "MyTransferTransfer.php".
        // When projects don't have this set to true our code would not work for them as the generated file name would be "MyTransfer.php"
        return false;
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
        return false;
    }

    /**
     * @return bool
     */
    public function isTransferXmlValidationEnabled(): bool
    {
        return true;
    }

    /**
     * @return string
     */
    public function getPropertyDescriptionMergeStrategy(): string
    {
        return TransferConstants::PROPERTY_DESCRIPTION_MERGE_STRATEGY_GET_FIRST;
    }
}
