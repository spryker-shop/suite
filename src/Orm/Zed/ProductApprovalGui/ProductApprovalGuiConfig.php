<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Orm\Zed\ProductApprovalGui;

use Spryker\Zed\ProductApprovalGui\ProductApprovalGuiConfig as SprykerProductApprovalGuiConfig;

class ProductApprovalGuiConfig extends SprykerProductApprovalGuiConfig
{
    /**
     * @return bool
     */
    public function isApprovalStatusTreeCustomizationEnabled(): bool
    {
        return true;
    }
}
