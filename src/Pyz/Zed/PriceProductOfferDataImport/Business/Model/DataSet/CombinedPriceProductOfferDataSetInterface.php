<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\PriceProductOfferDataImport\Business\Model\DataSet;

interface CombinedPriceProductOfferDataSetInterface
{
    public const PRODUCT_OFFER_REFERENCE = 'product_offer_reference';
    public const CONCRETE_SKU = 'merchant_product_offer.concrete_sku';
    public const PRICE_TYPE = 'price_product_offer.price_type';
    public const STORE = 'price_product_offer.store';
    public const CURRENCY = 'price_product_offer.currency';
    public const VALUE_NET = 'price_product_offer.value_net';
    public const VALUE_GROSS = 'price_product_offer.value_gross';
    public const PRICE_DATA_VOLUME_PRICES = 'price_product_offer.price_data.volume_prices';
}
