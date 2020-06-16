<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataImport\Business\CombinedProductImporter\ProductImage\Writer;

use Pyz\Zed\DataImport\Business\CombinedProductImporter\ProductImage\ProductImageHydratorStep;
use Pyz\Zed\DataImport\Business\Model\DataFormatter\DataImportDataFormatterInterface;
use Pyz\Zed\DataImport\Business\Model\ProductImage\Writer\ProductImageBulkPdoDataSetWriter as WriterProductImageBulkPdoDataSetWriter;
use Pyz\Zed\DataImport\Business\Model\ProductImage\Writer\Sql\ProductImageSqlInterface;
use Pyz\Zed\DataImport\Business\Model\PropelExecutorInterface;

class ProductImageBulkPdoDataSetWriter extends WriterProductImageBulkPdoDataSetWriter
{
    protected const COLUMN_EXTERNAL_URL_LARGE = ProductImageHydratorStep::COLUMN_EXTERNAL_URL_LARGE;
    protected const COLUMN_EXTERNAL_URL_SMALL = ProductImageHydratorStep::COLUMN_EXTERNAL_URL_SMALL;
    protected const COLUMN_PRODUCT_IMAGE_KEY = ProductImageHydratorStep::COLUMN_PRODUCT_IMAGE_KEY;
    protected const COLUMN_SORT_ORDER = ProductImageHydratorStep::COLUMN_SORT_ORDER;

    /**
     * @param \Pyz\Zed\DataImport\Business\Model\ProductImage\Writer\Sql\ProductImageSqlInterface $productImageSql
     * @param \Pyz\Zed\DataImport\Business\Model\PropelExecutorInterface $propelExecutor
     * @param \Pyz\Zed\DataImport\Business\Model\DataFormatter\DataImportDataFormatterInterface $dataFormatter
     */
    public function __construct(
        ProductImageSqlInterface $productImageSql,
        PropelExecutorInterface $propelExecutor,
        DataImportDataFormatterInterface $dataFormatter
    ) {
        parent::__construct($productImageSql, $propelExecutor, $dataFormatter);
    }
}
