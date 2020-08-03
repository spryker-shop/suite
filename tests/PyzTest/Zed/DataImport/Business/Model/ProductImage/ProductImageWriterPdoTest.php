<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Zed\DataImport\Business\Model\ProductImage;

use Orm\Zed\Product\Persistence\Map\SpyProductAbstractTableMap;
use Spryker\Shared\Config\Config;
use Spryker\Shared\Propel\PropelConstants;
use Spryker\Zed\Propel\PropelConfig;

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
        if (Config::get(PropelConstants::ZED_DB_ENGINE) !== PropelConfig::DB_ENGINE_PGSQL) {
            $this->markTestSkipped('PostgreSQL related test');
        }

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
        if (Config::get(PropelConstants::ZED_DB_ENGINE) !== PropelConfig::DB_ENGINE_PGSQL) {
            $this->markTestSkipped('PostgreSQL related test');
        }

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
