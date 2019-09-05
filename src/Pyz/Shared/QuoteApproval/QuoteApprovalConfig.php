<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Shared\QuoteApproval;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\QuoteApproval\QuoteApprovalConfig as SprykerQuoteApprovalConfig;

class QuoteApprovalConfig extends SprykerQuoteApprovalConfig
{
    /**
     * @return array
     */
    public function getRequiredQuoteFields(): array
    {
        return [
            QuoteTransfer::BILLING_ADDRESS,
            QuoteTransfer::SHIPPING_ADDRESS,
            QuoteTransfer::PAYMENTS,
            QuoteTransfer::SHIPMENT,
        ];
    }
}
