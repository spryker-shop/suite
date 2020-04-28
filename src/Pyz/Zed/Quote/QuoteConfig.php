<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Quote;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Quote\QuoteConfig as SprykerQuoteConfig;

class QuoteConfig extends SprykerQuoteConfig
{
    /**
     * @return string[]
     */
    public function getQuoteFieldsAllowedForSaving()
    {
        return array_merge(parent::getQuoteFieldsAllowedForSaving(), [
            QuoteTransfer::BUNDLE_ITEMS,
            QuoteTransfer::CART_NOTE, #CartNoteFeature,
            QuoteTransfer::EXPENSES, #QuoteApprovalFeature
            QuoteTransfer::VOUCHER_DISCOUNTS, #QuoteApprovalFeature
            QuoteTransfer::GIFT_CARDS,
            QuoteTransfer::CART_RULE_DISCOUNTS, #QuoteApprovalFeature
            QuoteTransfer::PROMOTION_ITEMS, #QuoteApprovalFeature
            QuoteTransfer::IS_LOCKED, #QuoteApprovalFeature
            QuoteTransfer::QUOTE_REQUEST_VERSION_REFERENCE,
            QuoteTransfer::QUOTE_REQUEST_REFERENCE,
            QuoteTransfer::MERCHANT_REFERENCE,
        ]);
    }

    /**
     * @return string[]
     */
    public function getQuoteItemFieldsAllowedForSaving(): array
    {
        return [
            ItemTransfer::ID,
            ItemTransfer::SKU,
            ItemTransfer::GROUP_KEY,
            ItemTransfer::GROUP_KEY_PREFIX,
            ItemTransfer::QUANTITY,
            ItemTransfer::ID_PRODUCT_ABSTRACT,
            ItemTransfer::IMAGES,
            ItemTransfer::NAME,
            ItemTransfer::UNIT_PRICE,
            ItemTransfer::SUM_PRICE,
            ItemTransfer::UNIT_GROSS_PRICE,
            ItemTransfer::SUM_GROSS_PRICE,
            ItemTransfer::IS_ORDERED,
            ItemTransfer::CONFIGURED_BUNDLE,
            ItemTransfer::GIFT_CARD_METADATA,
        ];
    }
}
