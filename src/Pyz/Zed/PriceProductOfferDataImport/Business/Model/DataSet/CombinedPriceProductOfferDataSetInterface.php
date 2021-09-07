<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\PriceProductOfferDataImport\Business\Model\DataSet;

interface CombinedPriceProductOfferDataSetInterface
{
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
    public const PRICE_TYPE = 'price_product_offer.price_type';
    /**
     * @var string
     */
    public const STORE = 'price_product_offer.store';
    /**
     * @var string
     */
    public const CURRENCY = 'price_product_offer.currency';
    /**
     * @var string
     */
    public const VALUE_NET = 'price_product_offer.value_net';
    /**
     * @var string
     */
    public const VALUE_GROSS = 'price_product_offer.value_gross';
    /**
     * @var string
     */
    public const PRICE_DATA_VOLUME_PRICES = 'price_product_offer.price_data.volume_prices';
}
