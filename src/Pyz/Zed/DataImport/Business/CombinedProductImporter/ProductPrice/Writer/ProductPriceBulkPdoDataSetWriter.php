<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataImport\Business\CombinedProductImporter\ProductPrice\Writer;

use Pyz\Zed\DataImport\Business\CombinedProductImporter\ProductPrice\ProductPriceHydratorStep;
use Pyz\Zed\DataImport\Business\Model\DataFormatter\DataImportDataFormatterInterface;
use Pyz\Zed\DataImport\Business\Model\ProductPrice\Writer\ProductPriceBulkPdoDataSetWriter as WriterProductPriceBulkPdoDataSetWriter;
use Pyz\Zed\DataImport\Business\Model\ProductPrice\Writer\Sql\ProductPriceSqlInterface;
use Pyz\Zed\DataImport\Business\Model\PropelExecutorInterface;

class ProductPriceBulkPdoDataSetWriter extends WriterProductPriceBulkPdoDataSetWriter
{
    protected const BULK_SIZE = ProductPriceHydratorStep::BULK_SIZE;

    protected const COLUMN_PRICE_TYPE = ProductPriceHydratorStep::COLUMN_PRICE_TYPE;
    protected const COLUMN_PRICE_DATA = ProductPriceHydratorStep::COLUMN_PRICE_DATA;
    protected const COLUMN_PRICE_DATA_CHECKSUM = ProductPriceHydratorStep::COLUMN_PRICE_DATA_CHECKSUM;
    protected const COLUMN_STORE = ProductPriceHydratorStep::COLUMN_STORE;
    protected const COLUMN_CURRENCY = ProductPriceHydratorStep::COLUMN_CURRENCY;

    /**
     * @param \Pyz\Zed\DataImport\Business\Model\ProductPrice\Writer\Sql\ProductPriceSqlInterface $productPriceSql
     * @param \Pyz\Zed\DataImport\Business\Model\PropelExecutorInterface $propelExecutor
     * @param \Pyz\Zed\DataImport\Business\Model\DataFormatter\DataImportDataFormatterInterface $dataFormatter
     */
    public function __construct(
        ProductPriceSqlInterface $productPriceSql,
        PropelExecutorInterface $propelExecutor,
        DataImportDataFormatterInterface $dataFormatter
    ) {
        parent::__construct($productPriceSql, $propelExecutor, $dataFormatter);
    }
}
