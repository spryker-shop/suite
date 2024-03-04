<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Zed\DataImport\Communication\Plugin\ProductConcrete;

use Generated\Shared\Transfer\DataImportConfigurationActionTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use Pyz\Zed\DataImport\Communication\Plugin\ProductConcrete\ProductConcreteBulkPdoWriterPlugin;
use PyzTest\Zed\DataImport\Communication\Plugin\AbstractWriterPluginTest;

/**
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Zed
 * @group DataImport
 * @group Communication
 * @group Plugin
 * @group ProductConcrete
 * @group ProductConcreteBulkPdoWriterPluginTest
 * Add your own group annotations below this line
 */
class ProductConcreteBulkPdoWriterPluginTest extends AbstractWriterPluginTest
{
    /**
     * @var string
     */
    public const CSV_IMPORT_FILE = 'import/ProductConcrete/product_concrete.csv';

    /**
     * @var string
     */
    public const DATA_IMPORTER_TYPE = 'product-concrete';

    /**
     * @return void
     */
    public function testProductConcreteStorePropelWriterPlugin(): void
    {
        $this->markTestSkippedOnDatabaseConstraintsMismatch();

        $dataImportBusinessFactory = $this->getDataImportBusinessFactoryStub();
        $dataImport = $dataImportBusinessFactory->createProductConcreteImporter($this->getDataImportConfigurationActionTransfer());
        $dataImporterReportTransfer = $dataImport->import();
        $this->assertInstanceOf(DataImporterReportTransfer::class, $dataImporterReportTransfer);
    }

    /**
     * @return array
     */
    public function getDataImportWriterPlugins(): array
    {
        return [
            new ProductConcreteBulkPdoWriterPlugin(),
        ];
    }

    /**
     * @return \Generated\Shared\Transfer\DataImportConfigurationActionTransfer
     */
    public function getDataImportConfigurationActionTransfer(): DataImportConfigurationActionTransfer
    {
        return (new DataImportConfigurationActionTransfer())
            ->setDataEntity(static::DATA_IMPORTER_TYPE)
            ->setSource(static::CSV_IMPORT_FILE);
    }
}
