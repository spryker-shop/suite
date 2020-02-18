<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\OrderCustomReference;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\OrderCustomReference\OrderCustomReferenceConfig as SprykerOrderCustomReferenceConfig;

class OrderCustomReferenceConfig extends SprykerOrderCustomReferenceConfig
{
    /**
     * @return string[]
     */
    public function getOrderCustomReferenceQuoteFieldsAllowedForSaving(): array
    {
        return [
            QuoteTransfer::ORDER_CUSTOM_REFERENCE,
        ];
    }
}
