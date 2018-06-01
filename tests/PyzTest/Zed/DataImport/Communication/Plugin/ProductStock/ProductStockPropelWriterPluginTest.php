<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Zed\DataImport\Communication\Plugin\ProductStock;

use Generated\Shared\Transfer\DataImporterReportTransfer;
use Pyz\Zed\DataImport\Communication\Plugin\ProductStock\ProductStockPropelWriterPlugin;
use PyzTest\Zed\DataImport\Communication\Plugin\AbstractWriterPluginTest;

/**
 * Auto-generated group annotations
 * @group PyzTest
 * @group Zed
 * @group DataImport
 * @group Communication
 * @group Plugin
 * @group ProductStock
 * @group ProductStockPropelWriterPluginTest
 * Add your own group annotations below this line
 */
class ProductStockPropelWriterPluginTest extends AbstractWriterPluginTest
{
    public const CSV_IMPORT_FILE = 'import/ProductStock/product_stock.csv';

    /**
     * @return void
     */
    public function testProductStockPropelWriterPlugin(): void
    {
        $dataImportBusinessFactory = $this->getDataImportBusinessFactoryStub();
        $dataImport = $dataImportBusinessFactory->createProductStockImporter();
        $dataImporterReportTransfer = $dataImport->import();
        $this->assertInstanceOf(DataImporterReportTransfer::class, $dataImporterReportTransfer);
    }

    /**
     * @return array
     */
    public function getDataImportWriterPlugins(): array
    {
        return [
            new ProductStockPropelWriterPlugin(),
        ];
    }

    /**
     * @return string
     */
    public function getDataImportCsvFile(): string
    {
        return static::CSV_IMPORT_FILE;
    }
}
