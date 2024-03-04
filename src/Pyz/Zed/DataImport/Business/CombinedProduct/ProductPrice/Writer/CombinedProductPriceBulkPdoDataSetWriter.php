<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataImport\Business\CombinedProduct\ProductPrice\Writer;

use Pyz\Zed\DataImport\Business\CombinedProduct\ProductPrice\CombinedProductPriceHydratorStep;
use Pyz\Zed\DataImport\Business\Model\ProductPrice\Writer\ProductPriceBulkPdoDataSetWriter;

class CombinedProductPriceBulkPdoDataSetWriter extends ProductPriceBulkPdoDataSetWriter
{
    protected const BULK_SIZE = CombinedProductPriceHydratorStep::BULK_SIZE;

    protected const COLUMN_PRICE_TYPE = CombinedProductPriceHydratorStep::COLUMN_PRICE_TYPE;

    protected const COLUMN_PRICE_DATA = CombinedProductPriceHydratorStep::COLUMN_PRICE_DATA;

    protected const COLUMN_PRICE_DATA_CHECKSUM = CombinedProductPriceHydratorStep::COLUMN_PRICE_DATA_CHECKSUM;

    protected const COLUMN_STORE = CombinedProductPriceHydratorStep::COLUMN_STORE;

    protected const COLUMN_CURRENCY = CombinedProductPriceHydratorStep::COLUMN_CURRENCY;
}
