<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductOfferStockDataImport\Business\Model\Step;

use Pyz\Zed\ProductOfferStockDataImport\Business\Model\DataSet\CombinedProductOfferStockDataSetInterface;
use Spryker\Zed\ProductOfferStockDataImport\Business\Step\StockNameToIdStockStep;

class CombinedStockNameToIdStockStep extends StockNameToIdStockStep
{
    protected const PRODUCT_STOCK_NAME = CombinedProductOfferStockDataSetInterface::STOCK_NAME;
    protected const STORE_NAME = CombinedProductOfferStockDataSetInterface::STORE_NAME;
}
