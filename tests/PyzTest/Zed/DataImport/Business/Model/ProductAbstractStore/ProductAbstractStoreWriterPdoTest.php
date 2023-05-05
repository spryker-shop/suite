<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Zed\DataImport\Business\Model\ProductAbstractStore;

use Spryker\Zed\Product\Business\ProductFacadeInterface;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

/**
 * Auto-generated group annotations
 *
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
    use LocatorHelperTrait;

    /**
     * @group ProductWriterTest
     * @group ProductAbstractStoreWriterTest
     * @group ProductAbstractStoreWriterPropelTest
     *
     * @return void
     */
    public function testProductAbstractStoreWriter(): void
    {
        //these SKUs data comes from import/ProductAbstractStore/product_abstract_store.csv
        foreach ($this->testSkus as $sku) {
            if (!$this->getProductFacade()->findProductAbstractIdBySku($sku)) {
                $this->tester->haveProductAbstract(['sku' => $sku]);
            }
        }
        $this->markTestSkippedOnDatabaseConstraintsMismatch();

        $writer = $this->getDataImportBusinessFactoryStub()->createProductAbstractStoreBulkPdoWriter();

        $dataSets = $this->createDataSets();
        foreach ($dataSets as $dataSet) {
            $writer->write($dataSet);
        }
        $writer->flush();

        $this->assertImportedData($dataSets, $this->queryDataFromDB($this->testSkus));
    }

    /**
     * @return \Spryker\Zed\Product\Business\ProductFacadeInterface
     */
    private function getProductFacade(): ProductFacadeInterface
    {
        return $this->getLocator()->product()->facade();
    }
}
