<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Zed\DataImport\Business\Model\ProductAbstract;

/**
 * Auto-generated group annotations
 * @group PyzTest
 * @group Zed
 * @group DataImport
 * @group Business
 * @group Model
 * @group ProductAbstract
 * @group ProductAbstractWriterPropelTest
 * Add your own group annotations below this line
 */
class ProductAbstractWriterPropelTest extends AbstractProductAbstractWriterTest
{
    /**
     * @group ProductWriterTest
     * @group ProductAbstractWriterTest
     * @group ProductAbstractWriterPropelTest
     *
     * @return void
     */
    public function testProductAbstractWriter(): void
    {
        $writer = $this->getDataImportBusinessFactoryStub()->createProductAbstractPropelWriter();

        $dataSets = $this->createDataSets();
        foreach ($dataSets as $dataSet) {
            $writer->write($dataSet);
        }

        $this->assertImportedData($dataSets, $this->queryDataFromDB(array_keys($dataSets)));
    }
}
