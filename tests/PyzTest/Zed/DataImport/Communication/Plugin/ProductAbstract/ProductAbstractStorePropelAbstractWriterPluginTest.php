<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Zed\DataImport\Communication\Plugin\ProductAbstract;

use Generated\Shared\Transfer\DataImporterReportTransfer;
use Pyz\Zed\DataImport\Communication\Plugin\ProductAbstract\ProductAbstractPropelWriterPlugin;
use PyzTest\Zed\DataImport\Communication\Plugin\AbstractWriterPluginTest;

/**
 * Auto-generated group annotations
 * @group PyzTest
 * @group Zed
 * @group DataImport
 * @group Communication
 * @group Plugin
 * @group ProductAbstract
 * @group ProductAbstractStorePropelAbstractWriterPluginTest
 * Add your own group annotations below this line
 */
class ProductAbstractStorePropelAbstractWriterPluginTest extends AbstractWriterPluginTest
{
    public const CSV_IMPORT_FILE = 'import/ProductAbstract/product_abstract.csv';

    /**
     * @return void
     */
    public function testProductAbstractStorePropelWriterPlugin(): void
    {
        $dataImportBusinessFactory = $this->getDataImportBusinessFactoryStub();
        $dataImport = $dataImportBusinessFactory->createProductAbstractImporter();
        $dataImporterReportTransfer = $dataImport->import();
        $this->assertInstanceOf(DataImporterReportTransfer::class, $dataImporterReportTransfer);
    }

    /**
     * @return array
     */
    public function getDataImportWriterPlugins(): array
    {
        return [
            new ProductAbstractPropelWriterPlugin(),
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
