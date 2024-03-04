<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Zed\DataImport\Business\Model\ProductAbstractStore;

use Generated\Shared\Transfer\ProductAbstractStoreTransfer;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractTableMap;
use Orm\Zed\Product\Persistence\SpyProductAbstractStoreQuery;
use Orm\Zed\Store\Persistence\Map\SpyStoreTableMap;
use Pyz\Zed\DataImport\Business\Model\ProductAbstractStore\ProductAbstractStoreHydratorStep;
use PyzTest\Zed\DataImport\Business\Model\AbstractWriterTest;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSet;

/**
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Zed
 * @group DataImport
 * @group Business
 * @group Model
 * @group ProductAbstractStore
 * @group AbstractProductAbstractStoreWriterTest
 * Add your own group annotations below this line
 */
abstract class AbstractProductAbstractStoreWriterTest extends AbstractWriterTest
{
    /**
     * @var \PyzTest\Zed\DataImport\DataImportBusinessTester
     */
    protected $tester;

    /**
     * @var string
     */
    protected const DEFAULT_STORE_NAME = 'DE';

    /**
     * @var int
     */
    protected const PRODUCTS_LIMIT = 2;

    /**
     * @var array<string> these SKUs data comes from import/ProductAbstractStore/product_abstract_store.csv
     */
    protected $testSkus = ['testSku-228971244', 'testSku-228971245'];

    /**
     * @return array
     */
    protected function createDataSets(): array
    {
        $result = [];
        foreach ($this->testSkus as $abstractProductSku) {
            $dataSet = new DataSet();
            $dataSet[ProductAbstractStoreHydratorStep::DATA_PRODUCT_ABSTRACT_STORE_ENTITY_TRANSFER] = (new ProductAbstractStoreTransfer())
                ->setProductAbstractSku($abstractProductSku)
                ->setStoreName(static::DEFAULT_STORE_NAME);

            $result[$abstractProductSku] = $dataSet;
        }

        return $result;
    }

    /**
     * @param array $skus
     *
     * @return array
     */
    protected function queryDataFromDB(array $skus): array
    {
        return SpyProductAbstractStoreQuery::create()
            ->useSpyProductAbstractQuery()
                ->filterBySku_In($skus)
            ->endUse()
            ->useSpyStoreQuery()
                ->filterByName(static::DEFAULT_STORE_NAME)
            ->endUse()
            ->select([SpyProductAbstractTableMap::COL_SKU, SpyStoreTableMap::COL_NAME])
            ->find()
            ->toArray();
    }

    /**
     * @param array $dataSets
     * @param array $productAbstractStores
     *
     * @return void
     */
    protected function assertImportedData(array $dataSets, array $productAbstractStores): void
    {
        $this->assertCount(count($dataSets), $productAbstractStores);

        foreach ($productAbstractStores as $productAbstractStore) {
            $productAbstractStoreDataSet = $dataSets[$productAbstractStore[SpyProductAbstractTableMap::COL_SKU]];
            $this->assertNotEmpty($productAbstractStoreDataSet);
            $this->assertSame(
                $productAbstractStoreDataSet[ProductAbstractStoreHydratorStep::DATA_PRODUCT_ABSTRACT_STORE_ENTITY_TRANSFER]->getStoreName(),
                $productAbstractStore[SpyStoreTableMap::COL_NAME],
            );
        }
    }
}
