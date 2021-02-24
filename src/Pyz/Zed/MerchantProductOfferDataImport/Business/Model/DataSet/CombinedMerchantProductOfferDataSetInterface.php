<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\MerchantProductOfferDataImport\Business\Model\DataSet;

interface CombinedMerchantProductOfferDataSetInterface
{
    public const STORE_NAME = 'merchant_product_offer_store.store_name';
    public const PRODUCT_OFFER_REFERENCE = 'product_offer_reference';
    public const CONCRETE_SKU = 'merchant_product_offer.concrete_sku';
    public const MERCHANT_REFERENCE = 'merchant_product_offer.merchant_reference';
    public const MERCHANT_SKU = 'merchant_product_offer.merchant_sku';
    public const IS_ACTIVE = 'merchant_product_offer.is_active';
    public const APPROVAL_STATUS = 'merchant_product_offer.approval_status';
}
