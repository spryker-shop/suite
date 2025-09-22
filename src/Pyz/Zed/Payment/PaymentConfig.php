<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Pyz\Zed\Payment;

use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\GiftCardTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\TaxTotalTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use Spryker\Zed\Payment\PaymentConfig as SprykerPaymentConfig;

class PaymentConfig extends SprykerPaymentConfig
{
 /**
  * @example
  * [
  *     QuoteTransfer::ORDER_REFERENCE => 'orderReference',
  *     QuoteTransfer::ITEMS => [
  *         ItemTransfer::NAME => 'itemName',
  *         ItemTransfer::ABSTRACT_SKU => 'abstractSku',
  *     ],
  * ]
  *
  * @return array<mixed>
  */
    public function getQuoteFieldsForForeignPayment(): array
    {
        return array_merge_recursive(parent::getQuoteFieldsForForeignPayment(), [
            QuoteTransfer::TOTALS => [
                TotalsTransfer::DISCOUNT_TOTAL => 'discountTotal',
                TotalsTransfer::TAX_TOTAL => [
                    TaxTotalTransfer::AMOUNT => 'taxTotal',
                ],
            ],
            QuoteTransfer::ITEMS => [
                ItemTransfer::TAX_RATE => 'taxRate',
                ItemTransfer::SUM_TAX_AMOUNT => 'taxAmount',
                ItemTransfer::SUM_DISCOUNT_AMOUNT_AGGREGATION => 'discountAmount',
            ],
            QuoteTransfer::EXPENSES => [
                ExpenseTransfer::TAX_RATE => 'taxRate',
                ExpenseTransfer::SUM_TAX_AMOUNT => 'taxAmount',
            ],
            QuoteTransfer::GIFT_CARDS => [
                GiftCardTransfer::ACTUAL_VALUE => 'actualValue',
            ],
        ]);
    }
}
