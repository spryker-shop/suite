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
     * @group ProductStockWriterTest
     * @group ProductStockWriterPropelTest
     *
     * @return void
     */
    public function testProductStockWriter(): void
    {
        $writer = $this->getDataImportBusinessFactoryStub()->createProductStockPropelWriter();

        $productSkus = $this->getProductsSkus();
        $warehouses = $this->getWarehouses();

        $dataSets = $this->createDataSets($productSkus, $warehouses);
        foreach ($dataSets as $dataSet) {
            $writer->write($dataSet);
        }

        $this->assertImportedData($dataSets, $this->queryDataFromDB($productSkus, $warehouses));
    }
}
