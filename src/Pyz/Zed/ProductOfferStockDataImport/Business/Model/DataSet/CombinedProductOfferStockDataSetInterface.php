<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductOfferStockDataImport\Business\Model\DataSet;

interface CombinedProductOfferStockDataSetInterface
{
    public const PRODUCT_OFFER_REFERENCE = 'product_offer_reference';
    public const STOCK_NAME = 'product_offer_stock.stock_name';
    public const QUANTITY = 'product_offer_stock.quantity';
    public const IS_NEVER_OUT_OF_STOCK = 'product_offer_stock.is_never_out_of_stock';
    public const STORE_NAME = 'merchant_product_offer_store.store_name';
}
