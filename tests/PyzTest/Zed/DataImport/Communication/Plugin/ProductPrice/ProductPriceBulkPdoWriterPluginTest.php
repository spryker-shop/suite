<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Zed\DataImport\Communication\Plugin\ProductPrice;

use Generated\Shared\Transfer\DataImporterReportTransfer;
use Pyz\Zed\DataImport\Communication\Plugin\ProductPrice\ProductPriceBulkPdoWriterPlugin;
use PyzTest\Zed\DataImport\Communication\Plugin\AbstractWriterPluginTest;

/**
 * Auto-generated group annotations
 * @group PyzTest
 * @group Zed
 * @group DataImport
 * @group Communication
 * @group Plugin
 * @group ProductPrice
 * @group ProductPriceBulkPdoWriterPluginTest
 * Add your own group annotations below this line
 */
class ProductPriceBulkPdoWriterPluginTest extends AbstractWriterPluginTest
{
    public const CSV_IMPORT_FILE = 'import/ProductPrice/product_price.csv';

    /**
     * @return void
     */
    public function testProductPricePdoImport(): void
    {
        $dataImportBusinessFactory = $this->getDataImportBusinessFactoryStub();
        $dataImport = $dataImportBusinessFactory->createProductPriceImporter();
        $dataImporterReportTransfer = $dataImport->import();
        $this->assertInstanceOf(DataImporterReportTransfer::class, $dataImporterReportTransfer);
    }

    /**
     * @return array
     */
    public function getDataImportWriterPlugins(): array
    {
        return [
            new ProductPriceBulkPdoWriterPlugin(),
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
