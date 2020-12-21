<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataImport\Business\CombinedProduct\ProductAbstractStore\Writer;

use Pyz\Zed\DataImport\Business\CombinedProduct\ProductAbstractStore\CombinedProductAbstractStoreHydratorStep;
use Pyz\Zed\DataImport\Business\Model\DataFormatter\DataImportDataFormatterInterface;
use Pyz\Zed\DataImport\Business\Model\ProductAbstractStore\Writer\ProductAbstractStoreBulkPdoMariaDbDataSetWriter;
use Pyz\Zed\DataImport\Business\Model\ProductAbstractStore\Writer\Sql\ProductAbstractStoreSqlInterface;
use Pyz\Zed\DataImport\Business\Model\PropelExecutorInterface;
use Spryker\Zed\DataImport\DataImportConfig;

class CombinedProductAbstractStoreBulkPdoMariaDbDataSetWriter extends ProductAbstractStoreBulkPdoMariaDbDataSetWriter
{
    public const COLUMN_ABSTRACT_SKU = CombinedProductAbstractStoreHydratorStep::COLUMN_ABSTRACT_SKU;
    public const COLUMN_STORE_NAME = CombinedProductAbstractStoreHydratorStep::COLUMN_STORE_NAME;

    /**
     * @param \Pyz\Zed\DataImport\Business\Model\ProductAbstractStore\Writer\Sql\ProductAbstractStoreSqlInterface $productAbstractStoreSql
     * @param \Pyz\Zed\DataImport\Business\Model\PropelExecutorInterface $propelExecutor
     * @param \Pyz\Zed\DataImport\Business\Model\DataFormatter\DataImportDataFormatterInterface $dataFormatter
     * @param \Spryker\Zed\DataImport\DataImportConfig $dataImportConfig
     */
    public function __construct(
        ProductAbstractStoreSqlInterface $productAbstractStoreSql,
        PropelExecutorInterface $propelExecutor,
        DataImportDataFormatterInterface $dataFormatter,
        DataImportConfig $dataImportConfig
    ) {
        parent::__construct($productAbstractStoreSql, $propelExecutor, $dataFormatter, $dataImportConfig);
    }
}
