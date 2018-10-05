<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Zed\DataImport\Business\Model\ProductAbstractStore;

use Generated\Shared\Transfer\ProductAbstractStoreTransfer;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractTableMap;
use Orm\Zed\Product\Persistence\SpyProductAbstractQuery;
use Orm\Zed\Product\Persistence\SpyProductAbstractStoreQuery;
use Orm\Zed\Store\Persistence\Map\SpyStoreTableMap;
use Pyz\Zed\DataImport\Business\Model\ProductAbstractStore\ProductAbstractStoreHydratorStep;
use PyzTest\Zed\DataImport\Business\Model\AbstractWriterTest;
use Spryker\Shared\Kernel\Store;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSet;

/**
 * Auto-generated group annotations
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
    protected const PRODUCTS_LIMIT = 2;

    /**
     * @return array
     */
    protected function createDataSets(): array
    {
        $result = [];
        $abstractProductSkus = $this->getAbstractProductSkus();
        foreach ($abstractProductSkus as $abstractProductSku) {
            $dataSet = new DataSet();
            $dataSet[ProductAbstractStoreHydratorStep::DATA_PRODUCT_ABSTRACT_STORE_ENTITY_TRANSFER] = (new ProductAbstractStoreTransfer())
                ->setProductAbstractSku($abstractProductSku)
                ->setStoreName(Store::getDefaultStore());

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
                ->filterByName(Store::getDefaultStore())
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

        /** @var \Orm\Zed\Product\Persistence\SpyProductAbstract $abstractProductEntity */
        foreach ($productAbstractStores as $productAbstractStore) {
            $productAbstractStoreDataSet = $dataSets[$productAbstractStore[SpyProductAbstractTableMap::COL_SKU]];
            $this->assertNotEmpty($productAbstractStoreDataSet);
            $this->assertEquals(
                $productAbstractStoreDataSet[ProductAbstractStoreHydratorStep::DATA_PRODUCT_ABSTRACT_STORE_ENTITY_TRANSFER]->getStoreName(),
                $productAbstractStore[SpyStoreTableMap::COL_NAME]
            );
        }
    }

    /**
     * @return array
     */
    protected function getAbstractProductSkus(): array
    {
        return SpyProductAbstractQuery::create()
            ->select(SpyProductAbstractTableMap::COL_SKU)
            ->limit(static::PRODUCTS_LIMIT)
            ->find()
            ->toArray();
    }
}
