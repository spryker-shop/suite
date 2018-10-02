<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Zed\DataImport\Business\Model\ProductConcrete;

/**
 * Auto-generated group annotations
 * @group PyzTest
 * @group Zed
 * @group DataImport
 * @group Business
 * @group Model
 * @group ProductConcrete
 * @group ProductConcreteWriterPropelTest
 * Add your own group annotations below this line
 */
class ProductConcreteWriterPropelTest extends AbstractProductConcreteWriterTest
{
    /**
     * @group ProductWriterTest
     * @group ProductConcreteWriterTest
     * @group ProductConcreteWriterPropelTest
     *
     * @return void
     */
    public function testProductConcreteWriter(): void
    {
        $writer = $this->getDataImportBusinessFactoryStub()->createProductConcretePropelWriter();

        $dataSets = $this->createDataSets();
        foreach ($dataSets as $dataSet) {
            $writer->write($dataSet);
        }

        $this->assertImportedData($dataSets, $this->queryDataFromDB(array_keys($dataSets)));
    }
}
