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
 * @group ProductAbstractStoreWriterPropelTest
 * Add your own group annotations below this line
 */
class ProductAbstractStoreWriterPropelTest extends AbstractProductAbstractStoreWriterTest
{
    use LocatorHelperTrait;

    /**
     * @group ProductWriterTest
     * @group ProductAbstractWriterTest
     * @group ProductAbstractWriterPropelTest
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
        $writer = $this->getDataImportBusinessFactoryStub()->createProductAbstractStorePropelWriter();

        $dataSets = $this->createDataSets();
        foreach ($dataSets as $dataSet) {
            $writer->write($dataSet);
        }

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
