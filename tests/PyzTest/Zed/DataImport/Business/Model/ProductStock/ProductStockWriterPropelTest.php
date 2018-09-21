<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Zed\DataImport\Business\Model\ProductStock;

/**
 * Auto-generated group annotations
 * @group PyzTest
 * @group Zed
 * @group DataImport
 * @group Business
 * @group Model
 * @group ProductStock
 * @group ProductStockWriterPropelTest
 * Add your own group annotations below this line
 */
class ProductStockWriterPropelTest extends AbstractProductStockWriterTest
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
        $writer = $this->getDataImportBusinessFactoryStub()->createProductStockPropelWriter();

        $dataSets = $this->createDataSets();
        foreach ($dataSets as $dataSet) {
            $writer->write($dataSet);
        }

        $this->assertImportedData($dataSets, $this->queryDataFromDB());
    }
}
