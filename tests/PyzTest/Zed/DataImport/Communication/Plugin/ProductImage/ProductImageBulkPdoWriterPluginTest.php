<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Zed\DataImport\Communication\Plugin\ProductImage;

use Generated\Shared\Transfer\DataImportConfigurationActionTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use Pyz\Zed\DataImport\Communication\Plugin\ProductImage\ProductImageBulkPdoWriterPlugin;
use PyzTest\Zed\DataImport\Communication\Plugin\AbstractWriterPluginTest;

/**
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Zed
 * @group DataImport
 * @group Communication
 * @group Plugin
 * @group ProductImage
 * @group ProductImageBulkPdoWriterPluginTest
 * Add your own group annotations below this line
 */
class ProductImageBulkPdoWriterPluginTest extends AbstractWriterPluginTest
{
    /**
     * @var string
     */
    public const CSV_IMPORT_FILE = 'import/ProductImage/product_image.csv';

    /**
     * @var string
     */
    public const DATA_IMPORTER_TYPE = 'product-image';

    /**
     * @return void
     */
    public function testProductImagePropelWriterPlugin(): void
    {
        $this->markTestSkippedOnDatabaseConstraintsMismatch();

        $dataImportBusinessFactory = $this->getDataImportBusinessFactoryStub();
        $dataImport = $dataImportBusinessFactory->createProductImageImporter($this->getDataImportConfigurationActionTransfer());
        $dataImporterReportTransfer = $dataImport->import();
        $this->assertInstanceOf(DataImporterReportTransfer::class, $dataImporterReportTransfer);
    }

    /**
     * @return array
     */
    public function getDataImportWriterPlugins(): array
    {
        return [
            new ProductImageBulkPdoWriterPlugin(),
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
