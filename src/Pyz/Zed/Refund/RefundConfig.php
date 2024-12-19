<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Pyz\Zed\Refund;

use Spryker\Zed\Refund\RefundConfig as SprykerRefundConfig;

class RefundConfig extends SprykerRefundConfig
{
    /**
     * @return bool
     */
    public function shouldCleanupRecalculationMessagesAfterRefund(): bool
    {
        return true;
    }
}
