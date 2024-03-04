<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Zed\DataImport\Communication\Plugin\CombinedProduct\ProductImage;

use Generated\Shared\Transfer\DataImportConfigurationActionTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use Pyz\Zed\DataImport\Communication\Plugin\CombinedProduct\ProductImage\CombinedProductImagePropelWriterPlugin;
use PyzTest\Zed\DataImport\Communication\Plugin\AbstractWriterPluginTest;

/**
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Zed
 * @group DataImport
 * @group Communication
 * @group Plugin
 * @group CombinedProduct
 * @group ProductImage
 * @group CombinedProductImagePropelWriterPluginTest
 * Add your own group annotations below this line
 */
class CombinedProductImagePropelWriterPluginTest extends AbstractWriterPluginTest
{
    /**
     * @var string
     */
    public const DATA_IMPORTER_TYPE_PRODUCT_ABSTRACT = 'combined-product-abstract';

    /**
     * @var string
     */
    public const DATA_IMPORTER_TYPE_PRODUCT_CONCRETE = 'combined-product-concrete';

    /**
     * @var string
     */
    public const DATA_IMPORTER_TYPE_IMAGE_ABSTRACT = 'combined-product-image-abstract';

    /**
     * @var string
     */
    public const DATA_IMPORTER_TYPE_IMAGE_CONCRETE = 'combined-product-image-concrete';

    /**
     * @var string
     */
    protected const CSV_IMPORT_FILE = 'import/CombinedProductImage/combined_product.csv';

    /**
     * @return void
     */
    public function testProductAbstractWriter(): void
    {
        //Arrange
        $dataImportBusinessFactory = $this->getDataImportBusinessFactoryStub();
        $dataImport = $dataImportBusinessFactory->createCombinedProductAbstractImporter(
            $this->getDataImportConfigurationActionTransfer(static::DATA_IMPORTER_TYPE_PRODUCT_ABSTRACT),
        );

        //Act
        $dataImporterReportTransfer = $dataImport->import();

        //Assert
        $this->assertInstanceOf(DataImporterReportTransfer::class, $dataImporterReportTransfer);
        $this->assertSame(1, $dataImporterReportTransfer->getImportedDataSetCount());
    }

    /**
     * @return void
     */
    public function testProductConcreteWriter(): void
    {
        //Arrange
        $dataImportBusinessFactory = $this->getDataImportBusinessFactoryStub();
        $dataImport = $dataImportBusinessFactory->createCombinedProductConcreteImporter(
            $this->getDataImportConfigurationActionTransfer(static::DATA_IMPORTER_TYPE_PRODUCT_CONCRETE),
        );

        //Act
        $dataImporterReportTransfer = $dataImport->import();

        //Assert
        $this->assertInstanceOf(DataImporterReportTransfer::class, $dataImporterReportTransfer);
        $this->assertSame(1, $dataImporterReportTransfer->getImportedDataSetCount());
    }

    /**
     * @return void
     */
    public function testProductImageAbstractWriter(): void
    {
        //Arrange
        $dataImportBusinessFactory = $this->getDataImportBusinessFactoryStub();
        $dataImport = $dataImportBusinessFactory->createCombinedProductImageAbstractImporter(
            $this->getDataImportConfigurationActionTransfer(static::DATA_IMPORTER_TYPE_IMAGE_ABSTRACT),
        );

        //Act
        $dataImporterReportTransfer = $dataImport->import();

        //Assert
        $this->assertInstanceOf(DataImporterReportTransfer::class, $dataImporterReportTransfer);
        $this->assertSame(1, $dataImporterReportTransfer->getImportedDataSetCount());
    }

    /**
     * @return void
     */
    public function testProductImageConcreteWriter(): void
    {
        //Arrange
        $dataImportBusinessFactory = $this->getDataImportBusinessFactoryStub();
        $dataImport = $dataImportBusinessFactory->createCombinedProductImageConcreteImporter(
            $this->getDataImportConfigurationActionTransfer(static::DATA_IMPORTER_TYPE_IMAGE_CONCRETE),
        );

        //Act
        $dataImporterReportTransfer = $dataImport->import();

        //Assert
        $this->assertInstanceOf(DataImporterReportTransfer::class, $dataImporterReportTransfer);
        $this->assertSame(1, $dataImporterReportTransfer->getImportedDataSetCount());
    }

    /**
     * @return array
     */
    public function getDataImportWriterPlugins(): array
    {
        return [
            new CombinedProductImagePropelWriterPlugin(),
        ];
    }

    /**
     * @param string $dataEntity
     *
     * @return \Generated\Shared\Transfer\DataImportConfigurationActionTransfer
     */
    public function getDataImportConfigurationActionTransfer(string $dataEntity = ''): DataImportConfigurationActionTransfer
    {
        return (new DataImportConfigurationActionTransfer())
            ->setDataEntity($dataEntity)
            ->setSource(static::CSV_IMPORT_FILE);
    }
}
