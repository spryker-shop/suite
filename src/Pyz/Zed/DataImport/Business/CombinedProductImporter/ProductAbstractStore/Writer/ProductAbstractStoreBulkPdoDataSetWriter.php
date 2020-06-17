<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataImport\Business\CombinedProductImporter\ProductAbstractStore\Writer;

use Pyz\Zed\DataImport\Business\CombinedProductImporter\ProductAbstractStore\ProductAbstractStoreHydratorStep;
use Pyz\Zed\DataImport\Business\Model\DataFormatter\DataImportDataFormatterInterface;
use Pyz\Zed\DataImport\Business\Model\ProductAbstractStore\Writer\ProductAbstractStoreBulkPdoDataSetWriter as WriterProductAbstractStoreBulkPdoDataSetWriter;
use Pyz\Zed\DataImport\Business\Model\ProductAbstractStore\Writer\Sql\ProductAbstractStoreSqlInterface;
use Pyz\Zed\DataImport\Business\Model\PropelExecutorInterface;

class ProductAbstractStoreBulkPdoDataSetWriter extends WriterProductAbstractStoreBulkPdoDataSetWriter
{
    public const COLUMN_ABSTRACT_SKU = ProductAbstractStoreHydratorStep::COLUMN_ABSTRACT_SKU;
    public const COLUMN_STORE_NAME = ProductAbstractStoreHydratorStep::COLUMN_STORE_NAME;

    /**
     * @param \Pyz\Zed\DataImport\Business\Model\ProductAbstractStore\Writer\Sql\ProductAbstractStoreSqlInterface $productAbstractStoreSql
     * @param \Pyz\Zed\DataImport\Business\Model\PropelExecutorInterface $propelExecutor
     * @param \Pyz\Zed\DataImport\Business\Model\DataFormatter\DataImportDataFormatterInterface $dataFormatter
     */
    public function __construct(
        ProductAbstractStoreSqlInterface $productAbstractStoreSql,
        PropelExecutorInterface $propelExecutor,
        DataImportDataFormatterInterface $dataFormatter
    ) {
        parent::__construct($productAbstractStoreSql, $propelExecutor, $dataFormatter);
    }
}
