<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Zed\DataImport\Business\Model\ProductAbstractStore;

/**
 * Auto-generated group annotations
 * @group PyzTest
 * @group Zed
 * @group DataImport
 * @group Business
 * @group Model
 * @group ProductAbstractStore
 * @group ProductAbstractStoreWriterPdoTest
 * Add your own group annotations below this line
 */
class ProductAbstractStoreWriterPdoTest extends AbstractProductAbstractStoreWriterTest
{
    /**
     * @group ProductWriterTest
     * @group ProductAbstractStoreWriterTest
     * @group ProductAbstractStoreWriterPropelTest
     *
     * @return void
     */
    public function testProductAbstractStoreWriter(): void
    {
        $writer = $this->getDataImportBusinessFactoryStub()->createProductAbstractStoreBulkPdoWriter();

        $dataSets = $this->createDataSets();
        foreach ($dataSets as $dataSet) {
            $writer->write($dataSet);
        }
        $writer->flush();

        $this->assertImportedData($dataSets, $this->queryDataFromDB(array_keys($dataSets)));
    }
}
