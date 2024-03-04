<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Zed\DataImport\Business\Model\ProductStock;

/**
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Zed
 * @group DataImport
 * @group Business
 * @group Model
 * @group ProductStock
 * @group ProductStockPdoTest
 * Add your own group annotations below this line
 */
class ProductStockPdoTest extends AbstractProductStockWriterTest
{
    /**
     * @var \PyzTest\Zed\DataImport\DataImportBusinessTester
     */
    protected $tester;

    /**
     * @group ProductWriterTest
     * @group ProductStockWriterTest
     * @group ProductStockWriterPropelTest
     *
     * @return void
     */
    public function testProductStockWriter(): void
    {
        // This will be fixed in next release
        $this->markTestSkipped(true);
        $writer = $this->getDataImportBusinessFactoryStub()->createProductStockBulkPdoWriter();

        $product = $this->tester->haveProduct();
        $stock = $this->tester->haveStock();
        $store = $this->tester->getAllowedStore();

        $this->tester->haveStockStoreRelation($stock, $store);

        $dataSets = $this->createDataSets([$product->getSku()], [$stock->getName()]);
        foreach ($dataSets as $dataSet) {
            $writer->write($dataSet);
        }
        $writer->flush();

        $this->assertImportedData($dataSets, $this->queryDataFromDB([$product->getSku()], [$stock->getName()]));
    }
}
