<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Zed\DataImport\Business\Model\ProductImage;

use Orm\Zed\Product\Persistence\Map\SpyProductAbstractTableMap;
use Pyz\Zed\DataImport\Business\Model\PropelMariaDBVersionConstraintException;

/**
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Zed
 * @group DataImport
 * @group Business
 * @group Model
 * @group ProductImage
 * @group ProductImageWriterPdoTest
 * Add your own group annotations below this line
 */
class ProductImageWriterPdoTest extends AbstractProductImageWriterTest
{
    /**
     * @group ProductWriterTest
     * @group ProductImageWriterTest
     * @group ProductImageWriterPropelTest
     *
     * @return void
     */
    public function testProductImageWriter(): void
    {
        $this->markTestSkipped('Need to implement MariaDB version check to decide whether we expect exception.');

        $this->expectException(PropelMariaDBVersionConstraintException::class);

        $writer = $this->getDataImportBusinessFactoryStub()->createProductImageBulkPdoWriter();

        $abstractProducts = $this->getAbstractProducts();
        $locale = $this->getLocale();
        $dataSets = $this->createDataSets($abstractProducts, $locale);
        foreach ($dataSets as $dataSet) {
            $writer->write($dataSet);
        }
        $writer->flush();

        $this->assertImportedData($dataSets, $this->queryDataFromDB($abstractProducts, $locale));
    }

    /**
     * @group ProductWriterTest
     * @group ProductImageWriterTest
     * @group ProductImageWriterPropelTest
     *
     * @return void
     */
    public function testProductImagesWithSameUrlAreSavedSeparately(): void
    {
        $this->markTestSkipped('Need to implement MariaDB version check to decide whether we expect exception.');

        $this->expectException(PropelMariaDBVersionConstraintException::class);

        // Arrange
        $writer = $this->getDataImportBusinessFactoryStub()->createProductImageBulkPdoWriter();
        $productImageEntityTransfer = $this->createProductImageEntityTransfer(
            static::DEFAULT_EXTERNAL_URL_LARGE,
            static::DEFAULT_EXTERNAL_URL_SMALL,
            uniqid('', true)
        );
        $abstractProducts = $this->getAbstractProducts(2);
        $locale = $this->getLocale();
        $dataSets = [];

        foreach ($abstractProducts as $abstractProduct) {
            $dataSets[$abstractProduct[SpyProductAbstractTableMap::COL_SKU]] = $this->createDataSet($abstractProduct, $locale, $productImageEntityTransfer);
        }

        foreach ($dataSets as $dataSet) {
            $writer->write($dataSet);
        }
        $writer->flush();

        $this->assertImportedData($dataSets, $this->queryDataFromDB($abstractProducts, $locale));
    }
}
