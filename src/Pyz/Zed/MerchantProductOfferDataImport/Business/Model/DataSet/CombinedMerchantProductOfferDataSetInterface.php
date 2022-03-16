<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\MerchantProductOfferDataImport\Business\Model\DataSet;

interface CombinedMerchantProductOfferDataSetInterface
{
    /**
     * @var string
     */
    public const STORE_NAME = 'merchant_product_offer_store.store_name';

    /**
     * @var string
     */
    public const PRODUCT_OFFER_REFERENCE = 'product_offer_reference';

    /**
     * @var string
     */
    public const CONCRETE_SKU = 'merchant_product_offer.concrete_sku';

    /**
     * @var string
     */
    public const MERCHANT_REFERENCE = 'merchant_product_offer.merchant_reference';

    /**
     * @var string
     */
    public const MERCHANT_SKU = 'merchant_product_offer.merchant_sku';

    /**
     * @var string
     */
    public const IS_ACTIVE = 'merchant_product_offer.is_active';

    /**
     * @var string
     */
    public const APPROVAL_STATUS = 'merchant_product_offer.approval_status';
}
