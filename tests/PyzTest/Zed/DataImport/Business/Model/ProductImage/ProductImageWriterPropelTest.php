<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Zed\DataImport\Business\Model\ProductImage;

/**
 * Auto-generated group annotations
 * @group PyzTest
 * @group Zed
 * @group DataImport
 * @group Business
 * @group Model
 * @group ProductImage
 * @group ProductImageWriterPropelTest
 * Add your own group annotations below this line
 */
class ProductImageWriterPropelTest extends AbstractProductImageWriterTest
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
        $writer = $this->getDataImportBusinessFactoryStub()->createProductImagePropelWriter();

        $abstractProducts = $this->getAbstractProducts();
        $locale = $this->getLocale();
        $dataSets = $this->createDataSets($abstractProducts, $locale);
        foreach ($dataSets as $dataSet) {
            $writer->write($dataSet);
        }

        $this->assertImportedData($dataSets, $this->queryDataFromDB($abstractProducts, $locale));
    }
}
