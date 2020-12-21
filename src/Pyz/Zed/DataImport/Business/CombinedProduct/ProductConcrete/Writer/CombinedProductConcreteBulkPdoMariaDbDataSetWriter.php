<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataImport\Business\CombinedProduct\ProductConcrete\Writer;

use Pyz\Zed\DataImport\Business\CombinedProduct\ProductConcrete\CombinedProductConcreteHydratorStep;
use Pyz\Zed\DataImport\Business\Model\DataFormatter\DataImportDataFormatterInterface;
use Pyz\Zed\DataImport\Business\Model\ProductConcrete\Writer\ProductConcreteBulkPdoMariaDbDataSetWriter;
use Pyz\Zed\DataImport\Business\Model\ProductConcrete\Writer\Sql\ProductConcreteSqlInterface;
use Pyz\Zed\DataImport\Business\Model\PropelExecutorInterface;
use Spryker\Zed\DataImport\DataImportConfig;

class CombinedProductConcreteBulkPdoMariaDbDataSetWriter extends ProductConcreteBulkPdoMariaDbDataSetWriter
{
    public const BULK_SIZE = CombinedProductConcreteHydratorStep::BULK_SIZE;

    protected const COLUMN_NAME = CombinedProductConcreteHydratorStep::COLUMN_NAME;
    protected const COLUMN_DESCRIPTION = CombinedProductConcreteHydratorStep::COLUMN_DESCRIPTION;
    protected const COLUMN_IS_SEARCHABLE = CombinedProductConcreteHydratorStep::COLUMN_IS_SEARCHABLE;
    protected const COLUMN_ABSTRACT_SKU = CombinedProductConcreteHydratorStep::COLUMN_ABSTRACT_SKU;

    /**
     * @param \Pyz\Zed\DataImport\Business\Model\ProductConcrete\Writer\Sql\ProductConcreteSqlInterface $productConcreteSql
     * @param \Pyz\Zed\DataImport\Business\Model\PropelExecutorInterface $propelExecutor
     * @param \Pyz\Zed\DataImport\Business\Model\DataFormatter\DataImportDataFormatterInterface $dataFormatter
     * @param \Spryker\Zed\DataImport\DataImportConfig $dataImportConfig
     */
    public function __construct(
        ProductConcreteSqlInterface $productConcreteSql,
        PropelExecutorInterface $propelExecutor,
        DataImportDataFormatterInterface $dataFormatter,
        DataImportConfig $dataImportConfig
    ) {
        parent::__construct($productConcreteSql, $propelExecutor, $dataFormatter, $dataImportConfig);
    }
}
