<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataImport\Business\CombinedProduct\ProductStock\Writer;

use Pyz\Zed\DataImport\Business\CombinedProduct\ProductStock\CombinedProductStockHydratorStep;
use Pyz\Zed\DataImport\Business\Model\ProductStock\Writer\ProductStockBulkPdoDataSetWriter;

class CombinedProductStockBulkPdoDataSetWriter extends ProductStockBulkPdoDataSetWriter
{
    public const BULK_SIZE = CombinedProductStockHydratorStep::BULK_SIZE;

    protected const COLUMN_NAME = CombinedProductStockHydratorStep::COLUMN_NAME;
    protected const COLUMN_CONCRETE_SKU = CombinedProductStockHydratorStep::COLUMN_CONCRETE_SKU;
    protected const COLUMN_IS_BUNDLE = CombinedProductStockHydratorStep::COLUMN_IS_BUNDLE;
    protected const COLUMN_QUANTITY = CombinedProductStockHydratorStep::COLUMN_QUANTITY;
    protected const COLUMN_IS_NEVER_OUT_OF_STOCK = CombinedProductStockHydratorStep::COLUMN_IS_NEVER_OUT_OF_STOCK;
}
