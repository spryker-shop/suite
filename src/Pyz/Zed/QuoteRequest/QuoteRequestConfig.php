<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\QuoteRequest;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\QuoteRequest\QuoteRequestConfig as SprykerQuoteRequestConfig;

class QuoteRequestConfig extends SprykerQuoteRequestConfig
{
    /**
     * @return array
     */
    public function getQuoteFieldsAllowedForSaving(): array
    {
        return array_merge(parent::getQuoteFieldsAllowedForSaving(), [
            QuoteTransfer::ITEMS,
            QuoteTransfer::TOTALS,
            QuoteTransfer::CURRENCY,
            QuoteTransfer::PRICE_MODE,
            QuoteTransfer::BUNDLE_ITEMS,
            QuoteTransfer::VOUCHER_DISCOUNTS,
        ]);
    }
}
