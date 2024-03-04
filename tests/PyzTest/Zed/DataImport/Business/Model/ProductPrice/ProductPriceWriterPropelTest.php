<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Zed\DataImport\Business\Model\ProductPrice;

/**
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Zed
 * @group DataImport
 * @group Business
 * @group Model
 * @group ProductPrice
 * @group ProductPriceWriterPropelTest
 * Add your own group annotations below this line
 */
class ProductPriceWriterPropelTest extends AbstractProductPriceWriterTest
{
    /**
     * @group ProductWriterTest
     * @group ProductPriceWriterTest
     * @group ProductPriceWriterPropelTest
     *
     * @return void
     */
    public function testProductPriceWriter(): void
    {
        $writer = $this->getDataImportBusinessFactoryStub()->createProductPricePropelWriter();

        $dataSets = $this->createDataSets();
        foreach ($dataSets as $dataSet) {
            $writer->write($dataSet);
        }

        $this->assertImportedData($dataSets, $this->queryDataFromDB(array_keys($dataSets)));
    }
}
